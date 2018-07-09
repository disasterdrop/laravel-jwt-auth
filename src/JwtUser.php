<?php

namespace Musterhaus\LaravelJWTAuth;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class JwtUser
 * @package Musterhaus\LaravelJWTAuth
 */
class JwtUser implements Authenticatable
{

    public $name;

    public $email;

    public $id;

    public $role;

    private $accessToken;

    private $refreshToken;

    /**
     * JwtUser constructor.
     * @param $accessToken
     * @param null $refreshToken
     */
    public function __construct(array $data, $accessToken, $refreshToken = null)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;

        $this->id = $data['id'];
        $this->name = $data['username'];
        $this->email = $data['email'];
        $this->role = $data['role'];
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }

    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
    }

    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }

}
