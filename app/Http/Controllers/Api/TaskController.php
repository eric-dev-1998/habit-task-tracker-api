<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = min($request->get('per_page', 10), 50);

        $sortDirection = $request->get('sort', 'desc');
        $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';

        $query = $request->user()->tasks()->orderBy('created_at', $sortDirection);

        if($request->has('completed')){
            if($request->completed === 'true'){
                $query->whereNotNull('completed_at');
            } else {
                $query->whereNull('completed_at');
            }
        }

        if($request->has('search')){
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks = TaskResource::collection($query->paginate($perPage));

        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'sometimes|max:255'
        ]);

        $task = $request->user()->tasks()->create($validated);
        
        return response()->json([
            'status' => 'success',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        
        $task = $request->user()->tasks()->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'sometimes|max:255',
            'completed_at' => 'sometimes|required|boolean'
        ]);

        if(array_key_exists('completed_at', $validated)){
            $task->completed_at = $validated['completed_at'] ? now() : null;
            unset($validated['completed_at']);
        }

        $task->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
    
        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted succesfully.'
        ]);
    }
}
