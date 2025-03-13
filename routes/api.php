<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('admin')->group(function () {
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{id}/assign', [TaskController::class, 'assign']);
        Route::get('/tasks', [TaskController::class, 'index']);
    });

    Route::middleware('user')->group(function () {
        Route::put('/tasks/{id}/complete', [TaskController::class, 'complete']);
    });
});
