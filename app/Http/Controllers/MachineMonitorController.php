<?php

namespace App\Http\Controllers;

use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class MachineMonitorController extends Controller
{

    public function Index()
    {
        $counters = 15;
        return View::make('Machine.Monitor')
            ->with('counters', $counters);
    }
}
