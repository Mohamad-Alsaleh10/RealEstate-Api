<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Resources\PropertyResource;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $favorites = $user->favorites()->with(['category', 'images'])->paginate(10);
        return PropertyResource::collection($favorites);
    }

    public function store(Request $request, Property $property)
    {
        $user = $request->user();

        if ($user->favorites()->where('property_id', $property->id)->exists()) {
            return response()->json(['message' => 'Property already in favorites'], 409); // Conflict
        }

        $user->favorites()->attach($property->id);

        return response()->json(['message' => 'Property added to favorites successfully']);
    }

    public function destroy(Request $request, Property $property)
    {
        $user = $request->user();

        if (!$user->favorites()->where('property_id', $property->id)->exists()) {
            return response()->json(['message' => 'Property not found in favorites'], 404);
        }

        $user->favorites()->detach($property->id);

        return response()->json(['message' => 'Property removed from favorites successfully']);
    }
}
