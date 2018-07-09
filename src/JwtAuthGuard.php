<?php

namespace Musterhaus\LaravelJWTAuth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Class JwtAuthGuard
 * @package Musterhaus\LaravelJWTAuth
 */
class JwtAuthGuard implements Guard
{

    use GuardHelpers;

    /**
     * @var Request
     */
    protected $request;

    /**
     * JwtAuthGuard constructor.
     * @param UserProvider $provider
     * @param Request $request
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * @return Authenticatable|null
     */
    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $token = $this->request->bearerToken();
        if (!empty($token) && $this->provider->getPayload($token)) {
            return $this->user = $this->provider->retrieveById($token);
        }

        if (Cookie::has('jwt_refresh_token')) {
            $refreshToken = Cookie::get('jwt_refresh_token');
            $user = $this->provider->retrieveByToken($token, $refreshToken);

            if ($user instanceof JwtUser) {
                $this->user = $user;
                $num_of_minutes_until_expire = 60 * 24 * 7; // one week
                Cookie::queue('jwt_token', $user->getAccessToken(), $num_of_minutes_until_expire, null, config('jwt-auth.cookie_domain'));
                Cookie::queue('jwt_refresh_token', $user->getRefreshToken(), $num_of_minutes_until_expire, null, config('jwt-auth.cookie_domain'));

                return $this->user;
            }
        }
    }

    public function viaRemember()
    {
        return false;
    }

    /**
     * Log a user into the application using their credentials.
     *
     * @param  array $credentials
     *
     * @return bool
     */
    public function once(array $credentials = [])
    {
        if ($this->validate($credentials)) {
            return true;
        }

        return false;
    }

    /**
     * Log the given User into the application.
     *
     * @param  mixed $id
     *
     * @return bool
     */
    public function onceUsingId($id)
    {
        if ($user = $this->provider->retrieveById($id)) {
            $this->setUser($user);

            return true;
        }

        return false;
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return (bool)$this->attempt($credentials, true);
    }

    /**
     * @param array $credentials
     * @param bool $login
     * @return bool|string
     */
    public function attempt(array $credentials = [], bool $login = false)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->user = $user;

            return $login ? $this->login($user) : true;
        }

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed $user
     * @param  array $credentials
     *
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Create a token for a user.
     *
     * @return string
     * @param JwtUser $user
     */
    public function login(JwtUser $user)
    {
        return $user->getAccessToken();
    }

    /**
     *
     */
    public function logout()
    {
        return true;
    }

}