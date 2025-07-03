<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class AdminRatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-admin-resources');
    }

    public function index(Request $request)
    {
        $query = Rating::with('user', 'property');

        // Filtering
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('stars')) {
            $query->where('stars', $request->stars);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('property', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })->orWhere('comment', 'like', '%' . $request->search . '%');
        }

        $ratings = $query->latest()->paginate(10);
        return view('admin.ratings.index', compact('ratings'));
    }

    public function show(Rating $rating)
    {
        $rating->load('user', 'property');
        return view('admin.ratings.show', compact('rating'));
    }

    public function edit(Rating $rating)
    {
        $rating->load('user', 'property');
        return view('admin.ratings.edit', compact('rating'));
    }

    public function update(Request $request, Rating $rating)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating->update([
            'stars' => $request->stars,
            'comment' => $request->comment,
        ]);

        return redirect()->route('admin.ratings.index')->with('success', 'Rating updated successfully.');
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return redirect()->route('admin.ratings.index')->with('success', 'Rating deleted successfully.');
    }
}