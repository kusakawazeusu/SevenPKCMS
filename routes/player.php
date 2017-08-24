<?php

Route::group(['prefix'=>'Player'], function () {
	Route::get('/', 'PlayerController@Index');
});
