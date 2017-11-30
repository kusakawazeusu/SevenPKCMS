<?php

  Route::group(['prefix'=>'Machine'], function () {
    Route::get('/Monitor', 'MachineMonitorController@Index');
    Route::post('/Monitor/GetCur', 'MachineMonitorController@GetCur');
    Route::post('/Monitor/CreditIn', 'MachineMonitorController@CreditIn');
    Route::post('/Monitor/CreditOut', 'MachineMonitorController@CreditOut');
    Route::post('/Monitor/GetVerificationCode', 'MachineMonitorController@GetVerificationCode');
    Route::post('/Monitor/FastCreate', 'MachineController@FastCreate');
    Route::post('/Monitor/RemoveReserved', 'MachineMonitorController@RemoveReserved');
    Route::post('/Monitor/RefreshMachineStatus', 'MachineMonitorController@RefreshMachineStatus');
    Route::post('/Monitor/GetDepositCredit', 'MachineMonitorController@GetDepositCredit');
    Route::post('Monitor/CheckCreditIn', 'MachineMonitorController@CheckCreditIn');
    Route::get('/', 'MachineController@Index');
    Route::get('/GetTableData', 'MachineController@GetTableData');
    Route::post('/Create', 'MachineController@Create');
    Route::get('/GetMachineByID', 'MachineController@GetMachineByID');
    Route::get('/GetAgent', 'MachineController@GetAgent');
    Route::post('/CheckExistAgentID', 'MachineController@CheckExistAgentID');
    Route::post('/CheckDepulicatedMachineName', 'MachineController@CheckDepulicatedMachineName');
    Route::post('/Edit', 'MachineController@Update');
    Route::post('/Delete', 'MachineController@Delete');
    Route::get('/Probability', 'MachineProbabilityController@Index');
    Route::get('/Probability/GetTableData', 'MachineProbabilityController@GetTableData');
    Route::get('/Probability/GetMachineByID', 'MachineProbabilityController@GetMachineByID');
    Route::post('/Probability/Edit', 'MachineProbabilityController@Update');
    Route::get('/Probability/GetProbabilityAdj', 'MachineProbabilityController@GetProbabilityAdj');
    Route::get('/Probability/GetBaseProbability', 'MachineProbabilityController@GetBaseProbability');
    Route::post('/Probability/GetPaytable', 'MachineProbabilityController@GetPaytable');
    Route::get('/Meter', 'MachineMeterController@Index');
    Route::get('/Meter/GetTableData', 'MachineMeterController@GetTableData');
    Route::post('/Meter/Clean', 'MachineMeterController@Clean');
    Route::get('/Meter/GetTableDataByID', 'MachineMeterController@GetTableDataByID');
    Route::get('/Meter/{id}', 'MachineMeterController@GetMachineMeterByID');
    Route::get('/PublicSetting', 'MachinePublicSettingController@Index');
    Route::get('/PublicSetting/GetPublicSetting', 'MachinePublicSettingController@GetPublicSetting');
  });
