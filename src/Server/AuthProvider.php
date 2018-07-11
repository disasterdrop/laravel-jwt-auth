<?php

namespace Musterhaus\LaravelJWTAuth\Server;


interface AuthProvider
{

    public function findUserByIdentifier(string $identifier): User;

    public function findRefreshToken(string $refreshToken): RefreshToken;

    public function removeOldRefreshTokensForUser(User $user);

    public function saveRefreshToken(RefreshToken $refreshToken);

    public function revokeRefreshToken(RefreshToken $refreshToken);

    public function findRefreshTokensByUser(User $user): array;

}