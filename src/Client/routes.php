<?php

Route::get('login', '\Musterhaus\LaravelJWTAuth\Client\Http\Controllers\LoginController@login')->name('login');
Route::get('logout', '\Musterhaus\LaravelJWTAuth\Client\Http\Controllers\LoginController@logout')->name('logout');
Route::get('password', '\Musterhaus\LaravelJWTAuth\Client\Http\Controllers\LoginController@password')->name('password');