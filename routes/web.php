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
    Route::delete('{channel}/{thread}', 'ThreadsController@destroy')->name('destroy');
    Route::get('{channel?}', 'ThreadsController@index')->name('index');
});

Route::group([
    'as' => 'replies.'
], function () {
    Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')->name('store');
    Route::delete('replies/{reply}', 'RepliesController@destroy')->name('destroy');
    Route::post('replies/{reply}/favorites', 'FavoritesController@store')->name('favorite');
});

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profiles.show');
