<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('login', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@showLoginForm')->name('login');
    Route::post('login', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@login')->name('login');
    Route::get('logout', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\LoginController@logout')->name('logout');

    Route::get('password/reset', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', '\Musterhaus\LaravelJWTAuth\Server\Http\Controllers\ResetPasswordController@reset');
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