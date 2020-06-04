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

Auth::routes([
    'verify'    => false
]);

Route::get('appreg', 'Auth\RegisterController@showRegistrationForm')->name('appreg');
Route::post('appreg', 'Auth\RegisterController@register');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('users','UserController');
    Route::get('/changePassword','UserController@showChangePasswordForm');
    Route::post('/changePassword','UserController@changePassword')->name('changePassword');

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/home', 'HomeController@search')->name('home');

    Route::get('/analytics', 'AnalyticsController@index')->name('analytics');
    Route::post('/analytics', 'AnalyticsController@search')->name('analytics');

    Route::get('/reasons', 'ReasonsController@index')->name('reasons');
    Route::post('/reasons', 'ReasonsController@search')->name('reasons');
    Route::post('/reasonslist', 'ReasonsController@reasonslist')->name('reasonslist');

    Route::resource('responses', 'AutoResponseController');
    Route::resource('settings', 'SettingsController');

    Route::get('/tracker', 'TrackerController@index')->name('tracker');
    Route::post('/tracker', 'TrackerController@search')->name('tracker');
    Route::post('/tracklist', 'TrackerController@tracklist')->name('tracklist');
    Route::get('tracker/{slug}', 'TrackerController@details');

    Route::get('/test-mail', 'APIController@testMail');
});