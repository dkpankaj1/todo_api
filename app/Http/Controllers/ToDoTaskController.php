<?php

namespace App\Http\Controllers;

use App\Http\Resources\ToDoTaskResource;
use App\Models\ToDoTask;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ToDoTaskController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = ToDoTask::whereNull('parent_id')
            ->where('user_id', Auth::id())
            ->with('subTask')
            ->get();

        return $this->sendSuccess(['tasks' => ToDoTaskResource::collection($tasks)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'     => ['required', 'string', 'max:255'],
                'parent_id' => ['nullable', 'exists:todo_tasks,id'],
                'ordered'   => ['nullable', 'integer'],
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->toArray(), 'Validation error.', 422);
            }

            // If parent_id is provided, verify the parent belongs to the authenticated user
            if ($request->filled('parent_id')) {
                $parent = ToDoTask::findOrFail($request->parent_id);
                if ($parent->user_id !== Auth::id()) {
                    throw new AuthorizationException('You are not authorized to add subtasks to this task.');
                }
            }

            $task = ToDoTask::create([
                'title'     => $request->title,
                'parent_id' => $request->parent_id,
                'user_id'   => Auth::id(),
                'ordered'   => $request->ordered ?? 0,
            ]);

            return $this->sendSuccess(
                ['task' => new ToDoTaskResource($task->load('subTask'))],
                'Task created successfully.',
                201
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError(message: 'Parent task not found.', code: 404);
        } catch (AuthorizationException $e) {
            return $this->sendError(message: $e->getMessage(), code: 403);
        } catch (Throwable $e) {
            report($e);
            return $this->sendError(message: 'An unexpected error occurred.', code: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ToDoTask $todos)
    {
        try {
            if ($todos->user_id !== Auth::id()) {
                throw new AuthorizationException('You are not authorized to view this task.');
            }

            return $this->sendSuccess([
                'task' => new ToDoTaskResource($todos->load('subTask')),
            ]);
        } catch (AuthorizationException $e) {
            return $this->sendError(message: $e->getMessage(), code: 403);
        } catch (Throwable $e) {
            report($e);
            return $this->sendError(message: 'An unexpected error occurred.', code: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ToDoTask $todos)
    {
        try {
            if ($todos->user_id !== Auth::id()) {
                throw new AuthorizationException('You are not authorized to update this task.');
            }

            $validator = Validator::make($request->all(), [
                'title'     => ['sometimes', 'string', 'max:255'],
                'ordered'   => ['sometimes', 'integer'],
                'parent_id' => ['nullable', 'exists:todo_tasks,id'],
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->toArray(), 'Validation error.', 422);
            }

            // Prevent a task from being its own parent
            if ($request->has('parent_id') && $request->parent_id == $todos->id) {
                return $this->sendError(
                    ['parent_id' => ['A task cannot be its own parent.']],
                    'Validation error.',
                    422
                );
            }

            // If parent_id is provided, verify the parent belongs to the authenticated user
            if ($request->filled('parent_id')) {
                $parent = ToDoTask::findOrFail($request->parent_id);
                if ($parent->user_id !== Auth::id()) {
                    throw new AuthorizationException('You are not authorized to assign this parent task.');
                }
            }

            $todos->update($request->only(['title', 'ordered', 'parent_id']));

            return $this->sendSuccess(
                ['task' => new ToDoTaskResource($todos->fresh()->load('subTask'))],
                'Task updated successfully.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->sendError(message: 'Parent task not found.', code: 404);
        } catch (AuthorizationException $e) {
            return $this->sendError(message: $e->getMessage(), code: 403);
        } catch (Throwable $e) {
            report($e);
            return $this->sendError(message: 'An unexpected error occurred.', code: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ToDoTask $todos)
    {
        try {
            if ($todos->user_id !== Auth::id()) {
                throw new AuthorizationException('You are not authorized to delete this task.');
            }

            // Delete all subtasks first, then the task itself
            $todos->subTask()->delete();
            $todos->delete();

            return $this->sendSuccess(message: 'Task deleted successfully.');
        } catch (AuthorizationException $e) {
            return $this->sendError(message: $e->getMessage(), code: 403);
        } catch (Throwable $e) {
            report($e);
            return $this->sendError(message: 'An unexpected error occurred.', code: 500);
        }
    }
}
