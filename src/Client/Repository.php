<?php

namespace Musterhaus\LaravelJWTAuth\Client;

interface Repository
{

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