<?php

use \Illuminate\Support\Facades\Cookie;

if (!function_exists('jwt_auth_set_cookies')) {
    function jwt_auth_set_cookies(string $access_token, string $refresh_token, int $tokenExpires = 60 * 15, int $refreshExpires = 60 * 60 * 24 * 31)
    {
        Cookie::queue('jwt_token', $access_token, $tokenExpires, null, config('jwt.cookie_domain'));
        Cookie::queue('jwt_refresh_token', $refresh_token, $refreshExpires, null, config('jwt.cookie_domain'));
    }
}

if (!function_exists('jwt_auth_remove_cookies')) {
    function jwt_auth_remove_cookies()
    {
        Cookie::queue('jwt_token', '', 0, null, config('jwt.cookie_domain'));
        Cookie::queue('jwt_refresh_token', '', 0, null, config('jwt.cookie_domain'));
    }
}

if (!function_exists('jwt_get_access_token')) {
    function jwt_get_access_token()
    {
        return Cookie::get('jwt_token');
    }
}

if (!function_exists('jwt_get_refresh_token')) {
    function jwt_get_refresh_token()
    {
        return Cookie::get('jwt_refresh_token');
    }
}

