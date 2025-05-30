<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category'); 

        if ($category) {
            $positions = Position::where('category', $category)->get();
        } else {
            $positions = Position::all();
        }

        return response()->json(['positions' => $positions]);
    }
}
