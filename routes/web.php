<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function(){

    include 'Player.php';
    include 'MachineRoute.php';
    include 'CardBuff.php';
    include 'CardType.php';

    Route::get('/', 'MachineMonitorController@Index');
    Route::get('/operator','Operator@ShowOperator');
    Route::get('/introducer','Introducer@ShowIntroducer');
    Route::get('/agent','Agent@ShowAgent');

    Route::get('/knockoff','Shift@KnockOffCount');
    Route::post('/knockoff','Shift@KnockOff');
    Route::get('/shift','Shift@ShiftCount');
    Route::post('/shift','Shift@Shift');

    Route::get('/report','Report@SingleSessionReport');
    Route::get('/dayreport','Report@DaySessionReport');
    Route::get('/syncdayreport','Report@SyncDaySessionReport');
    Route::get('/regeneratedayreport','Report@RegenerateDaySessionReport');

    Route::get('/monthreport','Report@MonthReport');
    Route::get('/regeneratemonthreport','Report@RegenerateMonthReport');
    Route::get('/syncmonthreport','Report@SyncMonthReport');
    Route::get('/playerbetreport','Report@PlayerBetReport');

    Route::get('/BroadcastMsg','BroadcastMsg@MsgSetting');
    Route::post('/BroadcastMsg','BroadcastMsg@SetMsg')->name('SetMsg');
});
Route::get('/login','Login@ShowLoginForm')->name('login');
Route::post('/login','Login@LoginAttempt');
Route::get('/logout','Login@Logout');
