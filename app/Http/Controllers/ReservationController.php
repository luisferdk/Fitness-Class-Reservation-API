<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Reservation::with(['user', 'classSession.classType', 'classSession.instructor']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date')) {
            $query->whereDate('booked_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('booked_at', '<=', $request->end_date);
        }

        $reservations = $query->orderBy('booked_at', 'desc')->paginate($request->get('per_page', 15));

        return response()->json($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request): JsonResponse
    {
        $reservation = Reservation::create($request->validated());

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => $reservation->load(['user', 'classSession.classType', 'classSession.instructor'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): JsonResponse
    {
        return response()->json([
            'data' => $reservation->load(['user', 'classSession.classType', 'classSession.instructor'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation): JsonResponse
    {
        $reservation->update($request->validated());

        return response()->json([
            'message' => 'Reservation updated successfully',
            'data' => $reservation->load(['user', 'classSession.classType', 'classSession.instructor'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json([
            'message' => 'Reservation deleted successfully'
        ]);
    }

    /**
     * Cancel a reservation.
     */
    public function cancel(Request $request, Reservation $reservation): JsonResponse
    {
        $reservation->update([
            'status' => $request->input('reason', 'canceled_by_user'),
            'canceled_at' => now(),
        ]);

        return response()->json([
            'message' => 'Reservation canceled successfully',
            'data' => $reservation->load(['user', 'classSession.classType', 'classSession.instructor'])
        ]);
    }

    /**
     * Check in a user for a reservation.
     */
    public function checkIn(Reservation $reservation): JsonResponse
    {
        $reservation->update([
            'status' => 'attended',
            'checked_in_at' => now(),
        ]);

        return response()->json([
            'message' => 'User checked in successfully',
            'data' => $reservation->load(['user', 'classSession.classType', 'classSession.instructor'])
        ]);
    }
}
