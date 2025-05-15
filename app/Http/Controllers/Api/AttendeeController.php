<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Attendee;
use App\Models\Event;
use App\Http\Resources\AttendeeResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        try {
            $paginator = $event->attendees()->latest()->paginate(1);
            $attendees = AttendeeResource::collection($paginator);

            return response()->json([
                'message' => 'Attendees retrieved successfully',
                'date' => $attendees,
                'meta' => [
                    'total' => $paginator->total(),
                    'count' => $paginator->count(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve attendees',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {

        try {
            $attendee = $event->attendees()->create([
                'user_id' => 1,
            ]);

            return response()->json([
                'message' => 'Attendee created successfully',
                'data' => new AttendeeResource($attendee)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve attendees',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        $event = new AttendeeResource($attendee);
        $event->load('user');

        try {
            return response()->json([
                'message' => 'Events retrieved successfully',
                'data' => $event
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $event, Attendee $attendee)
    {
        $attendee->delete();

        return response()->json([
            'message' => 'Events Deleted successfully'
        ], 204);
    }
}
