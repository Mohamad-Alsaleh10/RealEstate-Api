<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'path' => asset('storage/' . $this->path), // مسار الصورة كاملاً
            'is_primary' => (bool) $this->is_primary,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
