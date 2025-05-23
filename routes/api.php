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
Route::controller(ApiAuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});



//             ✅ مسارات المستخدمين 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('all', [UserController::class, 'all']);
    Route::get('getCategory/{id}', [UserController::class, 'getCategory']);
    //////////////////////////////////////////////////////////////////////////////////////////////
    Route::post('/cart/add/{id}', [UserController::class, 'addToCart']);
    Route::post('viewCart', [UserController::class, 'viewCart']);
    Route::post('updateQuantity', [UserController::class, 'updateQuantity']);
    Route::delete('removeFromCart/{id}', [UserController::class, 'removeFromCart']);
    Route::delete('clearCart', [UserController::class, 'clearCart']);




});






// // // ✅ مسارات خاصة بالأدمن فقط
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::post('admin/allUsers', [AdminController::class, 'allUsers']);
    Route::delete('admin/userdelete/{id}', [AdminController::class, 'userdelete']);
    Route::put('admin/updaterole/{id}', [AdminController::class, 'updaterole']);
    ////////////////////////////////////////////////////////////////////////////////////////////
    Route::post('admin/store', [AdminController::class, 'store']);
    Route::delete('admin/delete/{id}', [AdminController::class, 'delete']);
    Route::put('admin/update/{id}', [AdminController::class, 'update']);
    /////////////////////////////////////////////////////////////////////////////////
    Route::post('admin/createCategories', [AdminController::class, 'createCategories']);
    Route::post('admin/allCategories', [AdminController::class, 'allCategories']);
    Route::put('admin/updateCategories/{id}', [AdminController::class, 'updateCategories']);
    Route::delete('admin/Categoriesdelete/{id}', [AdminController::class, 'Categoriesdelete']);
});
// // // ✅ مسارات خاصة بالمودريتور فقط
Route::middleware(['auth:sanctum', 'moderator'])->group(function () {

});




// Route::middleware(['auth:sanctum', 'admin'])->get('/admin/dashboard', [AdminController::class, 'dashboard']);
