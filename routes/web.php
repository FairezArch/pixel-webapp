<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\CategoryProductController;

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

Route::get('/forgot', [AuthController::class, 'forgot'])->name('auth.forgot');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::get('/reset-password-info', [ForgotPasswordController::class, 'resetPasswordInfo'])->name('reset.password.info');

Route::get('/get-file-env', [AuthController::class, 'getENV'])->name('get.env.tc');
Route::get('/get-file-collect', [AuthController::class, 'getCollection'])->name('get.collect.tc');
Route::get('/get-file-text', [AuthController::class, 'getTextAPI'])->name('get.desc.api');

Route::prefix('/auth')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit');


    Route::middleware(['auth:web'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        //  change pwd, etc...
    });
});

//Protecting Routes
Route::middleware(['auth'])->group(function () {
    Route::group(['middlleware' => ['permission:dashboard']], function () {
        // Dashboard Route
        Route::name('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
            Route::post('/sale-chart', [DashboardController::class, 'saleChart'])->name('sale-chart');
            Route::post('/busy-chart', [DashboardController::class, 'busyChart'])->name('busy-chart');
            Route::post('/product-chart', [DashboardController::class, 'productChart'])->name('product-chart');
        });
    });

    Route::group(['middlleware' => ['permission:product_list|product_create|product_update|product_delete']], function () {
        // Product Route
        Route::resource('/product', ProductController::class);
        Route::resource('/color', ColorController::class);
    });

    Route::group(['middlleware' => ['permission:category_product_list|category_product_create|category_product_update|category_product_delete']], function () {
        // Category Route
        Route::resource('/category', CategoryProductController::class);
    });

    Route::group(['middlleware' => ['permission:sales_list|sales_create']], function () {
        // Sale Route
        Route::resource('/sale', SaleController::class);
        Route::resource('/job', JobController::class);
    });

    Route::group(['middlleware' => ['permission:employee_list|employee_create']], function () {
        // Employee Route
        Route::put('/user/{user}/reset-device', [UserController::class, 'reset'])->name('reset');
        Route::get('/user/store/{store}', [UserController::class, 'findByStore'])->name('findByStore');
        Route::resource('/user', UserController::class);
    });

    Route::group(['middlleware' => ['permission:attendance_list|attendance_create']], function () {
        // Attendance Route
        Route::resource('/attendance', AttendanceController::class);
    });

    Route::group(['middlleware' => ['permission:target_list|target_create|target_update|target_delete']], function () {
        // Target Route
        Route::resource('/target', TargetController::class);
    });

    Route::group(['middlleware' => ['permission:region_list|region_create|region_update|region_delete']], function () {
        // Region Route
        Route::resource('/region', RegionController::class);
    });

    Route::group(['middlleware' => ['permission:role_permission_list|role_permission_create|role_permission_update']], function () {
        // Role Permission Route
        Route::resource('/role-permission', RolePermissionController::class)->except('destroy');
    });

    Route::group(['middlleware' => ['permission:main_shop_list|main_shop_create|main_shop_update|main_shop_delete']], function () {
        // Main Store Route
        Route::resource('/main-store', ChannelController::class);
    });

    Route::group(['middlleware' => ['permission:branch_shop_list|branch_shop_create|branch_shop_update|branch_shop_delete']], function () {
        // Branch Store Route
        Route::resource('/branch-store', StoreController::class);
    });

    Route::name('report.')->group(function () {
        Route::group(['middlleware' => ['permission:sales_report_list']], function () {
            // Sale Report Route
            Route::get('/sale-report', [ReportController::class, 'sale'])->name('sale');
        });
        Route::group(['middlleware' => ['permission:buyer_report_list']], function () {
            // Customer Report Route
            Route::get('/customer-report', [ReportController::class, 'customer'])->name('customer');
        });

        Route::group(['middlleware' => ['permission:rush_hour_report_list']], function () {
            // Busy Time Report Route
            Route::get('/busy-report', [ReportController::class, 'busy'])->name('busy');
            Route::get('/busy-chart', [ReportController::class, 'busyChart'])->name('busy.chart');
        });

        Route::group(['middlleware' => ['permission:target_report_list']], function () {
            // Target Report Route
            Route::get('/target-report', [ReportController::class, 'target'])->name('target');
        });
    });
});
