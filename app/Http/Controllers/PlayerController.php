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

	public function CreatePlayer()
	{
		PlayerModel::create([
			'Name'=>Input::get('createName'), 
			'IDCardNumber'=>Input::get('createIDCardNumber'), 
			'Birthday'=>Input::get('createBirthday'), 
			'Gender'=>Input::get('createGender'), 
			'Cellphone'=>Input::get('createCellphone'), 
			'CardNumber'=>Input::get('createCardNumber'), 
			'IntroducerID'=>Input::get('createrIntroducerName'), 
			'Enable'=>Input::get('createEnable')]);
		return 'Success';

	}

	public function UpdatePlayer()
	{

	}

	public function DeletePlayer()
	{
        PlayerModel::where('ID',Input::get('ID'))->delete();

        return 'Success';
	}

	public function StorePhoto()
	{

	}

}
