<?php

namespace Musterhaus\LaravelJWTAuth\Server;

use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Musterhaus\LaravelJWTAuth\Exceptions\LoginFailedException;
use Musterhaus\LaravelJWTAuth\Exceptions\RefreshTokenRevokedException;

/**
 * Class AuthService
 * @package Musterhaus\LaravelJWTAuth\Server
 */
class AuthService
{

    private $provider;

    public function __construct(AuthProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param string $username
     * @param string $password
     * @return User
     * @throws LoginFailedException
     */
    public function validateUser(string $username, string $password): User
    {
        $user = $this->provider->findUserByIdentifier($username);

        if (!$user) {
            throw new LoginFailedException("User does not exists");
        }

        $verifyPassword = $user->verifyPassword($password);
        if (!$verifyPassword) {
            throw new LoginFailedException("Password does not match");
        }

        return $user;
    }

    /**
     * @param string $refreshToken
     * @return User
     * @throws RefreshTokenRevokedException
     */
    public function validateUserByRefreshToken(string $refreshToken): User
    {
        $refreshToken = $this->provider->findRefreshToken($refreshToken);

        if ($refreshToken->isRevoked()) {
            // refresh token was revoked and is not valid anymore
            throw new RefreshTokenRevokedException("Refresh Token was revoked.");
        }

        $user = $this->provider->findUserByIdentifier($refreshToken->getUserIdentifier());

        // remove used refresh token
        $this->provider->removeOldRefreshTokensForUser();

        return $user;
    }

    /**
     * @param string $refreshToken
     * @return RefreshToken
     */
    public function revokeRefreshToken(string $refreshToken): RefreshToken
    {
        $refreshToken = $this->provider->findRefreshToken($refreshToken);

        if (!$refreshToken->isRevoked()) {
            $refreshToken->revoke();
            $this->provider->revokeRefreshToken($refreshToken);
        }

        return $refreshToken;
    }

    /**
     * @param User $user
     * @param string $expires
     * @return string
     * @throws \Exception
     */
    private function generateTokenForUser(User $user, string $expires = '+15 minutes')
    {
        $privateKey = Storage::get('auth/key/private.pem');
        $accessToken = new AccessToken(config('jwt.issuer', 'jwt-auth-server'), $user, $expires);
        $jwt = JWT::encode($accessToken->getToken(), $privateKey, 'RS256');
        return $jwt;
    }

    /**
     * @param User $user
     * @param string $expires
     * @return bool|string
     * @throws \Exception
     */
    private function generateRefreshTokenForUser(User $user, string $expires = '+1 year')
    {
        $this->provider->removeOldRefreshTokensForUser($user);
        $refreshToken = new RefreshToken($user, $expires);
        $this->provider->saveRefreshToken($refreshToken);

        return $refreshToken->getToken();
    }

    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function generateAccessTokens(User $user): array
    {
        $jwt = $this->generateTokenForUser($user);
        $refreshToken = $this->generateRefreshTokenForUser($user);

        return [
            'access_token' => $jwt,
            'refresh_token' => $refreshToken,
        ];
    }

    /**
     * @param string $jwt
     * @return object
     */
    public function getPayload(string $jwt)
    {
        $publicKey = Storage::get('auth/key/public.pem');
        $payload = JWT::decode($jwt, $publicKey, ['RS256']);

        return $payload;
    }

    /**
     * @return array
     */
    public function createKeyPair()
    {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        $res = openssl_pkey_new($config);

        openssl_pkey_export($res, $privateKey);

        $publicKeyData = openssl_pkey_get_details($res);
        $publicKey = $publicKeyData['key'];

        Storage::put('auth/key/private.pem', $privateKey);
        Storage::put('auth/key/public.pem', $publicKey);

        return [
            'private_key' => $privateKey,
            'public_key' => $publicKey
        ];
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        $publicKey = Storage::get('auth/key/public.pem');
        return $publicKey;
    }

}