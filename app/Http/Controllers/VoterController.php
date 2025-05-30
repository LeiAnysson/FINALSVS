<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VoterController extends Controller
{
    public function index()
    {
        $students = User::where('role_id', 2)->get();

        $formatted = $students->map(function ($student) {
        $nameParts = explode(' ', $student->name);
        return [
            'studentID' => $student->student_ID ?? '[student_id missing]',
            'firstName' => $nameParts[0] ?? '',
            'middleName' => $nameParts[1] ?? '',
            'lastName' => isset($nameParts[2]) ? $nameParts[2] : ($nameParts[1] ?? ''),
            'program' => $student->course ?? 'N/A',
            'status' => $student->status ?? 'Not Voted',
        ];
    });

        return response()->json($formatted);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_ID' => 'required|string|max:50|unique:users,student_ID',
            'email' => 'required|email|unique:users,email',
            'course' => 'nullable|string|max:100',
        ]);

        $newVoter = User::create([
            'name' => $validated['name'],
            'student_ID' => $validated['student_ID'],
            'email' => $validated['email'],
            'course' => $validated['course'] ?? null,
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        $userId = Auth::id() ?? 1;
        $this->logAudit($userId, 'created', 'Voter', $newVoter->id, [
            'name' => $newVoter->name,
            'email' => $newVoter->email,
            'student_ID' => $newVoter->student_ID,
            'course' => $newVoter->course,
        ]);

        $formatted = [
            'studentID' => $newVoter->student_ID ?? '[student_id missing]',
            'firstName' => explode(' ', $newVoter->name)[0] ?? '',
            'middleName' => explode(' ', $newVoter->name)[1] ?? '',
            'lastName' => explode(' ', $newVoter->name)[2] ?? '',
            'program' => $newVoter->course ?? 'N/A',
            'status' => $newVoter->status ?? 'Not Voted',
        ];

        return response()->json($formatted);
    }
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');

        $header = array_map('trim', fgetcsv($file));
        $count = 0;
        $skipped = [];

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            $email = strtolower(trim($data['email']));

            if (User::where('email', $email)->exists()) {
                $skipped[] = $email;
                continue;
            }

            User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => 2,
                'student_ID' => $data['student_ID'] ?? null,
                'course' => $data['course'] ?? null,
            ]);

            $count++;
        }

        fclose($file);

        $userId = Auth::id() ?? 1;

        $this->logAudit($userId, 'created', 'VoterImport', null, [
            'imported' => $count,
            'skipped' => $skipped,
        ]);

        return response()->json([
            'message' => "$count voters imported successfully!",
            'skipped' => $skipped ?: 'None were skipped',
        ]);
    }

    public function deleteVoter($user_id)
    {
        $user = User::where('role_id', 2)->find($user_id);

        if (!$user) {
            return response()->json([
                'message' => 'Voter not found or not a student.'
            ], 404);
        }

        $user->delete();

        $userId = Auth::id() ?? 1;

        $this->logAudit($userId, 'deleted', 'Voter', $user_id, [
            'name' => $user->name,
            'email' => $user->email
        ]);

        return response()->json([
            'message' => 'Voter deleted successfully.'
        ]);
    }

    protected function logAudit($userId, $action, $entityType, $entityId, $changes = null)
    {
        try {
            AuditLog::create([
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'changes' => $changes ? json_encode($changes) : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Audit log error: ' . $e->getMessage());
        }
    }
}