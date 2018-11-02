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
//Auth::routes();

Route::match(['get'], '/', 'Xiaoshuo\IndexController@index')->name('top');
Route::match(['get'], '/search', 'Xiaoshuo\SearchController@search')->name('query');
Route::match(['get'], '/book/{bookid}.html', 'Xiaoshuo\BookController@index')->name('book');
Route::match(['get'], '/chapter/{bookid}.html', 'Xiaoshuo\ChapterController@index')->name('chapter');
Route::match(['get'], '/chapter/{bookid}/{chapterid}.html', 'Xiaoshuo\ContentController@index')->name('content');
