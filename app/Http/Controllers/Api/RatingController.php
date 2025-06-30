<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Resources\RatingResource;
use Illuminate\Validation\Rule;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']); // يتطلب مصادقة للإضافة/التعديل/الحذف
    }

    // عرض جميع تقييمات عقار معين
    public function index(Property $property)
    {
        $ratings = $property->ratings()->with('user')->paginate(10);
        return RatingResource::collection($ratings);
    }

    // عرض تقييم معين (إذا كان له داعي)
    public function show(Rating $rating)
    {
        return new RatingResource($rating->load('user'));
    }

    // إضافة تقييم جديد
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // التأكد من أن المستخدم لم يقم بتقييم هذا العقار من قبل
        if ($property->ratings()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'You have already rated this property.'], 409); // Conflict
        }

        $rating = $property->ratings()->create([
            'user_id' => $request->user()->id,
            'stars' => $request->stars,
            'comment' => $request->comment,
        ]);

        // تحديث متوسط التقييم وعدد التقييمات للعقار
        $property->update([
            'average_rating' => $property->ratings()->avg('stars'),
            'ratings_count' => $property->ratings()->count(),
        ]);

        return response()->json([
            'message' => 'Rating added successfully.',
            'rating' => new RatingResource($rating->load('user')),
        ], 201);
    }

    // تحديث تقييم موجود
    public function update(Request $request, Rating $rating)
    {
        // التأكد من أن المستخدم هو صاحب التقييم
        if ($request->user()->id !== $rating->user_id) {
            return response()->json(['message' => 'Unauthorized to update this rating.'], 403);
        }

        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $rating->update([
            'stars' => $request->stars,
            'comment' => $request->comment,
        ]);

        // تحديث متوسط التقييم للعقار بعد التعديل
        $property = $rating->property;
        $property->update([
            'average_rating' => $property->ratings()->avg('stars'),
        ]);

        return response()->json([
            'message' => 'Rating updated successfully.',
            'rating' => new RatingResource($rating->load('user')),
        ]);
    }

    // حذف تقييم
    public function destroy(Request $request, Rating $rating)
    {
        // التأكد من أن المستخدم هو صاحب التقييم أو أنه مسؤول
        if ($request->user()->id !== $rating->user_id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized to delete this rating.'], 403);
        }

        $property = $rating->property; // نحفظ العقار قبل الحذف لتحديث متوسط التقييم
        $rating->delete();

        // تحديث متوسط التقييم وعدد التقييمات للعقار بعد الحذف
        $property->update([
            'average_rating' => $property->ratings()->count() > 0 ? $property->ratings()->avg('stars') : 0.0,
            'ratings_count' => $property->ratings()->count(),
        ]);

        return response()->json(['message' => 'Rating deleted successfully.']);
    }
}
