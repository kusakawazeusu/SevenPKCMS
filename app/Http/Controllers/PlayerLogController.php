<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayerLogController extends Controller
{
    //
    public function Index()
    {

		$numOfEntries = 1;
		return view('Player.PlayerLog',['numOfEntries'=>$numOfEntries]);
    }
}
