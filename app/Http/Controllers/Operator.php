<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use DB;

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

        $operators = DB::table('operator')->select("Name","Account","Type","IDCardNumber","Phone")->where('Name','like','%'.$SearchText.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();

        return Response::json($operators);
    }
}
