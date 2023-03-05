<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        // change pwd, etc...
    });
});

//Protecting Routes
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/{profile}/update', [UserController::class, 'profile_update']);

    Route::apiResource('/attendance', AttendanceController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/sale', SaleController::class);
    Route::apiResource('/target', TargetController::class);
    Route::apiResource('/job', JobController::class);

});
