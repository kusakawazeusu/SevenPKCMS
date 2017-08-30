<?php

Route::group(['prefix'=>'Player'], function () {
	Route::get('/', 'PlayerController@Index');
	Route::get('/{page}/{num}','PlayerController@GetPlayer');
	Route::post('/CreatePlayer', 'PlayerController@CreatePlayer');
	Route::post('/DeletePlayer', 'PlayerController@DeletePlayer');
});