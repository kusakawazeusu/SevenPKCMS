<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Monitor;
use App\PlayerModel;

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

    public function CreditIn()
    {
        $playerBalance = PlayerModel::where('ID', '=', Input::get('playerID'))->select('Balance')->get()[0];
        $machineCurCredit = Monitor::where('CurPlayer', '=', Input::get('playerID'))->select('CurCredit')->get()[0];
        if ($playerBalance >= Input::get('credit')) {
            PlayerModel::where('ID', '=', Input::get('playerID'))->update(['Balance' => ($playerBalance - Input::get('credit'))]);
            Monitor::where('CurPlayer', '=', Input::get('playerID'))->update(['Balance' => ($machineCurCredit + Input::get('credit'))]);
        }
        return Response::json(['response'=>'true']);
    }
}
