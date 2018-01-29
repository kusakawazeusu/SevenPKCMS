<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

use App\Machine;
use App\MachineMeter;
use App\Playlog;
use DateTime;

class MachineMeterController extends Controller
{
    public function Index()
    {
        return view('Machine.MachineMeter');
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

        $machines = MachineMeter::join('machine', 'machinemeter.MachineID', '=', 'machine.ID')->where('AgentID', 'like', '%'.$SearchText.'%')->where('MachineName', 'like', '%'.$MachineName.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $machines['count'] = $count;

        return Response::json($machines);
    }

    public function Clean()
    {
        MachineMeter::where('MachineID', '=', Input::get('id'))
        ->update([
        'Games' => 0,
        'DoubleStar' => 0,
        'HighCard' => 0,
        'TwoPairs' => 0,
        'ThreeOfAKind' => 0,
        'Straight' => 0,
        'Flush' => 0,
        'FullHouse' => 0,
        'FourOfAKind' => 0,
        'RealFourOfAKind' => 0,
        'STRFlush' => 0,
        'RealSTRFlush' => 0,
        'FiveOfAKind' => 0,
        'RoyalFlush' => 0,
        'RealRoyalFlush' => 0,
        'BetCredit' => 0,
        'Credit' => 0,
        'RTP' => 0,
        'TotalCreditIn' => 0,
        'TotalCreditOut' => 0,
        'Throughput' => 0,
        'cleantime' => new DateTime()
        ]);
    }

    public function GetMachineMeterByID($id)
    {
        return view('Machine.MachineMeterByID', ['id'=>$id]);
    }

    public function GetTableDataByID()
    {
        $Page = Input::get('Page');
        $ShowEntries = Input::get('ShowEntries');
        $SearchText = Input::get('SearchText');

        $time = MachineMeter::where('MachineID', '=', Input::get('ID'))->get()[0]->cleantime;

        $query = Playlog::join('machine', 'playlog.MachineID', '=', 'machine.ID')->where('MachineID', '=', Input::get('ID'))->where('Created_at', '>=', $time)->orderBy('Created_at', 'desc');

        if (Input::get('StartTime')!=null && Input::get('EndTime')!=null) {
            $query = $query->where([
                    ['Created_at','>=',Input::get('StartTime').':00'],
                    ['Created_at','<=',Input::get('EndTime').' :59']
            ]);
        }

        $count = $query->count();

        if ($ShowEntries == "ALL") {
            $ShowEntries = $count;
        }

        $machines = $query->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $machines['count'] = $count;

        return Response::json($machines);
    }
}
