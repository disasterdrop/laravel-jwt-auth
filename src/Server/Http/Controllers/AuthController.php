<?php

namespace Musterhaus\LaravelJWTAuth\Server\Http\Controllers;

use Illuminate\Http\Request;
use Musterhaus\LaravelJWTAuth\Exceptions\LoginFailedException;
use Musterhaus\LaravelJWTAuth\Server\AuthService;
use Musterhaus\LaravelJWTAuth\Server\Http\Requests\Login;
use Musterhaus\LaravelJWTAuth\Server\Http\Requests\Refresh;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class AuthController
 * @package Musterhaus\LaravelJWTAuth\Server\Http\Controllers
 */
class AuthController extends BaseController
{
    /**
     * @var AuthService
     */
    private $authService;

    /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param Login $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorize(Login $request)
    {
        $validated = $request->validated();
        $redirectUri = $request->get('redirect_uri');

        try {
            $user = $this->authService->validateUser($validated['username'], $validated['password']);

            $accessTokens = $this->authService->generateAccessTokens($user);

            if ($request->wantsJson() || empty($redirectUri)) {
                return response()->json($accessTokens);
            } else {
                return redirect()->to($redirectUri)->with('token', $accessTokens);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * @param Refresh $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Refresh $request)
    {
        $validated = $request->validated();

        try {
            $user = $this->authService->validateUserByRefreshToken($validated['refresh_token']);

            return response()->json($this->authService->generateAccessTokens($user));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revoke(Request $request)
    {
        try {
            $refreshToken = $this->authService->revokeRefreshToken($request->get('refresh_token'));
            return response()->json([
                'refresh_token' => $refreshToken->getToken(),
                'revoked' => $refreshToken->isRevoked()
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     *
     */
    public function recover()
    {

    }

    /**
     *
     */
    public function logout()
    {
        jwt_auth_remove_cookies();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function keygen()
    {
        $this->authorize('keygen');
        $keypair = $this->authService->createKeyPair();

        return response()->json($keypair);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function publickey()
    {
        $this->authorize('publickey');

        $publicKey = $this->authService->getPublicKey();

        return response()->json(['public_key' => $publicKey]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function test()
    {
        return response()->json(['timestamp' => time()]);
    }

    /**
     * @return mixed
     */
    private function getRefererHost()
    {
        $referrer = request()->server('HTTP_REFERER');
        $host = parse_url($referrer, PHP_URL_HOST);

        return $host;
    }

}