<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class PlayerController extends Controller
{
    //
    public function Index()
    {
    	return view('Player.player');
    }
}
