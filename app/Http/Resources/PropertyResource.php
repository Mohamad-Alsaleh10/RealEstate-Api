<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'category' => new CategoryResource($this->whenLoaded('category')),
        'title' => $this->title,
        'description' => $this->description,
        'price' => $this->price,
        'currency' => $this->currency,
        'location' => $this->location,
        'type' => $this->type,
        'status' => $this->status,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
        'images' => PropertyImageResource::collection($this->whenLoaded('images')),
        'average_rating' => $this->average_rating ? (float)$this->average_rating : 0.0, // تأكد أنها float
        'ratings_count' => $this->ratings_count,
        'created_at' => $this->created_at->format('Y-m-d H:i:s'),
    ];
}

}
