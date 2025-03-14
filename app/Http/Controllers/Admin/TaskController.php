<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskAddRequest;
use App\Http\Requests\Task\TaskAssignRequest;
use App\Http\Requests\Task\TaskStatusRequest;
use App\Models\{Task, User};
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create(TaskAddRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->all());
            if (!$task) {
                return response()->json(['status' => false, 'message' => 'failed'], 400);
            }

            return response()->json(['status' => true, 'message' => 'Task created successfully', 'data' => $task], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function assign(TaskAssignRequest $request, $id): JsonResponse
    {
        try {
            $task = $this->taskService->assignTask($request->all(), $id);
            if (!$task) {
                return response()->json(['status' => false, 'message' => 'failed'], 400);
            }
            return response()->json(['status' => true, 'message' => 'Task assigned successfully', 'data' => $task], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $tasks = $this->taskService->listTask($request->all());
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
            $task = $this->taskService->listTaskById($id);
            if (!$task) {
                return response()->json(['status' => false, 'message' => 'failed'], 400);
            }
            return response()->json(['status' => true, 'message' => "task fetched successfully", 'data' => $task], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function changeStatus(TaskStatusRequest $request, $id): JsonResponse
    {
        try {
            $tasks = $this->taskService->changeTaskStatus($id, $request->status);
            if (!$tasks) {
                return response()->json(['status' => false, 'message' => 'failed'], 400);
            }

            return response()->json(['status' => true, 'message' => "Task status updated successfully", 'data' => $tasks], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
    public function destroy($id)
    {
        try {
            $deleted = $this->taskService->deleteTask($id);
            if (!$deleted) {
                return response()->json(['status' => false, 'message' => 'failed'], 400);
            }
            return response()->json(['status' => true, 'message' => 'Task deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
