<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Machine;
use App\MachineStatus;
use App\MachineProbability;
use App\MachineProbabilityLogModel;
use App\MachineMeter;
use App\AgentModel;
use App\defaultProbabilityModel;

class MachineController extends Controller
{
    public function Index()
    {
        return view('Machine.Machine');
    }

    public function Create()
    {
        $newMachineID = Machine::create([
            'AgentID' => Input::get('AgentID'),
            'MachineName' => Input::get('MachineName'),
            'IPAddress' => Input::get('IPAddress'),
            'SectionID' => Input::get('SectionID'),
            'MaxDepositCredit' => Input::get('MaxDepositCredit'),
            'DepositCreditOnce' => Input::get('DepositCreditOnce'),
            'MinCoinOut' => Input::get('MinCoinOut'),
            'MaxCoinIn' => Input::get('MaxCoinIn'),
            'CoinInOnce' => Input::get('CoinInOnce'),
            'CoinInBonus' => Input::get('CoinInBonus'),
            'TwoPairsOdd' => Input::get('TwoPairsOdd'),
            'ThreeOfAKindOdd' => Input::get('ThreeOfAKindOdd'),
            'StraightOdd' => Input::get('StraightOdd'),
            'FlushOdd' => Input::get('FlushOdd'),
            'FullHouseOdd' => Input::get('FullHouseOdd'),
            'FourOfAKindOdd' => Input::get('FourOfAKindOdd'),
            'STRFlushOdd' => Input::get('STRFlushOdd'),
            'FiveOfAKindOdd' => Input::get('FiveOfAKindOdd'),
            'RoyalFlushOdd' => Input::get('RoyalFlushOdd')
        ])->ID;
        $defaultProbability = defaultProbabilityModel::where('SectionID', '=', Input::get('SectionID'))->get()[0];
        $machineStatus = new MachineStatus;
        $machineStatus->MachineID = $newMachineID;
        $machineStatus->save();
        $machineProbability = new MachineProbability;
        $machineProbability->MachineID = $newMachineID;
        $machineProbability->TwoPairs = $defaultProbability->TwoPairs;
        $machineProbability->ThreeOfAKind = $defaultProbability->ThreeOfAKind;
        $machineProbability->Straight = $defaultProbability->Straight;
        $machineProbability->Flush = $defaultProbability->Flush;
        $machineProbability->FullHouse = $defaultProbability->FullHouse;
        $machineProbability->FourOfAKind = $defaultProbability->FourOfAKind;
        $machineProbability->STRFlush = $defaultProbability->STRFlush;
        $machineProbability->FiveOfAKind = $defaultProbability->FiveOfAKind;
        $machineProbability->RoyalFlush = $defaultProbability->RoyalFlush;
        $machineProbability->save();
        $machineMeter = new MachineMeter;
        $machineMeter->MachineID = $newMachineID;
        $machineMeter->save();
        $machineProbabilityLogModel = new MachineProbabilityLogModel;
        $machineProbabilityLogModel->MachineID = $newMachineID;
        $machineProbabilityLogModel->save();
    }

    public function GetTableData()
    {
        $Page = Input::get('Page');
        $ShowEntries = Input::get('ShowEntries');
        $SearchText = Input::get('SearchText');

        $count = Machine::where('AgentID', 'like', '%'.$SearchText.'%')->count();

        if ($ShowEntries == "ALL") {
            $ShowEntries = $count;
        }

        $machines = Machine::where('AgentID', 'like', '%'.$SearchText.'%')->join('agent', 'machine.AgentID', '=', 'agent.ID')->select('machine.*', 'agent.Name')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $machines['count'] = $count;

        return Response::json($machines);
    }

    public function GetMachineByID()
    {
        $machine = Machine::where('ID', '=', Input::get('id'))->get()[0];
        return $machine;
    }

    public function Update()
    {
        Machine::where('ID', '=', Input::get('id'))
            ->update([
            'AgentID' => Input::get('AgentID'),
            'MachineName' => Input::get('MachineName'),
            'SectionID' => Input::get('SectionID'),
            'MaxDepositCredit' => Input::get('MaxDepositCredit'),
            'DepositCreditOnce' => Input::get('DepositCreditOnce'),
            'MinCoinOut' => Input::get('MinCoinOut'),
            'MaxCoinIn' => Input::get('MaxCoinIn'),
            'CoinInOnce' => Input::get('CoinInOnce'),
            'CoinInBonus' => Input::get('CoinInBonus'),
            'TwoPairsOdd' => Input::get('TwoPairsOdd'),
            'ThreeOfAKindOdd' => Input::get('ThreeOfAKindOdd'),
            'StraightOdd' => Input::get('StraightOdd'),
            'FlushOdd' => Input::get('FlushOdd'),
            'FullHouseOdd' => Input::get('FullHouseOdd'),
            'FourOfAKindOdd' => Input::get('FourOfAKindOdd'),
            'STRFlushOdd' => Input::get('STRFlushOdd'),
            'FiveOfAKindOdd' => Input::get('FiveOfAKindOdd'),
            'RoyalFlushOdd' => Input::get('RoyalFlushOdd')
            ]);
    }

    public function Delete()
    {
        Machine::where('ID', Input::get('id'))->delete();
        MachineStatus::where('MachineID', Input::get('id'))->delete();
        MachineProbability::where('MachineID', Input::get('id'))->delete();
        MachineMeter::where('MachineID', Input::get('id'))->delete();
        return;
    }

    public function CheckExistAgentID()
    {
        $agent = AgentModel::where('ID', '=', Input::get('AgentID'))->get();
        if (sizeof($agent)==0) {
            return Response::json(['valid'=>false, 'errMsg'=>'查無此經銷商!']);
        }
        return Response::json(['valid'=>true, 'errMsg'=>'']);
    }

    public function CheckDepulicatedMachineName()
    {
        if (Input::get('Type') =='Machine/Create') {
            $machine = Machine::where('AgentID', '=', Input::get('AgentID'))->where('MachineName', '=', Input::get('MachineName'))->get();
        } else {
            $machine = Machine::where('AgentID', '=', Input::get('AgentID'))->where('MachineName', '=', Input::get('MachineName'))->where('ID', '<>', Input::get('ID'))->get();
        }
        if (sizeof($machine)!=0) {
            return Response::json(['valid'=>false, 'errMsg'=>'此經銷商有相同機台名稱!']);
        }
        return Response::json(['valid'=>true, 'errMsg'=>'']);
    }

    public function GetAgent()
    {
        return AgentModel::All();
    }
    
    public function FastCreate(){
        for($i = 60; $i < 100; $i++) {
            
        $newMachineID = Machine::create([
            'AgentID' => 2,
            'MachineName' => 1000+$i + 1,
            'SectionID' => 3,
            'MaxDepositCredit' => 10000000,
            'DepositCreditOnce' => 100000,
            'MinCoinOut' => 1000,
            'MaxCoinIn' => 10000,
            'CoinInOnce' => 1000,
            'CoinInBonus' => 200,
            'TwoPairsOdd' => 1,
            'ThreeOfAKindOdd' => 2,
            'StraightOdd' => 3,
            'FlushOdd' => 5,
            'FullHouseOdd' => 7,
            'FourOfAKindOdd' => 50,
            'STRFlushOdd' => 120,
            'FiveOfAKindOdd' => 200,
            'RoyalFlushOdd' => 500
        ])->ID;
        $machineStatus = new MachineStatus;
        $machineStatus->MachineID = $newMachineID;
        $machineStatus->save();
        $machineProbability = new MachineProbability;
        $machineProbability->MachineID = $newMachineID;
        $machineProbability->save();
        $machineMeter = new MachineMeter;
        $machineMeter->MachineID = $newMachineID;
        $machineMeter->save();
        $machineProbabilityLogModel = new MachineProbabilityLogModel;
        $machineProbabilityLogModel->MachineID = $newMachineID;
        $machineProbabilityLogModel->save();
        }
    }
}
