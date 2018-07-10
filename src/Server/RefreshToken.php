<?php

namespace Musterhaus\LaravelJWTAuth\Server;

/**
 * Class RefreshToken
 *
 * @package Musterhaus\LaravelJWTAuth\Server
 */
class RefreshToken implements Token
{

    /**
     * @var bool
     */
    private $revoked = false;

    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTimeImmutable
     */
    private $expires;

    /**
     * @var User
     */
    private $user;

    /**
     * RefreshToken constructor.
     * @param User $user
     * @param string $expires
     * @throws \Exception
     */
    public function __construct(User $user, string $expires = '+1 year')
    {
        $this->token = $this->generateAccessToken();
        $this->expires = $this->generateExpires($expires);
        $this->setUser($user);
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isRevokable(): bool
    {
        return (!$this->isRevoked());
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getUserIdentifier()
    {
        return $this->user->getIdentifier();
    }

    /**
     * Revoke access to Refresh Token
     */
    public function revoke()
    {
        if ($this->revoked === false) {
            $this->revoked = true;
        }
    }

    /**
     * @return bool
     */
    public function isRevoked(): bool
    {
        return ($this->revoked === true);
    }

    /**
     * @param string $format
     * @return string
     */
    public function expiresAt(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->expires->format($format);
    }

    /**
     * @param $expires
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    private function generateExpires($expires): \DateTimeImmutable
    {
        $today = new \DateTimeImmutable();
        $this->expires = $today->modify($expires);

        return $this->expires;
    }

    /**
     * @return bool|string
     */
    private function generateAccessToken()
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $randomData = openssl_random_pseudo_bytes(20);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 20);
            if ($randomData !== false && strlen($randomData) === 20) {
                return bin2hex($randomData);
            }
        }
        // Last resort which you probably should just get rid of:
        $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true) . uniqid(mt_rand(), true);

        return substr(hash('sha512', $randomData), 0, 40);
    }

}