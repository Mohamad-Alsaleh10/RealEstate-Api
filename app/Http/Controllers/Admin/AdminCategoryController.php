<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image; // لضغط الصور إن أردت
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-admin-resources'); // تطبيق صلاحية المسؤول
    }

    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // للصور
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('categories_icons', 'public');
            // يمكنك استخدام Intervention Image لضغط الصورة هنا:
            // $image = Image::make(public_path('storage/' . $iconPath))->fit(100, 100); // مثال
            // $image->save(public_path('storage/' . $iconPath));
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $iconPath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $iconPath = $category->icon;
        if ($request->hasFile('icon')) {
            // حذف الأيقونة القديمة إذا وجدت
            if ($iconPath && Storage::disk('public')->exists($iconPath)) {
                Storage::disk('public')->delete($iconPath);
            }
            $iconPath = $request->file('icon')->store('categories_icons', 'public');
        } elseif ($request->boolean('delete_icon')) { // خيار لحذف الأيقونة بدون استبدال
            if ($iconPath && Storage::disk('public')->exists($iconPath)) {
                Storage::disk('public')->delete($iconPath);
            }
            $iconPath = null;
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $iconPath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // حذف الأيقونة من التخزين
        if ($category->icon && Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
