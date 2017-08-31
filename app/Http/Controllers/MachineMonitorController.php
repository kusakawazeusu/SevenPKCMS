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

    public function GetCur()
    {
        $CurPlayer = Monitor::where('ID', '=', Input::get('id'))->select('Name', 'CurCredit', 'Cellphone', 'Status')->get()[0];
        return $CurPlayer;
    }

    public function CreditIn()
    {
        $player = PlayerModel::where('Cellphone', '=', Input::get('playerCellphone'))->select('Balance', 'ID')->get()[0];
        $machineCurStatus = Monitor::where('ID', '=', Input::get('machineID'))->select('CurCredit', 'ID')->get()[0];
        if ($player->Balance >= Input::get('credit')) {
            $player->Balance = $player->Balance - Input::get('credit');
            $machineCurStatus->CurCredit = $machineCurStatus->CurCredit + Input::get('credit');
            PlayerModel::where('Cellphone', '=', Input::get('playerCellphone'))->update(['Balance' => $player->Balance]);
            MachineStatus::where('MachineID', '=', $machineCurStatus->ID)->update(['CurCredit' => $machineCurStatus->CurCredit, 'Status' => '1', 'CurPlayer'=> $player->ID]);
        } else {
            return Response::json(['done'=>'unsuccess', 'errorMsg'=>'餘額不足']);
        }
        return Response::json(['done'=>'success','machineID'=>Monitor::where('Cellphone', '=', Input::get('playerCellphone'))->select('CurCredit',
        'ID')->get()[0]->ID]);
    }

    public function CreditOut()
    {
        $monitor = Monitor::where('ID', '=', Input::get('ID'))->select('CurCredit', 'CurPlayer', 'ID', 'Name')->get()[0];
        if (Input::get('type') == 'ToCredit') {
            $credit = PlayerModel::where('ID', '=', $monitor->CurPlayer)->select('Balance')->get()[0]->Balance + $monitor->CurCredit;
            PlayerModel::where('ID', '=', $monitor->CurPlayer)->update(['Balance' => $credit]);
            $machineCurStatus = MachineStatus::where('MachineID', '=', Input::get('ID'))->update(['Status' => '0','CurCredit' =>'0', 'CurPlayer' =>'0']);
            return Response::json(['done'=>'success', 'type'=>'ToCredit', 'credit'=>$credit, 'machineID'=>$monitor->ID]);
        } elseif (Input::get('type') == 'ToCash') {
            $response = Response::json(['done'=>'success', 'type'=>'ToCash', 'credit'=>$monitor->CurCredit, 'playerName'=>$monitor->Name,'machineID'=>$monitor->ID]);
            $machineCurStatus = MachineStatus::where('MachineID', '=', Input::get('ID'))->update(['Status' => '0','CurCredit' =>'0', 'CurPlayer' =>'0']);
            return $response;
        }

        return Response::json(['done'=>'unsuccess', 'type'=>'ToCredit', 'credit'=>0]);
    }
}
