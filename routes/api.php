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
use App\Http\Controllers\UserController;
//MOBILE
use App\Http\Controllers\Mobile\AuthController as MobileAuthController;
use App\Http\Controllers\Mobile\ElectionController as MobileElectionController;
use App\Http\Controllers\Mobile\VoteController as MobileVoteController;

//------------------WEB--------------------------

//front end connection
Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Connected!']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//student login
Route::post('/login', [AuthController::class, 'login']);
Route::prefix('mobile')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [UserController::class, 'profile']);
    });
});

//teacher login
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::get('/login', function () {
    return response()->json(['message' => 'Login route placeholder']);
})->name('login');

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
Route::get('/users/search', [UserController::class, 'searchByName']);
Route::get('/admin/elections/{election_id}/candidates', [ElectionController::class, 'getCandidates']);

//Election page
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResource('elections', ElectionController::class);
    Route::apiResource('candidates', CandidateController::class);
});

//------------------MOBILE--------------------------
Route::prefix('mobile')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [MobileAuthController::class, 'logout']);
        Route::get('/elections', [MobileElectionController::class, 'index']);
        Route::get('/elections/{id}', [MobileElectionController::class, 'show']);
        Route::get('/elections/{id}/candidates', [MobileElectionController::class, 'candidates']);
        Route::post('/votes/submit', [MobileVoteController::class, 'submitVote']);
        Route::get('/user', [UserController::class, 'profile']);
        Route::get('/elections/{election_id}/status', [MobileElectionController::class, 'getVotingStatus']);
        Route::get('/elections/{election_id}/org/{org_id}/positions', [MobileElectionController::class, 'getPositionsWithCandidates']);
        Route::post('/verify-password', [MobileVoteController::class, 'verifyPassword']);
    });

    Route::get('/test', function () {
        return response()->json(['message' => 'Mobile API test']);
    });
    
});
Route::middleware('auth:sanctum')->get('/mobile/check', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});
