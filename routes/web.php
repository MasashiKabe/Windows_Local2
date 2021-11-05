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

Route::group(['prefix' => 'admin'], function() {
    Route::get('news/create', 'Admin\NewsController@add')->middleware('auth');
});

Route::group(['prefix' => 'admin'], function() {
    Route::get('profile/create', 'Admin\ProfileController@add')->middleware('auth');
    Route::post('profile/create', 'Admin\ProfileController@create')->middleware('auth'); //PHP_Laravel14課題3
    Route::get('profile/edit', 'Admin\ProfileController@edit')->middleware('auth');
    Route::post('profile/edit', 'Admin\ProfileController@update')->middleware('auth'); //PHP_Laravel14課題6
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() { //PHP_Laravel14追記
    Route::get('news/create', 'Admin\NewsController@add');
    Route::post('news/create', 'Admin\NewsController@create');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
