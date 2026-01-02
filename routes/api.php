<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WorkspacesController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/workspaces', [WorkspacesController::class, 'createWorkspace']);
    Route::get('/workspaces', [WorkspacesController::class, 'index']);
    Route::get('/workspaces/{workspace}', [WorkspacesController::class, 'show']);
    Route::middleware(['workspace.member'])
        ->scopeBindings()
        ->group(function () {
            Route::get('/workspaces/{workspace}/tickets', [TicketController::class, 'index']);
            Route::post('/workspaces/{workspace}/tickets', [TicketController::class, 'store']);
            Route::get('/workspaces/{workspace}/tickets/{ticket}', [TicketController::class, 'show']);
            Route::patch('/workspaces/{workspace}/tickets/{ticket}', [TicketController::class, 'update']);
            Route::post('/workspaces/{workspace}/tickets/{ticket}/assign', [TicketController::class, 'assign']);
            Route::post('/workspaces/{workspace}/tickets/{ticket}/close', [TicketController::class, 'close']);
        });
});
