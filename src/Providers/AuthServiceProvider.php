<?php

namespace Musterhaus\LaravelJWTAuth\Providers;

use Musterhaus\LaravelJWTAuth\JwtAuthGuard;
use Musterhaus\LaravelJWTAuth\JwtUserProvider;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class AuthServiceProvider
 * @package Musterhaus\LaravelJWTAuth\Providers
 */
class AuthServiceProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
        $path = realpath(__DIR__ . '/../../config/config.php');

        $this->publishes([$path => config_path('jwt.php')], 'config');
        $this->mergeConfigFrom($path, 'jwt');

        Auth::provider('jwt', function ($app, array $config) {
            $client = new Client(['base_uri' => config('jwt.auth_server')]);
            $publicKey = Storage::get(config('jwt.public_key'));

            return new JwtUserProvider($client, $publicKey);
        });

        Auth::extend('jwt', function ($app, $name, array $config) {
            return new JwtAuthGuard(
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );
        });
    }

}