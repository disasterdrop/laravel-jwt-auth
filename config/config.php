<?php

return [
    'private_key' => env('JWT_PRIVATE_KEY', 'auth/key/private.pem'),
    'public_key' => env('JWT_PUBLIC_KEY', 'auth/key/public.pem'),
    'auth_server' => env('JWT_AUTH_SERVER', "https://auth.musterhaus.net"),
    'cookie_domain' => env('JWT_AUTH_COOKIE_DOMAIN', "")
];