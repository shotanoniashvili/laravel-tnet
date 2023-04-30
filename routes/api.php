<?php

use App\Http\Controllers\CartController;
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

Route::group(['prefix' => 'cart'], static function() {
    Route::get('/', [CartController::class, 'getCart']);
    Route::post('/{product}', [CartController::class, 'add']);
    Route::patch('/{product}', [CartController::class, 'setQuantity']);
    Route::delete('/{product}', [CartController::class, 'remove']);
});
