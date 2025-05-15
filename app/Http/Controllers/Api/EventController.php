<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $query = Event::query();
        $relations = ['user', 'attendees', 'attendees.user'];

        foreach ($relations as $relation) {
            if ($this->shouldIncludeRelation($relation)) {
                $query->with($relation);
            }
        }

        $paginator = $query->latest()->paginate(5);
        $event = EventResource::collection($paginator);

        try {
            return response()->json([
                'message' => 'Events retrieved successfully',
                'data' => $event,
                'meta' => [
                    'total' => $paginator->total(),
                    'count' => $paginator->count(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $event = Event::create([
                ...$request->validate([
                    'name' => 'required',
                    'description' => 'nullable|string',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_time',
                ]),
                'user_id' => 1
            ]);

            return response()->json([
                'message' => 'Event created successfully',
                'data' => $event
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        try {
            return response()->json([
                'message' => 'Event retrieved successfully',
                'data' => new EventResource($event)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $event->update([
                ...$request->validate([
                    'name' => 'required',
                    'description' => 'nullable|string',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_time',
                ]),
                'user_id' => 1
            ]);

            return response()->json([
                'message' => 'Event updated successfully',
                'data' => $event
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();

            return response()->json([
                'message' => 'Event deleted successfully'
            ], 204);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Event failed to delete',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function shouldIncludeRelation(string $relation)
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
}
