<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HabitResource;
use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\HabitLog;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = min($request->get('per_page', 10), 50);

        $sortDirection = $request->get('sort', 'desc');
        $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';

        $query = $request->user()->habits()->orderBy('created_at', $sortDirection);

        $date = $request->get('date', now()->toDateString());

        if($request->has('completed')){
            $date = $request->get('date', now()->toDateString());

            if($request->completed === 'true'){
                $query->whereHas('logs', function ($q) use ($date){
                    $q->whereDate('created_at', $date);
                });
            } else {
                $query->whereDoesntHave('logs', function ($q) use ($date){
                    $q->whereDate('created_at', $date);
                });
            }
        }

        $habits = HabitResource::collection($query->paginate($perPage));

        return response()->json([
            'status' => 'success',
            'data' => $habits
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:1|max:255',
            'description' => 'sometimes|max:255',
            'frecuency' => 'required|numeric|min:1'
        ]);

        $habit = $request->user()->habits()->create($validated);
        $habitLog = $habit->logs()->create();
        
        return response()->json([
            'status' => 'success',
            'data' => $habit
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
            'data' => $habit
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $habit = $request->user()->habits()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'sometimes|max:255',
            'frecuency' => 'required|numeric|min:1'
        ]);

        $habit->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $habit
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $habit = $request->user()->habits()->findOrFail($id);
        
        $habit->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Habit deleted successfully.'
        ], 204);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Habit completed.'
        ], 201);
    }
}
