<?php

namespace Musterhaus\LaravelJWTAuth\Client\Http\Middleware;

use Closure;

/**
 * Class AccessToken
 * @package Musterhaus\LaravelJWTAuth\Http\Middleware
 */
class AccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->hasCookie('jwt_token') ? $request->cookie("jwt_token") : "";

        $request->headers->set("Authorization", "Bearer $token");

        return $next($request);
    }

}
