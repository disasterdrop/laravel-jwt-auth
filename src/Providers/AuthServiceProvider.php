<?php

namespace Musterhaus\LaravelJWTAuth\Providers;

use Musterhaus\LaravelJWTAuth\Client\AuthGuard;
use Musterhaus\LaravelJWTAuth\Client\Repository;
use Musterhaus\LaravelJWTAuth\Client\UserProvider;
use Illuminate\Support\ServiceProvider;
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

        if (config('jwt.is_server')) {
            $this->loadRoutesFrom(__DIR__ . '/../Server/routes.php');
            $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'jwt');
        }
        else {
            $this->loadRoutesFrom(__DIR__ . '/../Client/routes.php');
        }

        Auth::provider('jwt', function ($app, array $config) {
            $publicKey = Storage::get(config('jwt.public_key'));
            return new UserProvider($app->make(Repository::class), $publicKey);
        });

        Auth::extend('jwt', function ($app, $name, array $config) {
            return new AuthGuard(
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );
        });
    }

}