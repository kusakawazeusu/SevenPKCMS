<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Monitor;
use App\MachineStatus;
use App\PlayerModel;
use App\MachineCreditLog;
use App\MachineMeter;

class MachineMonitorController extends Controller
{
    public function Index()
    {
        $machines = Monitor::all();
        return view('Machine.Monitor', ['counters'=>$machines->count(),'machines'=>$machines]);
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
            $machineCreditLog = new MachineCreditLog;
            $machineCreditLog->OperatorID = Input::get('operatorID');
            $machineCreditLog->Operation = 0;
            $machineCreditLog->Credit = Input::get('credit');
            $machineCreditLog->MachineID = Input::get('machineID');
            $machineCreditLog->PlayerID = $player->ID;
            $machineCreditLog->save();
        } else {
            return Response::json(['done'=>'unsuccess', 'errorMsg'=>'餘額不足']);
        }
        $code = 0;
        if (Input::get('needCode')=='true') {
            $code = $this->VerificationCode(Input::get('machineID'));
        }
        MachineMeter::where('MachineID', '=', Input::get('machineID'))->increment('TotalCreditIn',Input::get('credit'));
        return Response::json(['done'=>'success','machineID'=>Monitor::where('Cellphone', '=', Input::get('playerCellphone'))->select('CurCredit',
            'ID')->get()[0]->ID, 'code'=>$code]);
    }

    public function CreditOut()
    {
        $monitor = Monitor::where('ID', '=', Input::get('ID'))->get()[0];
        $machineCreditLog = new MachineCreditLog;
        $machineCreditLog->OperatorID = Input::get('operatorID');
        $Credit = $monitor->CurCredit + ($monitor->CurCoinIn > $monitor->MinCoinOut?(floor($monitor->CurCoinIn / 100) * 100):0);
        $leftCredit = $monitor->CurCoinIn - ($monitor->CurCoinIn > $monitor->MinCoinOut?(floor($monitor->CurCoinIn / 100) * 100):0);
        $machineCreditLog->Credit = $Credit;
        $machineCreditLog->MachineID = $monitor->ID;
        $machineCreditLog->PlayerID = $monitor->CurPlayer;
        MachineMeter::where('MachineID', '=', Input::get('ID'))->increment('TotalCreditOut' , $Credit);
        if (Input::get('type') == 'ToCredit') {
            $machineCreditLog->Operation = 1;
            $credit = PlayerModel::where('ID', '=', $monitor->CurPlayer)->select('Balance')->get()[0]->Balance + $machineCreditLog->Credit;
            PlayerModel::where('ID', '=', $monitor->CurPlayer)->update(['Balance' => $credit]);
            $machineCurStatus = MachineStatus::where('MachineID', '=', Input::get('ID'))->update(['CurCredit' =>'0', 'CurCoinIn' => $leftCredit]);
            $machineCreditLog->save();
            return Response::json(['done'=>'success', 'type'=>'ToCredit', 'credit'=>$credit, 'machineID'=>$monitor->ID, 't'=>floor($monitor->CurCoinIn / 100) * 100]);
        } elseif (Input::get('type') == 'ToCash') {
            $machineCreditLog->Operation = 2;
            $credit =  $machineCreditLog->Credit;
            $response = Response::json(['done'=>'success', 'type'=>'ToCash', 'credit'=>$credit, 'playerName'=>$monitor->Name,'machineID'=>$monitor->ID]);
            $machineCurStatus = MachineStatus::where('MachineID', '=', Input::get('ID'))->update(['CurCredit' =>'0', 'CurCoinIn' => $leftCredit]);
            $machineCreditLog->save();
            return $response;
        }
            return Response::json(['done'=>'unsuccess', 'type'=>'ToCredit', 'credit'=>0]);
    }

    public function GetVerificationCode()
    {
        $MachineStatus = MachineStatus::where('MachineID', '=', Input::get('id'))->get()[0];
        return $MachineStatus;
    }
    
    public function RefreshMachineStatus()
    {
        $machines = Monitor::all();
        return $machines;
    }

    public function GetDepositCredit()
    {
        $response = Monitor::where('ID', '=', Input::get('id'))->select('MaxDepositCredit', 'DepositCreditOnce', 'Cellphone', 'MachineName')->get()[0];
        return $response;
    }

    public function CheckCreditIn()
    {
        $player = PlayerModel::where('Cellphone', '=', Input::get('PlayerPhone'))->get();
        $machine = Monitor::where('ID', '=', Input::get('machineID')) ->get()[0];
        if (sizeof($player)==0) {
            return Response::json(['valid'=>'false', 'errMsg'=>'phone']);
        } elseif ($player[0]->Enable == 0) {
            return Response::json(['valid'=>'false', 'errMsg'=>'enable']);
        } elseif (Input::get('Credit') + $machine->CurCredit > $machine->MaxDepositCredit) {
            return Response::json(['valid'=>'false', 'errMsg'=>'creditToMore']);
        } elseif ($player[0]->Balance < Input::get('Credit')) {
            return Response::json(['valid'=>'false', 'errMsg'=>'creditNoEnough', 'id'=>$player[0]->ID]);
        }
            return Response::json(['valid'=>'true']);
    }

    public function RemoveReserved()
    {
        $monitor = Monitor::where('ID', '=', Input::get('ID'))->get()[0];
        $machineCreditLog = new MachineCreditLog;
        $machineCreditLog->OperatorID = Input::get('operatorID');
        $machineCreditLog->Credit = $monitor->CurCredit;
        $machineCreditLog->MachineID = $monitor->ID;
        $machineCreditLog->PlayerID = $monitor->CurPlayer;
        if (Input::get('type') == 'FreeMachine') {
            $machineCreditLog->Operation = 9;
            $credit = PlayerModel::where('ID', '=', $monitor->CurPlayer)->select('Balance')->get()[0]->Balance + $monitor->CurCredit + floor($monitor->CurCoinIn / $monitor->MinCoinOut) * $monitor->MinCoinOut;
            PlayerModel::where('ID', '=', $monitor->CurPlayer)->update(['Balance' => $credit]);
            $machineCurStatus = MachineStatus::where('MachineID', '=', Input::get('ID'))->update(['Status' => '0','CurCredit' =>'0', 'CurPlayer' =>'0','CurCoinIn'=>'0']);
            $machineCreditLog->save();
            return Response::json(['done'=>'success', 'type'=>'ToCredit', 'credit'=>$credit, 'machineID'=>$monitor->ID]);
        } elseif (Input::get('type') == 'RemoveReserved') {
            $machineCreditLog->Operation = 10;
            $response = Response::json(['done'=>'success']);
            $machineCurStatus = MachineStatus::where('MachineID', '=', Input::get('ID'))->update(['Status' => '1']);
            $machineCreditLog->save();
            return $response;
        }
            return Response::json(['done'=>'unsuccess', 'type'=>'ToCredit', 'credit'=>0]);
    }

    private function VerificationCode($id)
    {
        $code = mt_rand (1000, 9999);
        while (sizeof(MachineStatus::where('VerificationCode', '=', $code)->get()) != 0) {
            $code = mt_rand(1000, 9999);
        }
        MachineStatus::where('MachineID', '=', $id)->update(['VerificationCode' => $code]);
        return $code;
    }
}
