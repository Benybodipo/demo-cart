<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeyController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('keys')->group( function ()
{
    Route::get('/{id?}', [KeyController::class, 'index'])->name('get-key');
    Route::post('/verify', [KeyController::class, 'verify'])->name('verify-key');
    Route::post('/create', [KeyController::class, 'create'])->name('request-key');
    Route::put('/update/{key}', [KeyController::class, 'update'])->name('update-key');
    Route::delete('/delete/{key}', [KeyController::class, 'destroy'])->name('delete-key');
});
