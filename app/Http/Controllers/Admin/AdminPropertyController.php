<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Intervention\Image\Facades\Image; // إذا أردت استخدامها لضغط الصور

class AdminPropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-admin-resources');
    }

    public function index(Request $request)
    {
        $query = Property::with('category', 'images', 'user'); // Eager load user who added it

        // Filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            })->orWhereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }


        $properties = $query->latest()->paginate(10); // Paginate results
        $categories = Category::all(); // For filter dropdown

        return view('admin.properties.index', compact('properties', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.properties.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'type' => 'required|in:for_sale,for_rent',
            'status' => 'required|in:pending,approved,sold,rented',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096', // صور متعددة
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $property = Property::create([
            'user_id' => auth()->id(), // المسؤول الذي أضاف العقار
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'location' => $request->location,
            'type' => $request->type,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('property_images', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path' => $path,
                    'is_primary' => ($index === 0) ? true : false, // جعل أول صورة هي الرئيسية
                ]);
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property created successfully.');
    }

    public function show(Property $property)
    {
        $property->load(['category', 'images']);
        return view('admin.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $categories = Category::all();
        $property->load('images');
        return view('admin.properties.edit', compact('property', 'categories'));
    }

    public function update(Request $request, Property $property)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'location' => 'required|string|max:255',
            'type' => 'required|in:for_sale,for_rent',
            'status' => 'required|in:pending,approved,sold,rented',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $property->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'location' => $request->location,
            'type' => $request->type,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // إضافة صور جديدة
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('property_images', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // تحديث الصورة الرئيسية (إذا تم تحديدها)
        if ($request->has('primary_image_id')) {
            $property->images()->update(['is_primary' => false]); // إزالة الرئيسية الحالية
            PropertyImage::where('id', $request->primary_image_id)->update(['is_primary' => true]);
        }

        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        // حذف جميع صور العقار من التخزين
        foreach ($property->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        $property->delete();
        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
    }

    // دالة مخصصة لإضافة صور لعقار موجود
    public function addImages(Request $request, Property $property)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('property_images', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }
        return back()->with('success', 'Images added successfully.');
    }

    // دالة مخصصة لحذف صورة معينة لعقار
    public function deleteImage(PropertyImage $propertyImage)
    {
        if (Storage::disk('public')->exists($propertyImage->path)) {
            Storage::disk('public')->delete($propertyImage->path);
        }
        $propertyImage->delete();
        return back()->with('success', 'Image deleted successfully.');
    }


        public function approve(Property $property)
    {
        $property->update(['status' => 'approved']);
        return back()->with('success', 'Property approved successfully.');
    }
    public function reject(Property $property)
    {
        $property->update(['status' => 'rejected']); // Consider adding 'rejected' to your enum in migration
        return back()->with('success', 'Property rejected.');
    }
}
