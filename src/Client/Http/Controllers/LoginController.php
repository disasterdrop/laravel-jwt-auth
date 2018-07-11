<?php

namespace Musterhaus\LaravelJWTAuth\Client\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class LoginController extends BaseController
{

    public function login()
    {
        $server_url = config('jwt.auth_server') . '/client/login?redirect_uri=' . config('app.url');
        return redirect()->to($server_url);
    }

    public function logout()
    {
        $server_url = config('jwt.auth_server') . '/client/logout?continue=' . route('signout');
        return redirect()->to($server_url);
    }

    public function password()
    {
        $server_url = config('jwt.auth_server') . '/client/password?redirect_uri=' . config('app.url');
        return redirect()->to($server_url);
    }

    public function signout()
    {
        return view('jwt::signout');
    }

}