<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MpesaController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::get('/mpesa/password','MpesaController@lipaNaMpesaPassword');
Route::get('/mpesa/password', [MpesaController::class, 'lipaNaMpesaPassword']);

Route::post('/mpesa/new/token',[MpesaController::class, 'newAccessToken']);
Route::post('/mpesa/stk/push',[MpesaController::class, 'stkPush'])->name('lipa');
Route::post('/stk/push/callback/url',[MpesaController::class, 'MpesaRes']);
Route::post('/stk/push/callback/mpesares',[MpesaController::class, 'MpesaRes']);
