<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\AuditLog;
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
        Log::info('Store Election Request:', $request->all()); //remove
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized. User not authenticated.'], 401);
        }

        $org = $user->organizations()->first();
        if (!$org) {
            return response()->json(['message' => 'Organization not found for user.'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'candidates' => 'array',
            'candidates.*.user_id' => 'required|exists:users,id',
            'candidates.*.position_id' => 'required|exists:positions,id',
        ]);

        $validated['created_by'] = $user->user_id;
        $validated['org_id'] = $org->org_id;

        $election = Election::create($validated);
        
        if (!empty($validated['candidates'])) {
            $election->candidates()->createMany($validated['candidates']);
}

        $this->logAudit($user->id, 'created', 'Election', $election->election_id, $election->toArray());

        return response()->json($election, 201);
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
