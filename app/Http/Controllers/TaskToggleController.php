<?php

namespace App\Http\Controllers;

use App\Models\TodoTask;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TaskToggleController extends Controller
{
    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(TodoTask $task)
    {
        try {
            // Ensure the authenticated user owns this task
            if ($task->user_id !== Auth::id()) {
                throw new AuthorizationException('You are not authorized to modify this task.');
            }

            $newStatus = $task->is_complete ? 0 : 1;
            $task->update(['is_complete' => $newStatus]);

            return $this->sendSuccess(['task' => $task->fresh()], 'Task status toggled successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->sendError(message: 'Task not found.', code: 404);
        } catch (AuthorizationException $e) {
            return $this->sendError(message: $e->getMessage(), code: 403);
        } catch (Throwable $e) {
            report($e);
            return $this->sendError(message: 'An unexpected error occurred. Please try again later.', code: 500);
        }
    }
}
