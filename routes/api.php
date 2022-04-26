<?php

use App\Infrastructure\Controllers\CryptoDataController;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/coin/status/{coin_id}',CryptoDataController::class);
Route::get('/coin/buy',CryptoDataController::class);
Route::get('/coin/sell',CryptoDataController::class);
Route::get('/wallet/open',CryptoDataController::class);
Route::get('/wallet/{wallet_id}',CryptoDataController::class);
Route::get('/wallet/{wallet_id}/balance',CryptoDataController::class);

