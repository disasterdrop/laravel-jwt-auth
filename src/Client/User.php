<?php

namespace Musterhaus\LaravelJWTAuth\Client;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class User
 * @package Musterhaus\LaravelJWTAuth
 */
abstract class User implements Authenticatable, \ArrayAccess
{

    /**
     * @var
     */
    protected $accessToken;

    /**
     * @var null
     */
    protected $refreshToken;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * User constructor.
     *
     * @param array $data
     * @param $accessToken
     * @param null $refreshToken
     */
    public function __construct(array $data, $accessToken, $refreshToken = null)
    {
        $this->data = $data;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
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

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        }
    }

}
