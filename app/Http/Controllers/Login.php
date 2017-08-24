<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class Login extends Controller
{
    function ShowLoginForm()
    {
        return view('login');
    }

    function LoginAttempt( Request $request )
    {

        $UserData = array(
            'Account' => $request->input('Account'),
            'password' => $request->input('password')
        );

        if( Auth::attempt($UserData) )
        {
            return redirect('/');
        }
        else
        {
            Session::flash('error', '錯誤，請檢查帳號密碼。');
            return redirect('/login');
        }

    }

    function Logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
