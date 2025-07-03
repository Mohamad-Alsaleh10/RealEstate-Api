<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
public function index(Request $request)
{
    $query = Property::query()->with(['category', 'images', 'user']);



    // الفلترة حسب النوع (للبيع/للإيجار)
    if ($request->has('type')) {
        $query->where('type', $request->input('type'));
    }

    // الفلترة حسب الفئة
    if ($request->has('category_id')) {
        $query->where('category_id', $request->input('category_id'));
    }

    // الفلترة حسب الموقع
    if ($request->has('location')) {
        $query->where('location', 'like', '%' . $request->input('location') . '%');
    }

    // البحث العام
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%')
              ->orWhere('location', 'like', '%' . $search . '%');
        });
    }

    // معالجة عرض العقارات حسب صلاحيات المستخدم

    // ترتيب النتائج حسب الأحدث (اختياري)
    $query->latest();

    // التقسيم إلى صفحات مع عدد العناصر المطلوب
    $perPage = $request->input('per_page', 10);
    $properties = $query->paginate($perPage);

    return PropertyResource::collection($properties);
}

    public function show(Property $property)
    {

        $property->load(['category', 'images']);
        return new PropertyResource($property);
    }


public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'currency' => 'required|string|max:3',
        'location' => 'required|string',
        'type' => 'required|in:for_sale,for_rent',
        'latitude' => 'nullable|string',
        'longitude' => 'nullable|string',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096'
    ]);

    // تحديد حالة العقار تلقائياً بناءً على صلاحية المستخدم
    $status = auth()->user()->isAdmin() ? 'approved' : 'pending';

    $property = Property::create([
        'user_id' => auth()->id(),
        'category_id' => $request->category_id,
        'title' => $request->title,
        'description' => $request->description,
        'price' => $request->price,
        'currency' => $request->currency,
        'location' => $request->location,
        'type' => $request->type,
        'status' => $status,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude
    ]);

    // معالجة رفع الصور إذا وجدت
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('property_images', 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'path' => $path,
                'is_primary' => ($index === 0) // أول صورة تكون رئيسية
            ]);
        }
    }

    return response()->json([
        'message' => 'Property created successfully',
        'property' => new PropertyResource($property->load(['category', 'images', 'user'])),
        'status_auto_set' => $status // إرجاع الحالة التي تم تعيينها تلقائياً
    ], 201);
}
}