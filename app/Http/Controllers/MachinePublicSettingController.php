<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\MachinePublicSettingModel;

class MachinePublicSettingController extends Controller
{

    public function Index()
    {
        $JokerWin = MachinePublicSettingModel::where('id', '=', 1)->get()[0]->JokerWin;
        return view('Machine.MachinePublicSetting',['JokerWin'=>$JokerWin]);
    }

    public function GetPublicSetting(){
        $type = Input::get('type');
        if($type == 'JokerWin')
            return MachinePublicSettingModel::where('id', '=', 1)->get()[0]->$type;
        else
            return MachinePublicSettingModel::where('id', '=', 1)->get()[0];
    }
    
    public function EditJokerWin(){
        MachinePublicSettingModel::where('id', '=', 1)->update(['JokerWin' => Input::get('JokerWin')]);
    }

    public function EditProbility(){
        MachinePublicSettingModel::where('id', '=', 1)->update([
            'TwoPairs' => Input::get('TwoPairs'),
            'ThreeOfAKind' => Input::get('ThreeOfAKind'),
            'Straight' => Input::get('Straight'),
            'Flush' => Input::get('Flush'),
            'FullHouse' => Input::get('FullHouse'),
            'FourOfAKind' => Input::get('FourOfAKind'),
            'STRFlush' => Input::get('STRFlush'),
            'FiveOfAKind' => Input::get('FiveOfAKind'),
            'RoyalFlush' => Input::get('RoyalFlush'),
            'Water' => Input::get('Water'),
            'DoubleStar' => Input::get('DoubleStar')
            ]);
    }
}