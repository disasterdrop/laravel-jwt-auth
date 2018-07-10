<?php

namespace Musterhaus\LaravelJWTAuth\Client;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class User
 * @package Musterhaus\LaravelJWTAuth
 */
class User implements Authenticatable
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $role;

    /**
     * @var array
     */
    public $services = [];

    /**
     * @var
     */
    private $accessToken;

    /**
     * @var null
     */
    private $refreshToken;

    /**
     * User constructor.
     *
     * @param array $data
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
        $this->services = $data['services'];
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
