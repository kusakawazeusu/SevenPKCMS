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

// Operator API
Route::get('operator/get', 'Operator@getOperators')->name('GetOperators');
Route::get('operator/getbyid','Operator@getOperatorData')->name('GetOperatorById');
Route::post('operator/post','Operator@createOperator')->name('CreateOperator');
Route::post('operator/checkDepulicatedAccount','Operator@checkDepulicatedAccount')->name('CheckDepulicatedAccount');
Route::post('operator/update','Operator@updateOperator')->name('UpdateOperator');
Route::delete('operator/delete','Operator@deleteOperator')->name('DeleteOperator');

// Introducer API
Route::get('introducer/get', 'Introducer@getIntroducer')->name('GetIntroducers');
Route::get('introducer/getbyid','Introducer@getIntroducerData')->name('GetIntroducerById');
Route::post('introducer/post','Introducer@createIntroducer')->name('CreateIntroducer');
Route::post('introducer/update','Introducer@updateIntroducer')->name('UpdateIntroducer');
Route::delete('introducer/delete','Introducer@deleteIntroducer')->name('DeleteIntroducer');