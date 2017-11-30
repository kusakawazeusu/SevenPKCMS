<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MachinePublicSettingController extends Controller
{

    public function Index()
    {
        return view('Machine.MachinePublicSetting');
    }
}
