<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassSession\StoreClassSessionRequest;
use App\Http\Requests\ClassSession\UpdateClassSessionRequest;
use App\Models\ClassSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ClassSession::with(['classType', 'instructor', 'reservations']);

        if ($request->has('class_type_id')) {
            $query->where('class_type_id', $request->class_type_id);
        }

        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date')) {
            $query->whereDate('start_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('start_at', '<=', $request->end_date);
        }

        $sessions = $query->orderBy('start_at')->paginate($request->get('per_page', 15));

        return response()->json($sessions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassSessionRequest $request): JsonResponse
    {
        $session = ClassSession::create($request->validated());

        return response()->json([
            'message' => 'Class session created successfully',
            'data' => $session->load(['classType', 'instructor'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassSession $classSession): JsonResponse
    {
        return response()->json([
            'data' => $classSession->load(['classType', 'instructor', 'reservations.user', 'generatedFromSchedule'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassSessionRequest $request, ClassSession $classSession): JsonResponse
    {
        $classSession->update($request->validated());

        return response()->json([
            'message' => 'Class session updated successfully',
            'data' => $classSession->load(['classType', 'instructor'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSession $classSession): JsonResponse
    {
        $classSession->delete();

        return response()->json([
            'message' => 'Class session deleted successfully'
        ]);
    }
}
