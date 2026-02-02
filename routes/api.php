<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\HabitController;
use App\Http\Controllers\Api\AuthController;

Route::get('/health', function() {
    return response()->json([
        'status' => 'ok'
    ]);
});

// These routes wre used at the begining of the development for testing
// purposes and may eed to be deleted soon.
Route::apiResource('tasks', TaskController::class);
Route::apiResource('habits', HabitController::class);
Route::post('habits/{habit}/complete', [HabitController::class, 'complete']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});