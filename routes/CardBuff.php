<?php

Route::group(['prefix'=>'CardBuff'], function () {
	Route::get('/', 'CardBuffController@Index');

	Route::get('/{page}/{num}','CardBuffController@GetCardBuff');//抓取批量的資料
	Route::post('/CreateCardBuff', 'CardBuffController@CreateCardBuff');
	Route::get('/CardBuffData','CardBuffController@GetCardBuffData');//根據ID抓資料
	Route::post('/UpdateCardBuff','CardBuffController@UpdateCardBuff');
	Route::get('/CheckStartTime','CardBuffController@CheckStartTime');
	Route::get('/CheckEndTime','CardBuffController@CheckEndTime');

	/*Route::post('/DeletePlayer', 'PlayerController@DeletePlayer');
	Route::post('/CheckPhoto','PlayerController@CheckPhoto');
	Route::post('/CreatePhoto','PlayerController@CreatePhoto');
	Route::post('/Deposit','PlayerController@Deposit');*/

});