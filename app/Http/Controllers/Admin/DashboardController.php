<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $propertiesCount = Property::count();
        $categoriesCount = Category::count();
        $usersCount = User::where('role', 'user')->count();

        return view('admin.dashboard', compact('propertiesCount', 'categoriesCount', 'usersCount'));
    }
}
