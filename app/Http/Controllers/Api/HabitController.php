<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitLog;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Habit::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'sometimes|max:255',
            'frecuency' => 'required|sometimes'
        ]);

        $habit = $request->user()->habits()->create($validated);
        $habitLog = $habit->logs()->create();
        
        return response()->json($habit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        return response()->json($habit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $habit = $request->user()->habits()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|max:255',
            'description' => 'sometimes|required|max:255',
            'frecuency' => 'sometimes|required'
        ]);

        $habit->update($validated);
        return response()->json($habit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $habit = $request->user()->habits()->findOrFail($id);
        
        $habit->delete();
        return response()->json(null, 204);
    }

    public function complete(Request $request, string $id)
    {
        $habit = $request->user()->habits()->findOrFail($id);

        // Prevent duplicate log for same day
        $today = now()->toDateString();
        $existingLog = $habit->logs()->whereDate('completed_on', $today)->first();

        if($existingLog) {
            return response()->json(['message' => 'Habit already completed today.'], 400);
        }

        $log = $habit->logs()->create([
            'habit_id' => $habit->id,
            'completed_on' => $today
        ]);

        return response()->json($log, 201);
    }
}
