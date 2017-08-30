<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Monitor;
use App\MachineStatus;
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
        $CurPlayer = Monitor::where('ID', '=', Input::get('id'))->select('Name', 'CurCredit', 'Cellphone')->get()[0];
        return $CurPlayer;
    }

    public function CreditIn()
    {
        $playerBalance = PlayerModel::where('Cellphone', '=', Input::get('playerCellphone'))->select('Balance')->get()[0];
        $machineCurStatus = Monitor::where('Cellphone', '=', Input::get('playerCellphone'))->select('CurCredit', 'ID')->get()[0];
        if ($playerBalance->Balance >= Input::get('credit')) {
            $playerBalance = $playerBalance->Balance - Input::get('credit');
            $machineCurStatus->CurCredit = $machineCurStatus->CurCredit + Input::get('credit');
            PlayerModel::where('Cellphone', '=', Input::get('playerCellphone'))->update(['Balance' => $playerBalance]);
            MachineStatus::where('MachineID', '=', $machineCurStatus->ID)->update(['CurCredit' => $machineCurStatus->CurCredit]);
        } else {
            return 0;//fail
        }
        return 1;//success
    }

    public function CreditOut()
    {
        return 1;
    }
}
