<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ModeratorController;

// ✅ جلب بيانات المستخدم المصادق عليه
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ✅ مسارات المستخدمين (تسجيل، تسجيل دخول، تسجيل خروج)

Route::controller(ApiAuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    


});
Route::controller(UserController::class)->group(function(){
Route::get('index', 'index');




});




// // // ✅ مسارات خاصة بالأدمن فقط
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// // // ✅ مسارات خاصة بالمودريتور فقط
Route::middleware(['auth:sanctum', 'moderator'])->group(function () {
    Route::get('/moderator/dashboard', [ModeratorController::class, 'dashboard']);
});

// Route::middleware(['auth:sanctum', 'admin'])->get('/admin/dashboard', [AdminController::class, 'dashboard']);




