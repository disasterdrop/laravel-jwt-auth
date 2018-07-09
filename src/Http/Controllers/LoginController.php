<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function login(Request $request)
    {
        $credentials = $request->only(["email", "password"]);
        if ($token = $this->guard()->attempt($credentials, true)) {
            return $this->sendLoginResponse($request, $token);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request, $token)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user(), $token) ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param $user
     * @param $token
     */
    protected function authenticated(Request $request, $user, $token)
    {
        $num_of_minutes_until_expire = 60 * 24 * 7; // one week

        Cookie::queue('jwt_token', $user->getAccessToken(), $num_of_minutes_until_expire, null, config('jwt.cookie_domain'));
        Cookie::queue('jwt_refresh_token', $user->getRefreshToken(), $num_of_minutes_until_expire, null, config('jwt.cookie_domain'));
    }

    /**
     * @param Request $request
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return $this
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        Cookie::queue('jwt_token', '', 0, null, config('jwt.cookie_domain'));
        Cookie::queue('jwt_refresh_token', '', 0, null, config('jwt.cookie_domain'));

        return redirect('/')
            ->withCookie("jwt_token")
            ->withCookie("jwt_refresh_token");
    }

}
