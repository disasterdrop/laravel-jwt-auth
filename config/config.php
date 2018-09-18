<?php

return [
    'is_server' => env('JWT_SERVER', false),
    'issuer' => env('JWT_ISSUER', 'jwt-auth-server'),
    'auth_server' => env('JWT_AUTH_SERVER', "https://auth.musterhaus.net"),
    'private_key' => env('JWT_PRIVATE_KEY', 'auth/key/private.pem'),
    'public_key' => env('JWT_PUBLIC_KEY', 'auth/key/public.pem'),
    'cookie_domain' => env('JWT_AUTH_COOKIE_DOMAIN', ""),
    'login_redirect_route' => env('JWT_LOGIN_REDIRECT_ROUTE', ""),
    'password_redirect_route' => env('JWT_PASSWORD_REDIRECT_ROUTE', ""),
];