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

});