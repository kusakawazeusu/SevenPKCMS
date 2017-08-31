<?php

  Route::group(['prefix'=>'Machine'], function () {
    Route::get('/Monitor', 'MachineMonitorController@Index');
    Route::post('/Monitor/GetCur', 'MachineMonitorController@GetCur');
    Route::post('/Monitor/CreditIn', 'MachineMonitorController@CreditIn');
    Route::post('/Monitor/CreditOut', 'MachineMonitorController@CreditOut');
    Route::get('/', 'MachineController@Index');
  });
