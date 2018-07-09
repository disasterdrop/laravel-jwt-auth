<?php

use \Illuminate\Support\Facades\Cookie;

if (!function_exists('jwt_auth_set_cookies')) {
    function jwt_auth_set_cookies(string $access_token, string $refresh_token, int $expires = 60 * 24 * 7)
    {
        Cookie::queue('jwt_token', $access_token, $expires, null, config('jwt.cookie_domain'));
        Cookie::queue('jwt_refresh_token', $refresh_token, $expires, null, config('jwt.cookie_domain'));
    }
}

if (!function_exists('jwt_auth_remove_cookies')) {
    function jwt_auth_remove_cookies()
    {
        Cookie::queue('jwt_token', '', 0, null, config('jwt.cookie_domain'));
        Cookie::queue('jwt_refresh_token', '', 0, null, config('jwt.cookie_domain'));
    }
}