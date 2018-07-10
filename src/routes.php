<?php

Route::post('api/jwt/login', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@login');
Route::post('api/jwt/refresh', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@refresh');
Route::post('api/jwt/recover', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@recover');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('api/jwt/test', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@test');
    Route::get('api/jwt/logout', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@logout');
});