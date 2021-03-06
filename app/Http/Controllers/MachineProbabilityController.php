<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Machine;
use App\MachineProbability;
use App\MachineProbabilityLogModel;
use App\ProbabilityAdjModel;
use App\BaseProbabilityModel;

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
        $MachineName = Input::get('MachineName');
        $count = Machine::where('AgentID', 'like', '%'.$SearchText.'%')->where('MachineName', 'like', '%'.$MachineName.'%') ->count();

        if ($ShowEntries == "ALL") {
            $ShowEntries = $count;
        }

        $machines = MachineProbability::join('machine', 'machineprobability.MachineID', '=', 'machine.ID')->join('agent', 'machine.AgentID', '=', 'agent.ID')->where('AgentID', 'like', '%'.$SearchText.'%')->where('MachineName', 'like', '%'.$MachineName.'%')->select('machine.*', 'agent.Name', 'machineprobability.*')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $machines['count'] = $count;

        return Response::json($machines);
    }

    public function GetMachineByID()
    {
        $machineProbability = MachineProbability::join('machine', 'machineprobability.MachineID', '=', 'machine.ID')->where('ID', '=', Input::get('id'))->get()[0];
        return $machineProbability;
    }

    public function GetProbabilityAdj(){        
        $prabilityAdjModel = ProbabilityAdjModel::All();
        return $prabilityAdjModel;
    }

    public function GetPaytable(){        
        $paytable = Machine::where('ID', '=', Input::get('id'))->get()[0];
        return $paytable;
    }

    public function GetBaseProbability(){   
        
        $baseProbability = BaseProbabilityModel::All();     
        return $baseProbability;
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
            'TurtleTime' => Input::get('TurtleTime'),
            'DoubleStar' => Input::get('DoubleStar'),
            'BonusDifficulty' => Input::get('BonusDifficulty'),
            'WildCard' => Input::get('WildCard'),
            'Water' => Input::get('Water')
            ]);
            
            MachineProbabilityLogModel::create([
            'MachineID' => Input::get('id'),
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
            'TurtleTime' => Input::get('TurtleTime'),
            'DoubleStar' => Input::get('DoubleStar'),
            'BonusDifficulty' => Input::get('BonusDifficulty'),
            'WildCard' => Input::get('WildCard'),
            'Water' => Input::get('Water')
            ]);
    }
}
