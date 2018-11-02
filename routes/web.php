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



Route::get('/', 'Xiaoshuo\IndexController@index')->name('top');

Route::domain('im-bravo.com')->group(function () {
    Auth::routes();
});

Route::domain('im-bravo.com')->group(function () {
    require 'FrontEnd.php';
});

