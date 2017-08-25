<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Monitor;

class MachineMonitorController extends Controller
{
    public function Index()
    {
        $machines = Monitor::all();
        //dd($machines);
        return view('Machine.Monitor', ['counters'=>$machines->count()-1,'machines'=>$machines]);
    }
}
