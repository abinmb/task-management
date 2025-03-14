<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected TaskService $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    public function list(Request $request): JsonResponse
    {
        try {
            $tasks = $this->taskService->listTask(['user_id' => Auth::id()]);

            if ($tasks->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'failed'], status: 400);
            }
            return response()->json(['status' => true, 'message' => "tasks fetched successfully", 'data' => $tasks], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function listData(Request $request, $id): JsonResponse
    {
        try {
            $task = $this->taskService->listTasksById($id, Auth::id());
            if (!$task) {
                return response()->json(['status' => false, 'message' => 'failed'], 400);
            }
            return response()->json(['status' => true, 'message' => "task fetched successfully", 'data' => $task], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function complete($id): JsonResponse
    {
        try {
            $task = $this->taskService->completeTask($id, Auth::id());
            if (!$task) {
                return response()->json(['status' => false, 'message' => 'failed'], status: 400);
            }
            return response()->json(['status' => true, 'message' => 'Task status changed to completed', 'data' => $task], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
