<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Position;
use Illuminate\Support\Carbon;


class ElectionController extends Controller
{
    public function index()
    {
        $activeElections = Election::active()->get();

        return response()->json([
            'data' => $activeElections
        ]);
    }
    public function show($id)
    {
        $election = Election::with(['positions.candidates'])->findOrFail($id);
        return response()->json($election);
    }  
    public function candidates($id)
    {
        $candidates = Candidate::with(['user', 'position'])
            ->whereHas('position', function($query) use ($id) {
                $query->where('election_id', $id);
            })
            ->get();

        return response()->json($candidates);
    }
    public function getVotingStatus(Request $request, $election_id)
    {   
        $user = $request->user();
        $election = Election::findOrFail($election_id);

        $hasVoted = Vote::where('election_id', $election_id)
            ->where('voter_id', $user->user_id)
            ->exists();

        $now = Carbon::now();
        $isOngoing = $now->between($election->start_date, $election->end_date);

        return response()->json([
            'has_voted' => $hasVoted,
            'is_ongoing' => $isOngoing,
        ]);
    }
    public function getPositionsWithCandidates(Request $request, $election_id, $org_id)
    {
        if ($org_id >= 1 && $org_id <= 8) {
            $category = 'organization';
        } elseif ($org_id == 9) {
            $category = 'student_gov';
        } else {
            return response()->json(['message' => 'Invalid org_id'], 400);
        }

        $election = Election::find($election_id);
        if (!$election) {
            return response()->json(['message' => 'Election not found'], 404);
        }

        $positions = Position::where('category', $category)->get();

        $positions = $positions->map(function ($position) use ($election_id) {
            $candidates = Candidate::where('position_id', $position->position_id)
                ->where('election_id', $election_id)
                ->with('user:user_id,name') 
                ->get()
                ->map(function ($candidate) {
                    return [
                        'candidate_id' => $candidate->candidate_id,
                        'name' => $candidate->user->name ?? 'Unknown',
                        'image_path' => $candidate->description,
                    ];
                });

            if ($candidates->isEmpty()) return null;

            return [
                'position_id' => $position->position_id,
                'position_name' => $position->position_name,
                'candidates' => $candidates,
            ];
        })->filter()->values();

        return response()->json([
            'election_id' => $election_id,
            'category' => $category,
            'positions' => $positions,
        ]);
    }
}
