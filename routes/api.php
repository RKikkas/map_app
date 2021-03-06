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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('userCoordinates/{id}', 'CoordinatesController@userCoordinates');
Route::middleware('auth:api')->get('/usersForMap', 'UsersController@usersForMap');
Route::middleware('auth:api')->get('/userStartDates/{id}', 'CoordinatesController@userStartDates');
Route::middleware('auth:api')->post('/users/{id}', 'UsersController@update');
Route::middleware('auth:api')->delete('/users/{id}', 'UsersController@destroy');

// Route for storing new coordinates to DB
Route::post('log', 'CoordinatesController@store');

// Authenticated routes for logging in, registering etc..
Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::get('refresh', 'AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('user', 'AuthController@user');
        Route::get('users', 'UsersController@index')->middleware('isAdmin');
        Route::get('users/{id}', 'UsersController@show')->middleware('isAdminOrSelf');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'isAdmin'], function() {
        Route::post('changeSecretCode', 'AdminController@changeSecretCode');
        Route::post('changeUserPassword', 'AdminController@changeUserPassword');
    });
});
