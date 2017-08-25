<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\PlayerModel;
use Response;

class PlayerController extends Controller
{
    //
    public function Index()
    {
    	$numOfEntries = 1;
    	return view('Player.Player',['numOfEntries'=>$numOfEntries]);
    }

    public function GetPlayer($page,$num)
    {

    	//return 'GetPlayer';


    	$offset = $num * $page;
		$name = Input::get('name');
		$cardNumber = Input::get('cardNumber');
		$query = PlayerModel::where([
			['Name','LIKE','%'.$name.'%'],			
			['cardNumber','LIKE','%'.$cardNumber.'%']
			]);

		$numOfEntries = $query->count();
		$players = $query
		->orderby('ID')
		->offset($offset)
		->limit($num)
		->get();
		return Response::json(['players'=>$players,'numOfEntries'=>$numOfEntries]);
    }

    public function AddPlayer()
    {

    }

    public function EditPlayer()
    {

    }

    public function RemovePlayer()
    {

    }

    public function StorePhoto()
    {

    }

}
