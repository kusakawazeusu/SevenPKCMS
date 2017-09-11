<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Machine;
use App\MachineStatus;
use App\MachineProbability;

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
        $machineStatus = new MachineStatus;
        $machineStatus->MachineID = $newMachineID;
        $machineStatus->save();
        $machineProbability = new MachineProbability;
        $machineProbability->MachineID = $newMachineID;
        $machineProbability->save();
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

        $machines = Machine::where('AgentID', 'like', '%'.$SearchText.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
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
        return;
    }
}