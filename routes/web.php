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

Route::get('/', 'ResultPageController@index');

Route::get('/table','ResultPageController@table');
Route::get('/currentWeek','ResultPageController@week');
Route::get('/play','ResultPageController@play');
