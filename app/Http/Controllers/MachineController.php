<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Machine;
use App\MachineStatus;

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
    }

    public function GetTableData()
    {
        $Page = Input::get('Page');
        $ShowEntries = Input::get('ShowEntries');
        $SearchText = Input::get('SearchText');

        $count = Machine::where('MachineName', 'like', '%'.$SearchText.'%')->count();

        if ($ShowEntries == "ALL") {
            $ShowEntries = $count;
        }

        $machines = Machine::where('MachineName', 'like', '%'.$SearchText.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $machines['count'] = $count;

        return Response::json($machines);
    }
}
