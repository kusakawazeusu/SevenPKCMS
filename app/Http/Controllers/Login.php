<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Response;

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
            $NonShift = DB::table('session')->where('id',Auth::user()->SessionID)->whereNull('EndTime')->first();

            if( !$NonShift )
            {
                DB::table('session')->insert([
                    'OperatorID' => Auth::user()->id,
                    'Status' => 1,
                    'StartTime' => date('Y-m-d H:i:s'),
                ]);

                $Shift = DB::table('session')->where('OperatorID',Auth::user()->id)->orderBy('StartTime','dsc')->whereNull('EndTime')->first();

                DB::table('operator')->where('id',Auth::user()->id)->update([
                    'SessionID' => $Shift->ID
                ]);
            }

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
