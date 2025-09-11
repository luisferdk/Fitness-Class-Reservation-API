<?php

namespace App\Http\Controllers;

use App\Models\ClassType;
use Illuminate\Http\Request;
use App\Http\Requests\ClassType\StoreClassTypeRequest;
use App\Http\Requests\ClassType\UpdateClassTypeRequest;

class ClassTypeController extends Controller
{
    // Public or auth-visible list
    public function index(Request $request)
    {
        $query = ClassType::query();

        // Optional filters
        if ($request->has('active')) {
            $query->where('is_active', filter_var($request->boolean('active'), FILTER_VALIDATE_BOOL));
        }

        return $query->orderBy('name')->paginate(15);
    }

    public function show(ClassType $classType)
    {
        return $classType;
    }

    // Admin-only
    public function store(StoreClassTypeRequest $request)
    {
        $data = $request->validated();
        $data['min_attendees'] = $data['min_attendees'] ?? 2;
        $data['is_active'] = $data['is_active'] ?? true;

        $ct = ClassType::create($data);

        return response()->json($ct, 201);
    }

    public function update(UpdateClassTypeRequest $request, ClassType $classType)
    {
        $classType->update($request->validated());
        return response()->json($classType);
    }

    public function destroy(ClassType $classType)
    {
        $classType->delete();
        return response()->json(['message' => 'Deleted']);
    }
}