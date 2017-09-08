<?php

Route::group(['prefix'=>'CardType'], function () {
	Route::get('/', 'CardBuffController@Index');

	Route::get('/CardTypeData','CardTypeController@CardTypeData');
	/*Route::post('/CreatePlayer', 'PlayerController@CreatePlayer');
	Route::post('/DeletePlayer', 'PlayerController@DeletePlayer');
	Route::get('/PlayerData','PlayerController@GetPlayerData');
	Route::post('/UpdatePlayer','PlayerController@UpdatePlayer');
	Route::post('/CheckPhoto','PlayerController@CheckPhoto');
	Route::post('/CreatePhoto','PlayerController@CreatePhoto');
	Route::post('/Deposit','PlayerController@Deposit');*/

});