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

/**
 * Load all the routes for main page.
 */
Route::get('password/reset/{token}', function () {
    return view('layouts.vue');
})->name('password.reset');
Route::domain('www.'.env('APP_DOMAIN'))->group(function () {
    Route::get('/', 'HomeController@index')->name('homepage'); // main showcase
    Route::get('contact', 'HomeController@contact')->name('contact');
    Route::post('contact', 'HomeController@postContact');
    Route::get('prices', 'HomeController@prices')->name('prices');
    Route::get('terms-and-conditions', 'HomeController@terms');

    Route::get('{any}', function (Illuminate\Http\Request $request) {
        return view('layouts.vue');
    })->where('any', '.*');
});

/**
 * We try to load club served from subdomain.
 */
Route::domain('{club_sub}.'.env('APP_DOMAIN'))->group(function () {
    Route::get('{any}', function (Illuminate\Http\Request $request) {
        return view('layouts.vue');
    })->where('any', '.*');
});



