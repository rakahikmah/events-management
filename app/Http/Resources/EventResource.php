<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user' => new UserResource($this->user),
            'attendees' => AttendeeResource::collection($this->whenLoaded('attendees')),
        ];
    }
}
