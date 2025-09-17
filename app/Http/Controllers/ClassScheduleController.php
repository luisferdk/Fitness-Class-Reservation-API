<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassSchedule\StoreClassScheduleRequest;
use App\Http\Requests\ClassSchedule\UpdateClassScheduleRequest;
use App\Models\ClassSchedule;
use App\Models\ClassType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ClassSchedule::with(['classType', 'instructor']);

        if ($request->has('class_type_id')) {
            $query->where('class_type_id', $request->class_type_id);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $schedules = $query->paginate($request->get('per_page', 15));

        return response()->json($schedules);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassScheduleRequest $request): JsonResponse
    {
        $schedule = ClassSchedule::create($request->validated());

        return response()->json([
            'message' => 'Class schedule created successfully',
            'data' => $schedule->load(['classType', 'instructor'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSchedule $classSchedule): JsonResponse
    {
        return response()->json([
            'data' => $classSchedule->load(['classType', 'instructor', 'classSessions'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassScheduleRequest $request, ClassSchedule $classSchedule): JsonResponse
    {
        $classSchedule->update($request->validated());

        return response()->json([
            'message' => 'Class schedule updated successfully',
            'data' => $classSchedule->load(['classType', 'instructor'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSchedule $classSchedule): JsonResponse
    {
        $classSchedule->delete();

        return response()->json([
            'message' => 'Class schedule deleted successfully'
        ]);
    }
}
