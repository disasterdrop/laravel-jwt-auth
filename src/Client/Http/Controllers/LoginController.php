<?php

namespace Musterhaus\LaravelJWTAuth\Client\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class LoginController extends BaseController
{

    public function login()
    {
        $server_url = config('jwt.auth_server') . '/login?redirect_uri=' . route(config('jwt.login_redirect_route'));
        return redirect()->to($server_url);
    }

    public function logout()
    {
        $server_url = config('jwt.auth_server') . '/logout?continue=' . route('signout');
        return redirect()->to($server_url);
    }

    public function password()
    {
        $server_url = config('jwt.auth_server') . '/password?redirect_uri=' . route(config('jwt.password_redirect_route'));
        return redirect()->to($server_url);
    }

    public function signout()
    {
        return view('jwt::signout');
    }

}