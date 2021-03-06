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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    "namespace" => "Api",
    "prefix" => "auth"

], function () {
    Route::group([
        "namespace" => "Auth",

    ], function () {
        Route::post("register", "RegisterController@register");
        Route::post("login", "LoginController@login");
        Route::group([
            "middleware" => "auth:api",
            "prefix" => "user"
        ], function () {
            Route::get("profile", "UserController@details");
        });
    });
});


Route::group([
    "namespace" => "Api",
    "prefix" => "chat"

], function () {
    Route::group([
        "namespace" => "Chat",
        // "middleware" => "auth:api"
    ], function () {
        Route::get('Redis', 'RedisController@index');
        Route::post('sendmessage', 'RedisController@sendMessage');
        Route::get('writemessage', 'RedisController@writemessage');
    });
});


