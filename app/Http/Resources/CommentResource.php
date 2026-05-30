<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'status' => $this->status,
            //Show reistered user name or guest name
            'author_name' => $this->authorName(),

            //Guest info -- only show to admins
            "guest_name" => $this->when(
                $request->user()?->hasRole('admin'),
                $this->guest_name
            ),
            'guest_email' => $this->when(
                $request->user()?->hasRole('admin'),
                $this->guest_email
            ),

            //Nested replies -- only included if with('replies') was loaded
            'replies' => CommentResource::collection(
                $this->whenLoaded('replies')
            ),

            
        ];
    }
}
