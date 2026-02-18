<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        dd($request->user()->tasks()->toSql());
        return $request->user()->tasks()->latest()->paginate($perPage);
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
        
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|max:255',
            'completed' => 'sometimes|required|boolean'
        ]);

        if(array_key_exists('completed', $validated)){
            $task->completed_at = $validated['completed'] ? now() : null;
            unset($validated['completed']);
        }

        $task->update($validated);
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
    
        $task->delete();

        return response()->json([
            'message' => 'Task deleted succesfully.'
        ]);
    }
}
