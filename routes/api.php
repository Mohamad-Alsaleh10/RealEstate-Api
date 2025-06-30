<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Admin\AdminCategoryController; // سننشئ هذا لاحقاً
use App\Http\Controllers\Admin\AdminPropertyController; // سننشئ هذا لاحقاً
use App\Http\Controllers\Api\RatingController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// مسارات المصادقة العامة
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// مسارات الفئات والعقارات العامة (بدون مصادقة)
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);
Route::get('properties', [PropertyController::class, 'index']);
Route::get('properties/{property}', [PropertyController::class, 'show']);


// المسارات التي تتطلب مصادقة (للمستخدمين العاديين والمسؤولين)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::put('user/profile', [AuthController::class, 'updateProfile']);

    Route::post('/properties', [PropertyController::class, 'store']);

    // مسارات المفضلة
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites/{property}', [FavoriteController::class, 'store']);
    Route::delete('favorites/{property}', [FavoriteController::class, 'destroy']);

    // مسارات التقييمات (تحتاج لمصادقة للإضافة/التعديل/الحذف)
    Route::post('properties/{property}/ratings', [RatingController::class, 'store']);
    Route::put('ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy']);

    // مسارات خاصة بالمسؤولين (سنضيف middleware التحقق من الدور لاحقاً)
    Route::middleware('can:manage-admin-resources')->prefix('admin')->group(function () {
        // إدارة الفئات
        Route::apiResource('categories', AdminCategoryController::class); // CRUD
        // إدارة العقارات
        Route::apiResource('properties', AdminPropertyController::class); // CRUD
        Route::post('properties/{property}/images', [AdminPropertyController::class, 'addImages']); // إضافة صور لعقار
        Route::delete('property-images/{propertyImage}', [AdminPropertyController::class, 'deleteImage']); // حذف صورة
    });
});

// مسارات عامة للتقييمات (يمكن لأي شخص رؤيتها)
Route::get('properties/{property}/ratings', [RatingController::class, 'index']); // عرض تقييمات عقار معين
Route::get('ratings/{rating}', [RatingController::class, 'show']); // عرض تقييم واحد

// مسار لإدارة التقييمات من قبل الأدمن (يمكن إضافته ضمن مسارات الأدمن)
Route::middleware(['auth:sanctum', 'can:manage-admin-resources'])->prefix('admin')->group(function () {
    // ... (إدارة الفئات والعقارات)

    // إدارة التقييمات من قبل الأدمن (يمكن للأدمن حذف أي تقييم)
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy']); // يستخدم نفس دالة destroy لكن يتحقق من صلاحيات الأدمن
});
