<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query=Task::query();
        if($request->has('status')){
            $query->where('status',$request->get('status'));
        }


        return TaskResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        if(!Gate::allows('create')){
            return response()->json(['message' => 'Unauthorized'], 403);

        }
        $validated=$request->validated();
        $task=Auth::user()->tasks()->create(array_merge($validated,['status'=>false]));
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (!Gate::allows('update', $task)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated=$request->validated();
        $task->update($validated);
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (!Gate::allows('delete', $task)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $task->delete();
        return response()->json('deleted', 204);
    }
}
