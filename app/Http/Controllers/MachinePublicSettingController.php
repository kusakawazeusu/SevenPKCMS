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
        return MachinePublicSettingModel::where('id', '=', 1)->get()[0]->$type;
    }
    
    public function Edit(){
        MachinePublicSettingModel::where('id', '=', 1)->update(['JokerWin' => Input::get('JokerWin')]);
    }
}
