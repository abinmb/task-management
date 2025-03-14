<?php

namespace App\Services;

use App\Events\TaskCompleted;
use App\Jobs\NotificationEmailJob;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function createTask($data)
    {
        try {
            $task = Task::create([
                'title'       => $data['title'],
                'description' => $data['description'] ?? null,
                'status'      => $data['status'] ?? 'pending'
            ]);
            return $task;
        } catch (\Exception $e) {
            Log::error('Task creation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function assignTask($data, $id)
    {
        try {
            $task = Task::findOrFail($id);
            $user = User::findOrFail($data['user_id']);

            $updateData = ['user_id' => $user->id];

            if (isset($data['due_date'])) {
                $updateData['due_date'] = Carbon::createFromFormat('d-m-Y', $data['due_date'])->format('Y-m-d H:i:s');
            }

            $task->update($updateData);

            NotificationEmailJob::dispatch($user, $task)->onQueue('emails');

            return $task;
        } catch (\Exception $e) {
            Log::error('Task assign failed: ' . $e->getMessage());
            return null;
        }
    }

    public function listTask(array $filters = [])
    {
        try {
            $query = Task::with('user')->latest();

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }

            return $query->paginate(25);
        } catch (\Exception $e) {
            Log::error('Task list failed: ' . $e->getMessage());
            return null;
        }
    }

    public function listTaskById($id)
    {
        try {
            return Task::with('user')->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Task list failed: ' . $e->getMessage());
            return null;
        }
    }

    public function changeTaskStatus($id, $status)
    {
        try {
            $task = Task::findOrFail($id);
            $task->update(['status' => $status]);

            return $task;
        } catch (\Exception $e) {
            Log::error('Task change status failed: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteTask($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Task change status failed: ' . $e->getMessage());
            return false;
        }
    }
    public function listTasksById($id, $userId)
    {
        try {
            return Task::with('user')->where('user_id', $userId)->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Task list failed: ' . $e->getMessage());
            return null;
        }
    }
    public function completeTask($id,  $userId)
    {
        try {
            $task = Task::where('id', $id)->where('user_id', $userId)->firstOrFail();
            $task->update(['status' => 'completed']);
            event(new TaskCompleted($task));
            return $task;
        } catch (\Exception $e) {
            Log::error('Task list failed: ' . $e->getMessage());
            return null;
        }
    }
}
