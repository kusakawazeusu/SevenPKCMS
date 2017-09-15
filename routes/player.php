<?php

Route::group(['prefix'=>'Player'], function () {
	Route::get('/', 'PlayerController@Index');
	Route::get('/{page}/{num}','PlayerController@GetPlayer');
	Route::post('/CreatePlayer', 'PlayerController@CreatePlayer');
	Route::post('/DeletePlayer', 'PlayerController@DeletePlayer');
	Route::get('/PlayerData','PlayerController@GetPlayerData');
	Route::post('/UpdatePlayer','PlayerController@UpdatePlayer');
	Route::post('/CheckPhoto','PlayerController@CheckPhoto');
	Route::post('/CreatePhoto','PlayerController@CreatePhoto');
	Route::post('/Deposit','PlayerController@Deposit');
	Route::post('/CheckPassword','PlayerController@CheckPassword');
	Route::post('/UpdatePassword','PlayerController@UpdatePassword');
	Route::post('/CheckDepulicatedAccount','PlayerController@CheckDepulicatedAccount');
});

Route::group(['prefix'=>'PlayerLog'], function () {
	Route::get('/', 'PlayerLogController@Index');
	Route::get('/{page}/{num}','PlayerLogController@GetPlayerLog');
	Route::get('/{ID}','PlayerLogController@GetPlayerLogByID');
	Route::get('/PlayerLogData/{page}/{num}','PlayerLogController@GetPlayerLogDataByID');

});