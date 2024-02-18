<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Middleware\checkRole;
use Illuminate\Http\Request;
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

Route::get('/test', function () {
  return 'service running';
});

Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('logout', [UserAuthController::class, 'logout'])
  ->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
  //all admin route
  Route::middleware(['restrictRole:1'])->prefix('admin/')->group(function () {
    Route::controller(ProductController::class)
      ->prefix('products')
      ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}/update', 'update');
        Route::delete('/{id}/delete', 'destroy');
      });
  });

  // all customer route
  Route::middleware(['restrictRole:2'])->prefix('customer/')->group(function () {
    Route::get('orders', [OrderController::class, 'curtomerOrder']);
  });

  Route::post('orders', [OrderController::class, 'store']);
  Route::get('orders', [OrderController::class, 'index']);
  Route::get('orders/{id}', [OrderController::class, 'show']);
});
