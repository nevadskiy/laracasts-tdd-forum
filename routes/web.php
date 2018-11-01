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

Auth::routes(['verify' => true]);

Route::get('home', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'threads',
    'as' => 'threads.',
], function () {
    Route::post('/', 'ThreadsController@store')->name('store')->middleware('verified');
    Route::get('create', 'ThreadsController@create')->name('create');
    Route::get('{channel}/{thread}', 'ThreadsController@show')->name('show');
    Route::delete('{channel}/{thread}', 'ThreadsController@destroy')->name('destroy');
    Route::get('{channel?}', 'ThreadsController@index')->name('index');

    Route::group([
        'prefix' => '{channel}/{thread}/subscriptions',
        'as' => 'subscriptions.',
        'middleware' => 'auth'
    ], function () {
        Route::post('/', 'ThreadSubscriptionsController@store')->name('store');
        Route::delete('/', 'ThreadSubscriptionsController@destroy')->name('destroy');
    });
});

Route::group([
    'as' => 'replies.'
], function () {
    Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index')->name('index');
    Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store')->name('store');
    Route::delete('replies/{reply}', 'RepliesController@destroy')->name('destroy');
    Route::put('replies/{reply}', 'RepliesController@update')->name('update');

    Route::post('replies/{reply}/favorites', 'FavoritesController@store')->name('favorite');
    Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy')->name('unfavorite');
});

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profiles.show');

Route::group([
    'prefix' => '/profiles/{user}/notifications',
    'as' => 'notifications.'
], function () {
    Route::get('/', 'UserNotificationsController@index')->name('index');
    Route::delete('/{notification}', 'UserNotificationsController@destroy')->name('destroy');
});

Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
