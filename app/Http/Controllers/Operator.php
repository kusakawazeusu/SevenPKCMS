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
        DB::table('Operator')->insert([
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

    function updateOperator(Request $request)
    {
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
        $data = DB::table('Operator')->where('id',$request->input('id'))->first();
        return Response::json($data);
    }
}
