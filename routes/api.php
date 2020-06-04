<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('reason', 'APIController');
Route::resource('receive-uninstall-tracking', 'APIController');

Route::group(['prefix' => 'v1'], function() {
    Route::post('reason', 'APIController@reasonTrackerV2');
    Route::post('track', 'APIController@track');
});
