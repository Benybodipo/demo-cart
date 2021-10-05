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

    Route::get('/', [ProductController::class, 'home'])->name('home');
    Route::match(['get','post'],'/access-cart', [CartController::class, 'accessCart'])->name('access-cart');
    Route::get('/exit-cart', [CartController::class, 'exitCart'])->name('exit-cart');
    
    Route::get('products/{api_key}', [ProductController::class, 'index'])->middleware('api.key')->name('products');
    
    Route::group(['prefix' => 'cart', 'middleware' => 'api.key'], function () {

        Route::get('/', [CartController::class, 'home'])->name('cart');
        Route::post('/add-item/{product_id}', [CartController::class, 'addItem'])->name('add-item');
        Route::post('/save-cart-to-db', [CartController::class, 'saveCartToDb'])->name('save-cart-to-db');
        Route::post('update-item/{product_id}', [CartController::class, 'updateItem'])->name('update-item');
        Route::post('/update-cart-info', [CartController::class, 'update'])->name('update-cart-info');
        Route::get('/delete-cart', [CartController::class, 'delete'])->name('delete-cart');
        Route::post('/delete-item/{product_id}', [CartController::class, 'deleteItem'])->name('delete-item');
        Route::get('/my-cart', [CartController::class, 'profile'])->name('profile');

    });
    
    Route::match(['get', 'post'],'request-api-key', [CartController::class, 'requestApiKey'])->name('request-api-key');
});


