<?php

namespace Musterhaus\LaravelJWTAuth;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * Class JwtUserProvider
 * @package Musterhaus\LaravelJWTAuth
 */
class JwtUserProvider implements UserProvider
{

    private $client;

    private $publicKey;

    /**
     * JwtUserProvider constructor.
     * @param Client $client
     * @param $publicKey
     */
    public function __construct(Client $client, $publicKey)
    {
        $this->client = $client;
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
     * @return JwtUser|Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $payload = $this->getPayload($identifier);
        return new JwtUser((array)$payload->data, $identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        try {
            $response = $this->client->post('api/refresh', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode([
                    'refresh_token' => $token
                ])
            ]);

            $body = json_decode((string)$response->getBody());
            $payload = $this->getPayload($body->access_token);

            return new JwtUser((array)$payload->data, $body->access_token, $body->refresh_token);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * @param array $credentials
     * @return JwtUser|Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $response = $this->client->post('api/login', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'username' => $credentials['email'],
                'password' => $credentials['password']
            ])
        ]);

        $body = json_decode((string)$response->getBody());
        $payload = $this->getPayload($body->access_token);

        return new JwtUser((array)$payload->data, $body->access_token, $body->refresh_token);
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