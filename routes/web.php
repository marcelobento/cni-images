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
    return redirect(route('images'));
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home', function() {
    return redirect(route('images'));
});

// Images
Route::post('/images', function () {
    return view('images.new');
})->name('images.new');

Route::prefix('users')->middleware('auth')->group(function() {
    Route::get('/', 'UsersController@index')->name('users');
    Route::get('create', 'UsersController@create')->name('users.create');
    Route::post('store', 'UsersController@store')->name('users.store');
    Route::get('{id}/edit', 'UsersController@edit')->name('users.edit');
    Route::put('update', 'UsersController@update')->name('users.update');
    Route::get('{id}/delete', 'UsersController@delete')->name('users.delete');
});

Route::prefix('images')->group(function() {
    Route::get('/', 'ImagesController@index')->middleware('auth')->name('images');
    Route::get('create', 'ImagesController@create')->middleware('auth')->name('images.create');
    Route::post('store', 'ImagesController@store')->middleware('auth')->name('images.store');
    Route::get('{id}/edit', 'ImagesController@edit')->middleware('auth')->name('images.edit');
    Route::put('{id}/update', 'ImagesController@update')->middleware('auth')->name('images.update');
    Route::get('{id}/delete', 'ImagesController@delete')->middleware('auth')->name('images.delete');
});