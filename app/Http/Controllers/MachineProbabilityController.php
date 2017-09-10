<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Machine;
use App\MachineProbability;

class MachineProbabilityController extends Controller
{
    public function Index()
    {
        return view('Machine.MachineProbability');
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

        $machines = MachineProbability::join('machine', 'machineprobability.MachineID', '=', 'machine.ID')->where('AgentID', 'like', '%'.$SearchText.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $machines['count'] = $count;

        return Response::json($machines);
    }

    public function GetMachineByID()
    {
        $machineProbability = MachineProbability::join('machine', 'machineprobability.MachineID', '=', 'machine.ID')->where('ID', '=', Input::get('id'))->get()[0];
        return $machineProbability;
    }

    public function Update()
    {
        MachineProbability::where('MachineID', '=', Input::get('id'))
            ->update([
            'TwoPairs' => Input::get('TwoPairs'),
            'ThreeOfAKind' => Input::get('ThreeOfAKind'),
            'Straight' => Input::get('Straight'),
            'Flush' => Input::get('Flush'),
            'FullHouse' => Input::get('FullHouse'),
            'FourOfAKind' => Input::get('FourOfAKind'),
            'STRFlush' => Input::get('STRFlush'),
            'FiveOfAKind' => Input::get('FiveOfAKind'),
            'RoyalFlush' => Input::get('RoyalFlush'),
            'RealFourOfAKind' => Input::get('RealFourOfAKind'),
            'RealSTRFlush' => Input::get('RealSTRFlush'),
            'RealFiveOfAKind' => Input::get('RealFiveOfAKind'),
            'RealRoyalFlush' => Input::get('RealRoyalFlush'),
            'Turtle' => Input::get('Turtle'),
            'DoubleStar' => Input::get('DoubleStar'),
            'BonusDifficulty' => Input::get('BonusDifficulty'),
            'WildCard' => Input::get('WildCard'),
            'Water' => Input::get('Water')
            ]);
    }
}
