<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Operator extends Controller
{
    function ShowOperator()
    {
        return view('Operator.operator');
    }
}
