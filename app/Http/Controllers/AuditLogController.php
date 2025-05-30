<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        // Eager load the related user to avoid N+1 problem
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->get();

        return response()->json($logs);
    }
}
