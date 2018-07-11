<?php

Route::get('jwt/client/login', '\Musterhaus\LaravelJWTAuth\Client\Http\Controllers\LoginController@showLoginForm')->name('login');
Route::get('jwt/client/callback', '\Musterhaus\LaravelJWTAuth\Client\Http\Controllers\LoginController@callback')->name('login.callback');
Route::get('jwt/client/password', '\Musterhaus\LaravelJWTAuth\Client\Http\Controllers\LoginController@showPasswordForm')->name('login.password');

Route::post('jwt/authorize', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@authorize')->name('jwt.authorize');
Route::post('jwt/refresh', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@refresh')->name('jwt.refresh');
Route::post('jwt/recover', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@recover')->name('jwt.recover');

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('jwt/test', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@test');
    Route::get('jwt/logout', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\AuthController@logout')->name('jwt.logout');
});