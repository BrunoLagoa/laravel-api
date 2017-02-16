<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your api!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//$this->resource('products', 'api\ProductController');
Route::group(['prefix' => 'v1'], function () {

    Route::post('auth', 'Auth\AuthApiController@authenticate');
    Route::post('auth-refresh', 'Auth\AuthApiController@refreshToken');

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('products/search', 'api\V1\ProductController@search');
        Route::resource('products', 'api\V1\ProductController', ['except' => ['create', 'edit']]);
    });

});
