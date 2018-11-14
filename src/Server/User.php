<?php

namespace Musterhaus\LaravelJWTAuth\Server;

interface User
{

    /**
     * @param $password
     * @return bool
     */
    public function verifyPassword($password): bool;

    /**
     * @param $service
     * @return bool
     */
    public function verifyService($service): bool;

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @return array
     */
    public function getJWTPayloadData(): array;

    /**
     * @return string
     */
    public function getJWTSubject(): string;

    /**
     * @return string
     */
    public function getJWTAudience(): string;

}