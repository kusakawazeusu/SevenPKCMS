<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\PlayerModel;
use DB;
use Response;
use Hash;

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
			['CardNumber','LIKE','%'.$cardNumber.'%']
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
			'Account'=>Input::get('Account'),
			'Password'=>Hash::make(Input::get('Password')),
			'Name'=>Input::get('Name'), 
			'IDCardNumber'=>Input::get('IDCardNumber'), 
			'Birthday'=>Input::get('Birthday'), 
			'Gender'=>Input::get('Gender'), 
			'Cellphone'=>Input::get('Account'),
			'IntroducerID'=>DB::table('introducer')->where('IntroducerName','=',Input::get('IntroducerName'))->value('ID'), 
			'Enable'=>Input::get('Enable'),
			'NickName'=>Input::get('NickName'),
			'Career'=>Input::get('Career'),
			'Address'=>Input::get('Address'),
			'Telephone'=>Input::get('Telephone'),
			'Marry'=>Input::get('Marry'),
			'Coming'=>Input::get('Coming'),
			'ReceiveAd'=>Input::get('ReceiveAd'),
			'CardNumber'=>PlayerModel::max('id')+1,
			'Memo'=>Input::get('Memo')]);
		return 'Success';

	}

	public function GetPlayerData()
	{
		
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
