<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MachineMonitorController extends Controller
{
    //
    public function Index()
    {
        $i = 150;
        return view('Machine.Monitor', ['counters'=>$i]);
    }
}
