<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('client/login', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@showLoginForm')->name('login');
    Route::post('client/login', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@login')->name('login');
    Route::get('client/logout', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@logout')->name('logout');
    Route::get('client/password', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@showPasswordForm')->name('password.request');
    Route::post('client/password', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@password')->name('password.request');
    Route::post('client/password', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@sendPassword')->name('password.email');
});

Route::group(['middleware' => ['api']], function () {
    Route::post('jwt/authorize', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@authorize')->name('jwt.authorize');
    Route::post('jwt/refresh', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@refresh')->name('jwt.refresh');
    Route::post('jwt/recover', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@recover')->name('jwt.recover');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('jwt/test', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@test');
    Route::get('jwt/logout', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@logout')->name('jwt.logout');
});