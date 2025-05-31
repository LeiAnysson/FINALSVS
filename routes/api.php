<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PositionController;

//front end connection
Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Connected!']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//student login
Route::post('/login', [AuthController::class, 'login']);
//teacher login
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//Orgs
Route::get('/admin/organizations', [OrganizationController::class, 'index']);

//positions
Route::get('admin/positions', [PositionController::class, 'index']);

//dashboard
Route::middleware('auth:sanctum')->get('/teacher/dashboard', [TeacherDashboardController::class, 'index']);

//voter page
Route::get('/admin/voters', [VoterController::class, 'index']);
Route::prefix('admin')->group(function () {
    Route::post('/voters', [VoterController::class, 'store']);
    Route::post('/upload-voters-csv', [VoterController::class, 'importCsv']);
});
Route::delete('/admin/voters/{user_id}', [VoterController::class, 'deleteVoter']);

//Candidate page
Route::prefix('admin')->group(function () {
    Route::get('/candidates', [CandidateController::class, 'index']);
    Route::post('/candidates', [CandidateController::class, 'store']);
    Route::put('/candidates/{id}', [CandidateController::class, 'update']);
    Route::delete('/candidates/{id}', [CandidateController::class, 'destroy']);
});
Route::get('/admin/elections/{id}/candidates', [CandidateController::class, 'getCandidatesByElection']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/upload-image', [CandidateController::class, 'uploadImage']);
});

//Election page
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('elections', ElectionController::class);
    Route::apiResource('candidates', CandidateController::class);
});