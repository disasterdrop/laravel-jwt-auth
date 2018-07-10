<?php

namespace Musterhaus\LaravelJWTAuth\Client;

use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as LaravelUserProvider;

/**
 * Class UserProvider
 * @package Musterhaus\LaravelJWTAuth
 */
class UserProvider implements LaravelUserProvider
{

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * UserProvider constructor.
     *
     * @param Repository $repository
     * @param string $publicKey
     */
    public function __construct(Repository $repository, string $publicKey)
    {
        $this->repository = $repository;
        $this->publicKey = $publicKey;
    }

    /**
     * @param string $jwt
     * @return null|object
     */
    public function getPayload(string $jwt)
    {
        try {
            $payload = JWT::decode($jwt, $this->publicKey, ['RS256']);

            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param mixed $identifier
     * @return Authenticatable|User|null
     */
    public function retrieveById($identifier)
    {
        $payload = $this->getPayload($identifier);
        return $this->repository->createUser((array)$payload->data, $identifier);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return Authenticatable|User|null
     */
    public function retrieveByToken($identifier, $token)
    {
        try {
            $data = $this->repository->retrieveByToken($identifier, $token);
            $payload = $this->getPayload($data['access_token']);

            return $this->repository->createUser((array)$payload->data, $data['access_token'], $data['refresh_token']);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * @param array $credentials
     * @return User|Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        try {
            $data = $this->repository->retrieveByCredentials($credentials);
            $payload = $this->getPayload($data['access_token']);

            return $this->repository->createUser((array)$payload->data, $data['access_token'], $data['refresh_token']);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $payload = $this->getPayload($user->getAccessToken());

        if (!isset($payload->exp) || $payload->exp <= time()) {
            return false;
        }

        return ($payload->data->email === $credentials['email']);
    }

}