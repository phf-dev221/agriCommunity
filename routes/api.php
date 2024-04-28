<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\isSuperAdminMiddleware;

use App\Http\Controllers\SousCategoryController;
use App\Http\Controllers\ForgetPasswordController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login'])->name('login');
//Route protected 
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('refreshToken', [ApiController::class, 'refreshToken']);
    Route::put('update-profile/{user}', [ApiController::class, 'updateProfile']);
});

Route::apiResource('role', RoleController::class)->middleware('superAdmin');
Route::apiResource('category', CategoryController::class)->middleware('superAdmin');
Route::apiResource('sous-category', SousCategoryController::class)->middleware('superAdmin');

Route::middleware('auth:api')->group(function () {
    // Routes pour la ressource Product
    Route::get('product', [ProductController::class, 'index'])->name('product.index');
    Route::get('product/{product}', [ProductController::class, 'show'])->name('product.show');
    Route::post('product', [ProductController::class, 'store'])->name('product.store');
    Route::post('product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
});

/*reset password*/
// Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::get('reset-password/{token}', [ForgetPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgetPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::post('forget-password', [ForgetPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('/password/reset/success', [ForgetPasswordController::class,'showResetPasswordSuccess'])->name('password.reset.success');

// Route::get('loginin', function (){
// return response()->json([
//     'error' => 'Unauthenticated',
// ], 404);
// })->name('login');
