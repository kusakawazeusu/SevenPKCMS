<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CardType;

class CardTypeController extends Controller
{
    //

    public function CardTypeData()
    {
    	return CardType::all();
    }
}
