<?php

use App\Infrastructure\Controllers\CoinLoreGetCoinController;
use App\Infrastructure\Controllers\CoinLoreBuyCoinController;

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

Route::get('/coin/status/{coin_id}',CoinLoreGetCoinController::class);
Route::post('/coin/buy',CoinLoreBuyCoinController::class);
Route::post('/coin/sell',CoinLoreGetCoinController::class);
Route::post('/wallet/open',CoinLoreGetCoinController::class);
Route::get('/wallet/{wallet_id}',CoinLoreGetCoinController::class);
Route::get('/wallet/{wallet_id}/balance',CoinLoreGetCoinController::class);

