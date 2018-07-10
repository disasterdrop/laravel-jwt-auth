<?php

namespace Musterhaus\LaravelJWTAuth\Client;

/**
 * Interface Repository
 * @package Musterhaus\LaravelJWTAuth\Client
 */
interface Repository
{

    /**
     * @param array $data
     * @param string $accessToken
     * @param null|string $refreshToken
     * @return User
     */
    public function createUser(array $data, string $accessToken, ?string $refreshToken = null): User;

    /**
     * @param $identifier
     * @param $token
     * @return array ['access_token', 'refresh_token']
     */
    public function retrieveByToken($identifier, $token): array;

    /**
     * @param array $credentials
     * @return array ['access_token', 'refresh_token']
     */
    public function retrieveByCredentials(array $credentials): array;

}