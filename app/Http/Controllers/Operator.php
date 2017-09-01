<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use DB;
use Hash;

class Operator extends Controller
{
    function ShowOperator()
    {
        return view('Operator.operator');
    }

    function getOperators(Request $request)
    {
        $Page = $request->input('Page');
        $ShowEntries = $request->input('ShowEntries');
        $SearchText = $request->input('SearchText');

        $count = DB::table('operator')->where('Name','like','%'.$SearchText.'%')->count();

        if($ShowEntries == "ALL")
            $ShowEntries = $count;

        $operators = DB::table('operator')->select("id","Name","Account","Type","IDCardNumber","Cellphone")->where('Name','like','%'.$SearchText.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $operators['count'] = $count;

        return Response::json($operators);
    }

    function createOperator(Request $request)
    {
        $IntroducerID = 0;

        if( $request->input('IntroToggle') )
        {
            DB::table('introducer')->insert([
                'IntroducerName' => $request->input('Name'),
                'Gender' => $request->input('Gender'),
                'Address' => $request->input('Address'),
                'Cellphone' => $request->input('Cellphone'),
                'ReturnThreshold' => $request->input('IntroBonusThreshold'),
                'ReturnCreditRate' => $request->input('IntroBonusRate'),
                'CalcWeeks' => $request->input('BonusPeriod'),
                'Create_at' => date("Y-m-d H:i:s"),
            ]);

            $IntroducerID = DB::table('introducer')->max('id');
        }

        DB::table('Operator')->insert([
            'Account' => $request->input('Account'),
            'password' => Hash::make($request->input('Password')),
            'Name' => $request->input('Name'),
            'Type' => $request->input('Type'),
            'Session' => $request->input('Session'),
            'IntroducerID' => $IntroducerID,
            'IDCardNumber' => $request->input('IDCardNumber'),
            'Gender' => $request->input('Gender'),
            'Birthday' => $request->input('Birthday'),
            'Address' => $request->input('Address'),
            'Phone' => $request->input('Phone'),
            'IntroBonus' => $request->input('IntroBonus'),
            'Cellphone' => $request->input('Cellphone')
        ]);



        return Response('Success',200);
    }

    function updateOperator(Request $request)
    {
        // 此員工還不是介紹人，新建立一個介紹人
        if( $request->input('IntroToggle') && DB::table('Operator')->where('Account',$request->input('Account'))->first()->IntroducerID == 0)
        {
            DB::table('introducer')->insert([
                'IntroducerName' => $request->input('Name'),
                'Gender' => $request->input('Gender'),
                'Address' => $request->input('Address'),
                'Cellphone' => $request->input('Cellphone'),
                'ReturnThreshold' => $request->input('IntroBonusThreshold'),
                'ReturnCreditRate' => $request->input('IntroBonusRate'),
                'CalcWeeks' => $request->input('BonusPeriod'),
                'Create_at' => date("Y-m-d H:i:s"),
            ]);

            $IntroducerID = DB::table('introducer')->max('id');

            DB::table('Operator')->where('Account',$request->input('Account'))->update([
                'IntroducerID' => $IntroducerID,
                'IntroBonus' => $request->input('IntroBonus')
            ]);
            
        }
        // 此員工已經是介紹人 純更新資料
        else if($request->input('IntroToggle'))
        {
            $IntroducerID = DB::table('operator')->where('Account',$request->input('Account'))->first()->IntroducerID;

            DB::table('introducer')->where('ID',$IntroducerID)->update([
                'IntroducerName' => $request->input('Name'),
                'Gender' => $request->input('Gender'),
                'Address' => $request->input('Address'),
                'Cellphone' => $request->input('Cellphone'),
                'ReturnThreshold' => $request->input('IntroBonusThreshold'),
                'ReturnCreditRate' => $request->input('IntroBonusRate'),
                'CalcWeeks' => $request->input('BonusPeriod'),
            ]);

            DB::table('operator')->where('Account',$request->input('Account'))->update([
                'IntroBonus' => $request->input('IntroBonus')
            ]);
        }

        DB::table('Operator')->where('Account',$request->input('Account'))->update([
            'Account' => $request->input('Account'),
            'password' => Hash::make($request->input('Password')),
            'Name' => $request->input('Name'),
            'Type' => $request->input('Type'),
            'Session' => $request->input('Session'),
            'IDCardNumber' => $request->input('IDCardNumber'),
            'Gender' => $request->input('Gender'),
            'Birthday' => $request->input('Birthday'),
            'Address' => $request->input('Address'),
            'Phone' => $request->input('Phone'),
            'Cellphone' => $request->input('Cellphone'),
        ]);

        return Response('Success',200);
    }

    function checkDepulicatedAccount(Request $request)
    {
        if( DB::table('Operator')->where('Account', $request->input('Account'))->first() )
        {
            return Response('Depulicated',506);
        }
        else
        {
            return Response('OK',200);
        }
    }

    function deleteOperator(Request $request)
    {
        DB::table('Operator')->where('id',$request->input('id'))->delete();
        return Response('OK',200);
    }

    function getOperatorData(Request $request)
    {
        if( DB::table('Operator')->where('id',$request->input('id'))->first()->IntroducerID != 0 )
        {
            $data = DB::table('Operator')->where('Operator.id',$request->input('id'))->join('Introducer','Operator.IntroducerID','=','Introducer.ID')->first();
        }
        else
        {
            $data = DB::table('Operator')->where('id',$request->input('id'))->first();
        }
        
        return Response::json($data);
    }
}
