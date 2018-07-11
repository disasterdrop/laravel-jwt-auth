<?php

namespace Musterhaus\LaravelJWTAuth\Client\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    */

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
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
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
        jwt_auth_set_cookies($user->getAccessToken(), $user->getRefreshToken());
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

        jwt_auth_remove_cookies();

        return redirect('/')
            ->withCookie("jwt_token")
            ->withCookie("jwt_refresh_token");
    }

    public function callback(Request $request)
    {
        var_dump($request->all());
        exit;
    }

    public function password()
    {

    }

    public function showPasswordForm()
    {

    }

}
