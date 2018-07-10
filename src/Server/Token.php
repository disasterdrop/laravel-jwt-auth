<?php
/**
 * Created by PhpStorm.
 * User: Sebastian Okolowski
 * Date: 10.07.2018
 * Time: 09:03
 */

namespace Musterhaus\LaravelJWTAuth\Server;


interface Token
{

    public function getToken();

    public function isRevokable(): bool;

    public function expiresAt(string $format = 'Y-m-d H:i:s'): string;

}