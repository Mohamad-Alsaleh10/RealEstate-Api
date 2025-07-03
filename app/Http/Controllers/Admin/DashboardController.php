<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\User;
use App\Models\Rating; 

class DashboardController extends Controller
{
    public function index()
    {
        $propertiesCount = Property::count();
        $pendingPropertiesCount = Property::where('status', 'pending')->count(); // New count
        $categoriesCount = Category::count();
        $usersCount = User::where('role', 'user')->count();
        $adminUsersCount = User::where('role', 'admin')->count(); // Optional: count admins
        $ratingsCount = Rating::count(); // New count

        return view('admin.dashboard', compact(
            'propertiesCount',
            'pendingPropertiesCount',
            'categoriesCount',
            'usersCount',
            'adminUsersCount', // Pass to view
            'ratingsCount' // Pass to view
        ));
    }
}