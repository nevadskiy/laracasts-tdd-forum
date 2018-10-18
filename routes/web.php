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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('home', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'threads',
    'as' => 'threads.',
], function () {
    Route::post('/', 'ThreadsController@store')->name('store');
    Route::get('create', 'ThreadsController@create')->name('create');
    Route::get('{channel}/{thread}', 'ThreadsController@show')->name('show');
    Route::get('{channel?}', 'ThreadsController@index')->name('index');
});

Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')->name('replies.store');
