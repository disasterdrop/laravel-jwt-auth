<?php

namespace Musterhaus\LaravelJWTAuth\Server;

use Ramsey\Uuid\Uuid;

/**
 * Class AccessToken
 * @package Musterhaus\LaravelJWTAuth\Server
 */
class AccessToken implements Token
{

    /**
     * @var array
     */
    private $token;

    /**
     * @var \DateTimeImmutable
     */
    private $expires;

    /**
     * AccessToken constructor.
     * @param $issuer
     * @param User $user
     * @param string $expires
     * @throws \Exception
     */
    public function __construct($issuer, User $user, $expires = '+15 minutes')
    {
        $today = new \DateTimeImmutable();
        $this->generateExpires($expires);

        $this->token = [
            'iss' => $issuer,
            'sub' => $user->getJWTSubject(),
            'aud' => $user->getJWTAudience(),
            'iat' => $today->getTimestamp(),
            'nbf' => $today->getTimestamp(),
            'exp' => $this->expires->getTimestamp(),
            'jti' => Uuid::uuid4(),
            'data' => $user->getJWTPayloadData()
        ];
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
     * @return array
     */
    public function getToken()
    {
        return $this->token;
    }

    public function isRevokable(): bool
    {
        return false;
    }

    /**
     * @param string $format
     * @return string
     */
    public function expiresAt(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->expires->format($format);
    }

}