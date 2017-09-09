<?php

Route::group(['prefix'=>'CardType'], function () {
	Route::get('/', 'CardTypeController@Index');

	Route::post('/CreateCardType','CardTypeController@CreateCardType');
	Route::get('/CardTypeData','CardTypeController@CardTypeData');
	Route::post('/UpdateCardType','CardTypeController@UpdateCardType');
	Route::get('/{page}/{num}','CardTypeController@GetCardType');//抓取批量的資料
	Route::get('/CardTypeDatas','CardTypeController@CardTypeDatas');

});