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

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('profile/create', 'Admin\ProfileController@add');
    Route::post('profile/create', 'Admin\ProfileController@create'); //PHP_Laravel14課題3
    Route::get('profile/edit', 'Admin\ProfileController@edit');
    Route::post('profile/edit', 'Admin\ProfileController@update'); //PHP_Laravel14課題6
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() { 
    Route::get('news/create', 'Admin\NewsController@add');
    Route::post('news/create', 'Admin\NewsController@create');
    Route::get('news', 'Admin\NewsController@index'); //PHP_Laravel16追記
    Route::get('news/edit', 'Admin\NewsController@edit'); //PHP_Laravel17追記
    Route::post('news/edit', 'Admin\NewsController@update'); //PHP_Laravel17追記
    Route::get('news/delete', 'Admin\NewsController@delete'); //PHP_Laravel17追記
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
