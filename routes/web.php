<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminRatingController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPropertyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes Group
Route::middleware(['auth', 'verified', 'can:manage-admin-resources'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class); // CRUD Categories
    Route::resource('properties', AdminPropertyController::class); // CRUD Properties
    Route::post('properties/{property}/images', [AdminPropertyController::class, 'addImages'])->name('properties.add_images');
    Route::delete('property-images/{propertyImage}', [AdminPropertyController::class, 'deleteImage'])->name('properties.delete_image');
    Route::resource('users', AdminUserController::class); 
    Route::resource('ratings', AdminRatingController::class)->except(['create', 'store']); 
    Route::post('properties/{property}/approve', [AdminPropertyController::class, 'approve'])->name('properties.approve');
    Route::post('properties/{property}/reject', [AdminPropertyController::class, 'reject'])->name('properties.reject');

});


require __DIR__.'/auth.php';
