<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MachineProbabilityController extends Controller
{
    public function Index()
    {
        return view('Machine.MachineProbability');
    }
}
