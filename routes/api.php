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

Route::group(['prefix' => 'v1', 'as' => 'api.v1.', 'namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('register', ['as' => 'register', 'uses' => 'RegisterController@create']);
        Route::post('login', ['as' => 'login', 'uses' => 'LoginController@login']);
        Route::post('logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('user-profile', 'UserController@showProfile');
        Route::resource('projects', 'ProjectController');
        Route::resource('fields', 'FieldController', ['only' => [
            'update', 'destroy',
        ]]);
        Route::resource('endpoints', 'EndpointController', ['only' => [
            'index', 'show',
        ]]);
    });
});
