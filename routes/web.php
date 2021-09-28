<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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

// Route::resource('api', App\Http\Controllers\KeyController::class);


Route::group(['middleware' => 'web'], function(){

    Route::get('products', [ProductController::class, 'home'])->name('home');
    Route::match(['get','post'],'/access-cart', [CartController::class, 'accessCart'])->name('access-cart');
    
    Route::get('products/{api_key}', [ProductController::class, 'index'])->middleware('api.key')->name('products');
    
    Route::group(['prefix' => 'cart', 'middleware' => 'api.key'], function () {
        Route::get('/{api_key?}', [CartController::class, 'home'])->name('cart');
        Route::post('/{api_key}/add-item/{product_id}', [CartController::class, 'addItem'])->name('add-item');
        Route::post('/save-cart-to-db/{api_key}', [CartController::class, 'saveCartToDb'])->name('save-cart-to-db');
        Route::post('/{api_key}/update-item/{product_id}', [CartController::class, 'updateItem'])->name('update-item');
        Route::post('/{api_key}/update-cart-info/', [CartController::class, 'update'])->name('update-cart-info');
        Route::get('/{api_key}/delete-cart', [CartController::class, 'delete'])->name('delete-cart');
        Route::post('/{api_key}/delete-item/{product_id}', [CartController::class, 'deleteItem'])->name('delete-item');
        Route::get('/{api_key}/my-cart', [CartController::class, 'profile'])->name('profile');
    });
    
    Route::match(['get', 'post'],'request-api-key', [CartController::class, 'requestApiKey'])->name('request-api-key');
});


