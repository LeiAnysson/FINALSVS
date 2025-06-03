<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function searchByName(Request $request)
    {
        $name = $request->query('name'); // 🔑 This is important for GET requests

        Log::info("Searching user for name: " . $name);

        $user = User::where('name', $name)->first();

        Log::info("Resolved user ID: " . ($user ? $user->user_id : 'NOT FOUND'));

        return response()->json([
            'user_id' => $user ? $user->user_id : null,
            'name' => $name
        ]);
    }
}
