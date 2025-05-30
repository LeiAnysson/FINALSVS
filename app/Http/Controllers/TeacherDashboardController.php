<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Vote;


use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_casts' => Vote::count(),
        ]);
    }
}
