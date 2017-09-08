<?php

  Route::group(['prefix'=>'Machine'], function () {
    Route::get('/Monitor', 'MachineMonitorController@Index');
    Route::post('/Monitor/GetCur', 'MachineMonitorController@GetCur');
    Route::post('/Monitor/CreditIn', 'MachineMonitorController@CreditIn');
    Route::post('/Monitor/CreditOut', 'MachineMonitorController@CreditOut');
    Route::get('/', 'MachineController@Index');
    Route::get('/GetTableData', 'MachineController@GetTableData');
    Route::post('/Create', 'MachineController@Create');
    Route::get('/GetMachineByID', 'MachineController@GetMachineByID');
    Route::post('/Edit', 'MachineController@Update');
    Route::post('/Delete', 'MachineController@Delete');
    Route::post('/Probability', 'MachineProbabilityController@Index');
  });
