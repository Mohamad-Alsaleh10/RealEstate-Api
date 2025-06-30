<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')), // معلومات المستخدم الذي قام بالتقييم
            'property_id' => $this->property_id,
            'stars' => $this->stars,
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
