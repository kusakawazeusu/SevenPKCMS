<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('operator/get', 'Operator@getOperators')->name('GetOperators');
Route::post('operator/post','Operator@createOperator')->name('CreateOperator');
Route::post('operator/checkDepulicatedAccount','Operator@checkDepulicatedAccount')->name('CheckDepulicatedAccount');
Route::delete('operator/delete','Operator@deleteOperator')->name('DeleteOperator');