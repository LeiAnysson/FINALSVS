<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function submitVote(Request $request)
    {
        $request->validate([
            'election_id' => 'required|exists:elections,election_id',
            'candidate_id' => 'required|exists:candidates,candidate_id',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $candidate = Candidate::findOrFail($request->candidate_id);

        $existingVote = Vote::where('voter_id', $user->user_id)
            ->where('election_id', $request->election_id)
            ->whereHas('candidate', function ($query) use ($candidate) {
                $query->where('position_id', $candidate->position_id);
            })
            ->first();

        if ($existingVote) {
            return response()->json([
                'message' => 'You have already voted for this position in this election.'
            ], 409);
        }

        $vote = Vote::create([
            'voter_id' => $user->user_id,
            'candidate_id' => $request->candidate_id,
            'election_id' => $request->election_id,
            'voted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Vote submitted successfully',
            'vote' => [
                'vote_id' => $vote->id,
                'candidate_id' => $vote->candidate_id,
                'position_id' => $candidate->position_id,
                'election_id' => $vote->election_id,
                'voted_at' => $vote->voted_at,
            ],
        ]);
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user(); 

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password verified'], 200);
        }

        return response()->json(['message' => 'Incorrect password'], 403);
    }
}
