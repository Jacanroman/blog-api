<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,

            //To show only on the detail page (show), not to listing
            'description' => $this->when(
                $request->routeIs('*.recipes.show'),
                $this->description
            ),

            'ingredients' => $this->when(
                $request->routeIs('*.recipes.show'),
                $this->ingredients
            ),
            'steps'       => $this->when(
                $request->routeIs('*.recipes.show'),
                $this->steps
            ),

            'prep_time' => $this->prep_time,
            'cook_time' => $this->cook_time,
            'total_time' => ($this->prep_time + $this->cook_time),
            'servings' => $this->servings,
            'difficulty' => $this->difficulty,

            'country' => $this->country,
            'region' => $this->region,

            // Publishing
            'status'      => $this->status,
            'published_at'=> $this->published_at?->diffForHumans(),
            'published_at_raw' => $this->published_at,

           

        ];
    }
}
