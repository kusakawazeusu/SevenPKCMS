<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Session;

class Shift extends Controller
{
    public function KnockOffCount()
    {
        $StartTime = DB::table('session')->where('id',Auth::user()->SessionID)->first()->StartTime;

        $CreditIn = DB::table('machinecreditlog')->where('created_at','>',$StartTime)->where('Operation',0)->where('OperatorID', Auth::id())->sum('Credit');
        $CreditOut = DB::table('machinecreditlog')->where('created_at','>',$StartTime)->where('Operation',1)->where('OperatorID', Auth::id())->sum('Credit');

        $CoinIn = DB::table('playlog')->where('created_at','>',$StartTime)->sum('Credit');
        $CoinOut = DB::table('playlog')->where('created_at','>',$StartTime)->sum('WinCredit');

        return view('Shift.knockoff',['CreditIn'=>$CreditIn,'CreditOut'=>$CreditOut,'CoinIn'=>$CoinIn,'CoinOut'=>$CoinOut]);
    }

    public function KnockOff( Request $request ) 
    {
        $UserData = array(
            'Account' => $request->input('Account'),
            'password' => $request->input('password')
        );

        if( Auth::attempt($UserData) )
        {
            DB::table('session')->where('id',$request->input('SessionID'))->update([
                'TotalCreditIn' => $request->input('CreditIn'),
                'TotalCreditOut' => $request->input('CreditOut'),
                'Throughput' => $request->input('Throughput'),
                'TotalCoinIn' => $request->input('CoinIn'),
                'TotalCoinOut' => $request->input('CoinOut'),
                'CoinDiff' => $request->input('CoinDiff'),
                'EndTime' => $request->input('KnockOffTime')
            ]);
    
            Auth::logout();
            return redirect('/');
        }
        else
        {
            Session::flash('error', '錯誤，請檢查帳號密碼。');
            return back();
        }
    }

    public function ShiftCount()
    {
        $StartTime = DB::table('session')->where('id',Auth::user()->SessionID)->first()->StartTime;
        
        $CreditIn = DB::table('machinecreditlog')->where('created_at','>',$StartTime)->where('Operation',0)->where('OperatorID', Auth::id())->sum('Credit');
        $CreditOut = DB::table('machinecreditlog')->where('created_at','>',$StartTime)->where('Operation',1)->where('OperatorID', Auth::id())->sum('Credit');
        
        $CoinIn = DB::table('playlog')->where('created_at','>',$StartTime)->sum('Credit');
        $CoinOut = DB::table('playlog')->where('created_at','>',$StartTime)->sum('WinCredit');

        return view('Shift.shift',['CreditIn'=>$CreditIn,'CreditOut'=>$CreditOut,'CoinIn'=>$CoinIn,'CoinOut'=>$CoinOut]);
    }

    public function Shift( Request $request ) 
    {
        $NextUserData = array(
            'Account' => $request->input('NextSessionAccount'),
            'password' => $request->input('NextSessionPassword')
        );

        $UserData = array(
            'Account' => $request->input('Account'),
            'password' => $request->input('password')
        );

        if( Auth::attempt($UserData) && Auth::attempt($NextUserData) )
        {
            DB::table('session')->where('id',$request->input('SessionID'))->update([
                'EndTime' => $request->input('KnockOffTime'),
                'TotalCreditIn' => $request->input('CreditIn'),
                'TotalCreditOut' => $request->input('CreditOut'),
                'Throughput' => $request->input('Throughput'),
                'TotalCoinIn' => $request->input('CoinIn'),
                'TotalCoinOut' => $request->input('CoinOut'),
                'CoinDiff' => $request->input('CoinDiff')
            ]);

            DB::table('session')->insert([
                'OperatorID' => Auth::user()->id,
                'Status' => 1,
                'StartTime' => date('Y-m-d H:i:s'),
            ]);

            $Shift = DB::table('session')->where('OperatorID',Auth::user()->id)->orderBy('StartTime','dsc')->whereNull('EndTime')->first();

            DB::table('operator')->where('id',Auth::user()->id)->update([
                'SessionID' => $Shift->ID
            ]);

            return redirect('/');
        }
        else
        {
            Session::flash('error', '錯誤，請檢查帳號密碼。');
            return back();
        }
    }
}
