<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CandidateController extends Controller
{
    public function index()
    {
        return Candidate::with(['user', 'election', 'position'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'election_id' => 'required|exists:elections,election_id',
            'position_id' => 'required|exists:positions,position_id',
            'description' => 'nullable|string',
        ]);

        $candidate = Candidate::create($validated);
        
        $userId = Auth::id() ?? 1;
        $this->logAudit($userId, 'created', 'Candidate', $candidate->candidate_id, $candidate->toArray());
        
        return response()->json($candidate, 201);
    }

    public function show($id)
    {
        $candidate = Candidate::with(['user', 'election', 'position'])->findOrFail($id);
        return response()->json($candidate);
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);
        $oldData = $candidate->toArray(); 

        $validated = $request->validate([
            'user_id'     => 'required|exists:users,user_id',
            'election_id' => 'required|exists:elections,election_id',
            'position_id' => 'required|exists:positions,position_id',
            'description' => 'nullable|string',
        ]);

        $candidate->update($validated);

        $userId = Auth::id() ?? 1;
        $this->logAudit($userId, 'updated', 'Candidate', $candidate->candidate_id, [
            'before' => $oldData,
            'after'  => $candidate->toArray(),
        ]);

        return response()->json($candidate);
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        $oldData = $candidate->toArray(); 
        $candidate->delete();

        $userId = Auth::id() ?? 1;
        $this->logAudit($userId, 'deleted', 'Candidate', $candidate->candidate_id, $oldData);

        return response()->json(['message' => 'Candidate deleted successfully.']);
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
    public function getCandidatesByElection($electionId)
    {
        $candidates = Candidate::with(['user', 'position'])
                        ->where('election_id', $electionId)
                        ->get();

        return response()->json($candidates);
    }
}
