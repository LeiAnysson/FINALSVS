<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orgIds = $user->organizations()->pluck('organizations.org_id')->toArray();

        if (empty($orgIds)) {
            return response()->json(['message' => 'No organizations found for user.'], 404);
        }

        $elections = Election::whereIn('org_id', $orgIds)->get();

        return response()->json($elections);
    }

    public function store(Request $request)
    {   
        Log::info('Creating election with data:', $request->all());
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $org = $user->organizations()->first();
        if (! $org) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'candidates' => 'required|array',
            'candidates.*.name' => 'required|string',
            'candidates.*.position_id' => 'required|exists:positions,position_id',
            'candidates.*.description' => 'nullable|string' 
        ]);

        DB::beginTransaction();

        try {
            $election = Election::create([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
                'created_by' => $user->user_id,
                'org_id' => $org->org_id,
            ]);

            if ($request->has('candidates')) {
                $candidates = $request->input('candidates');
                $files = $request->file('candidates');

                foreach ($candidates as $index => $candidateData) {
                    $candidateUser = User::where('name', $candidateData['name'])->first();

                    if (!$candidateUser) {
                        DB::rollback();
                        return response()->json(['error' => "User with name {$candidateData['name']} not found"], 400);
                    }

                    $photo = null;
                    if ($files && isset($files[$index]['photo'])) {
                        $photoFile = $files[$index]['photo'];
                        $photoPath = $photoFile->store('candidate_photos', 'public');
                        $photo = $photoPath;
                    }

                    Candidate::create([
                        'name' => $candidateData['name'],       // keep the name from input
                        'position_id' => $candidateData['position_id'],
                        'election_id' => $election->election_id, // use election_id if that is your PK
                        'description' => $photo,                 // store photo path here if any
                        'user_id' => $candidateUser->user_id,   // assign found user's id
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Election created', 'election' => $election], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $election = Election::with('candidates')->findOrFail($id);
        return response()->json($election);
    }

    public function update(Request $request, $id)
    {
        $election = Election::findOrFail($id);
        $oldData = $election->toArray();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
        ]);

        $election->update($validated);

        $userId = Auth::id() ?? 1;
        $this->logAudit($userId, 'updated', 'Election', $election->election_id, [
            'before' => $oldData,
            'after' => $election->toArray(),
        ]);

        return response()->json($election);
    }

    public function destroy($id)
    {
        $election = Election::findOrFail($id);
        $election->delete();

        $userId = Auth::id() ?? 1;
        $this->logAudit($userId, 'deleted', 'Election', $id, ['id' => $id]);

        return response()->json(['message' => 'Election deleted successfully.']);
    }

    protected function logAudit($userId, $action, $entityType, $entityId, $changes = null)
    {
        try {
            \App\Models\AuditLog::create([
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
