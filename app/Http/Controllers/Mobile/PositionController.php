<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function mobilePositions()
    {
        $positions = Position::where('category', 'organization')->get(['position_id', 'position_name']);

        return response()->json([
            'positions' => $positions
        ]);
    }
}
