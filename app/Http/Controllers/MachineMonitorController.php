<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Monitor;

class MachineMonitorController extends Controller
{
    public function Index()
    {
        $machines = Monitor::all();
        return view('Machine.Monitor', ['counters'=>$machines->count()-1,'machines'=>$machines]);
    }

    public function GetCurPlayer()
    {
        $CurPlayer = Monitor::where('ID', '=', Input::get('id'))->select('CurPlayer', 'CurCredit')->get()[0];
        return $CurPlayer;
    }
}
