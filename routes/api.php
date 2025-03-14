<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\User\TaskController as UserTaskController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'log_data'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/tasks', [AdminTaskController::class, 'list']);
        Route::post('/tasks', [AdminTaskController::class, 'create']);
        Route::put('/tasks/{id}/assign', [AdminTaskController::class, 'assign']);
        Route::get('/tasks/{id}/list', [AdminTaskController::class, 'listData']);
        Route::put('/tasks/{id}/status', [AdminTaskController::class, 'changeStatus']);
        Route::delete('/tasks/{id}/delete', [AdminTaskController::class, 'destroy']);
    });

    Route::middleware('user')->prefix('user')->group(function () {
        Route::get('/tasks', [UserTaskController::class, 'list']);
        Route::get('/tasks/{id}/list', [UserTaskController::class, 'listData']);
        Route::put('/tasks/{id}/complete', [UserTaskController::class, 'complete']);
    });
});
