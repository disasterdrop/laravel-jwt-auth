<?php

namespace Musterhaus\LaravelJWTAuth\Server\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Musterhaus\LaravelJWTAuth\Server\AuthService;

class LoginController extends BaseController
{

    use AuthenticatesUsers, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $authService;

    protected $redirectTo = '/user';

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        $redirectUri = $request->get('redirect_uri');

        return view('jwt::login', ['redirect_uri' => $redirectUri]);
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
        $accessTokens = $this->authService->generateAccessTokens($user);
        jwt_auth_set_cookies($accessTokens['access_token'], $accessTokens['refresh_token']);

        if ($request->has('redirect_uri')) {
            return redirect()->to($request->get('redirect_uri'));
        }
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

        if ($request->has('continue')) {
            return redirect($request->get('continue'));
        }

        return redirect('/')
            ->withCookie("jwt_token")
            ->withCookie("jwt_refresh_token");
    }

    public function showPasswordForm()
    {
        return view('jwt::passwords.email');
    }

    public function password(Request $request)
    {
        $email = $request->only(["email"]);
        var_dump($email);
        exit;
    }

    public function sendPassword()
    {

    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

}