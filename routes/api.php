<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RestaurantAddressController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'restaurants'], function () {
        Route::get('/', [RestaurantController::class, 'index']);
        Route::get('/{id}', [RestaurantController::class, 'show']);
        Route::post('/', [RestaurantController::class, 'create']);
        Route::post('/{id}', [RestaurantController::class, 'update']);
        Route::delete('/{id}', [RestaurantController::class, 'destroy']);   
    });

    Route::group(['prefix' => 'restaurant/addresses'], function () {
        Route::get('/', [RestaurantAddressController::class, 'index']);
        Route::get('/{id}', [RestaurantAddressController::class, 'show']);
        Route::post('/', [RestaurantAddressController::class, 'create']);
        Route::put('/{id}', [RestaurantAddressController::class, 'update']);
        Route::delete('/{id}', [RestaurantAddressController::class, 'destroy']);
    });

    Route::group(['prefix' => 'restaurant/contacts'], function () {
        Route::get('/', [ContactController::class, 'index']);
        // Route::get('{id}/', [ContactController::class, 'show']);
        Route::get('by_user', [ContactController::class, 'getContactByUser']);
        Route::post('/', [ContactController::class, 'create']);
        Route::put('{id}', [ContactController::class, 'update']);
        Route::delete('{id}', [ContactController::class, 'destroy']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'create']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'create']);
        Route::post('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::group(['prefix' => 'addresses'], function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::get('/{id}', [AddressController::class, 'show']);
        Route::post('/', [AddressController::class, 'create']);
        Route::put('/{id}', [AddressController::class, 'update']);
        Route::delete('/{id}', [AddressController::class, 'destroy']);
    });

    Route::group(['prefix' => 'order_statuses'], function () {
        Route::get('/', [OrderStatusController::class, 'index']);
        Route::get('{id}/', [OrderStatusController::class, 'show']);
        Route::post('/', [OrderStatusController::class, 'create']);
        Route::put('{id}', [OrderStatusController::class, 'update']);
        Route::delete('{id}', [OrderStatusController::class, 'destroy']);
    });

    Route::group(['prefix' => 'payment_methods'], function () {
        Route::get('/', [PaymentMethodController::class, 'index']);
        Route::get('{id}/', [PaymentMethodController::class, 'show']);
        Route::post('/', [PaymentMethodController::class, 'create']);
        Route::put('{id}', [PaymentMethodController::class, 'update']);
        Route::delete('{id}', [PaymentMethodController::class, 'destroy']);
    });

    Route::get('user_data', [UserController::class, 'getUserData']);
});
Route::group(['prefix' => 'notifications'], function () {
    Route::post('send-email-contact', [SendMailController::class, 'sendEmailContact']);
});

Route::get('merchant/{id}', [RestaurantController::class, 'getMerchantData']);

Route::post('register', [AuthController::class, 'register']);

Route::post('check_email', [AuthController::class, 'checkEmailExists']);
Route::post('login', [AuthController::class, 'login']);
        