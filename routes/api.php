<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SousCategoryController;
use App\Http\Middleware\isSuperAdminMiddleware;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);
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
Route::apiResource('product', ProductController::class)->middleware('auth:api');


Route::get('loginin', function (){
return response()->json([
    'error' => 'Unauthenticated',
], 404);
})->name('login');
