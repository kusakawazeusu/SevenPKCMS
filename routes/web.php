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

include 'Player.php';
include 'MachineRoute.php';

Route::get('/', 'MachineMonitorController@Index');

// Operator

Route::get('/operator','Operator@ShowOperator');

Route::get('/login','Login@ShowLoginForm')->name('login');
Route::post('/login','Login@LoginAttempt');
Route::get('/logout','Login@Logout');
