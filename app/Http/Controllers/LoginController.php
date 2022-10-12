<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Helper\Helper;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function username()
    {
        return 'login';
    }

    public function login()
    {
        if(View::exists('admin.auth.login'))
        {
            return view('admin.auth.login');
        }
        abort(Response::HTTP_NOT_FOUND);
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->except(['_token']);

        if(isAdminActive($request->login))
        {
            // Auth::attempt($this->only('login', 'password'), $this->boolean('remember'))
            if(Auth::guard('admin')->attempt([
                'login' => $request->login,
                'password' => $request->password
            ]))
            {
                return redirect(RouteServiceProvider::ADMIN);
            }
            return redirect()->action([
                LoginController::class,
                'login'
            ])->with('message','Credentials not matced in our records!');
        }
        return redirect()->action([
            LoginController::class,
            'login'
        ])->with('message','You are not an active admins!');
    }
}
