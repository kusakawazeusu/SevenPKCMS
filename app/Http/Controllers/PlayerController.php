<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\PlayerModel;
use App\PlayerAccModel;
use DB;
use Response;
use Hash;
use Carbon\Carbon;

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
		$query = DB::table('playerview')->where([
			['Name','LIKE','%'.$name.'%'],			
			['CardNumber','LIKE','%'.$cardNumber.'%']
			]);

		$numOfEntries = $query->count();
		$players = $query
		->orderby('ID')
		->offset($offset)
		->limit($num)
		->get();

		for($i=0;$i<count($players);++$i)
		{
			$players[$i]->IDCardNumber = substr($players[$i]->IDCardNumber, 0, -4).'****';
		}
		return Response::json(['players'=>$players,'numOfEntries'=>$numOfEntries]);
	}

	public function CreatePlayer()
	{

		$newID = PlayerModel::create([
			'Account'=>Input::get('Account'),
			'Password'=>Hash::make(Input::get('Password')),
			'Name'=>Input::get('Name'), 
			'IDCardNumber'=>Input::get('IDCardNumber'), 
			'Birthday'=>Input::get('Birthday'), 
			'Gender'=>Input::get('Gender'), 
			'Cellphone'=>Input::get('Account'),
			'IntroducerID'=>Input::get('IntroducerName'),//這裡的introducerName已經是ID 
			'Enable'=>Input::get('Enable'),
			'NickName'=>Input::get('NickName'),
			'Career'=>Input::get('Career'),
			'Address'=>Input::get('Address'),
			'Telephone'=>Input::get('Telephone'),
			'Marry'=>Input::get('Marry'),
			'Coming'=>Input::get('Coming'),
			'ReceiveAd'=>Input::get('ReceiveAd'),
			'CardType'=>'會員',
			'Memo'=>Input::get('Memo'),
			'LastLoginTime'=>Carbon::now()])->ID;
		PlayerModel::where('ID','=',$newID)->update(['CardNumber'=>$newID]);
		PlayerAccModel::create([
			'PlayerID'=>$newID]);
		return '新增會員成功！';

	}

	public function GetPlayerData()
	{
		$data = PlayerModel::where('ID','=',Input::get('ID'))->
		select('ID',
			'Account',
			'Password',
			'Name',
			'IDCardNumber',
			'Birthday',
			'Gender',
			'IntroducerID',
			'Enable',
			'NickName',
			'Career',
			'Address',
			'Telephone',
			'Marry',
			'Coming',
			'ReceiveAd',
			'Memo')->get()[0];
		$data['IntroducerName'] = DB::table('introducer')->where('ID','=',$data['IntroducerID'])->value('ID');
		return $data;
	}

	public function UpdatePlayer()
	{
		PlayerModel::where('ID','=',Input::get('ID'))->update([
			'Account'=>Input::get('Account'),
			'Name'=>Input::get('Name'),
			'IDCardNumber'=>Input::get('IDCardNumber'),
			'Birthday'=>Input::get('Birthday'),
			'Gender'=>Input::get('Gender'),
			'IntroducerID'=>Input::get('IntroducerName'),
			'Enable'=>Input::get('Enable'),
			'NickName'=>Input::get('NickName'),
			'Career'=>Input::get('Career'),
			'Address'=>Input::get('Address'),
			'Telephone'=>Input::get('Telephone'),
			'Marry'=>Input::get('Marry'),
			'Coming'=>Input::get('Coming'),
			'ReceiveAd'=>Input::get('ReceiveAd'),
			'Memo'=>Input::get('Memo')
			]);
		return '更新會員成功！';
	}

	public function DeletePlayer()
	{
		PlayerModel::where('ID',Input::get('ID'))->delete();
		PlayerAccModel::where('PlayerID',Input::get('ID'))->delete();

		return '刪除會員成功！';
	}

	public function CreatePhoto()
	{
		$query = PlayerModel::where('ID','=',Input::get('ID'));
		switch (Input::get('Type')) 
		{
			case 'Front':
			$img = $query->update(['DocumentFront'=>Input::get('photo')]);
			break;
			case 'Back':
			$img = $query->update(['DocumentBack'=>Input::get('photo')]);
			break;
			case 'Photo':
			$img = $query->update(['Photo'=>Input::get('photo')]);
			break;
		}
	}

	public function CheckPhoto()
	{
		$query = PlayerModel::where('ID','=',Input::get('ID'));
		switch (Input::get('Type')) {
			case 'Front':
			$img = $query->value('DocumentFront');
			break;
			case 'Back':
			$img = $query->value('DocumentBack');
			break;
			case 'Photo':
			$img = $query->value('Photo');
			break;
		}
		if($img!=null)
			return Response::json(['valid' => false ,'Photo'=>$img]);
		else
			return Response::json(['valid'=>true,'Photo'=>null]);
	}

	public function Deposit()
	{
		PlayerModel::where('ID','=',Input::get('ID'))->increment('Balance',Input::get('credit'));
		return '儲值成功！';
	}

	public function CheckPassword()
	{
		$password = PlayerModel::where('ID','=',Input::get('ID'))->value('Password');
		if(Hash::check(Input::get('Password'),$password))
			return Response::json(['valid'=>true]);
		return Response::json(['valid'=>false]);
	}

	public function UpdatePassword()
	{
		PlayerModel::where('ID','=',Input::get('ID'))->update([
			'Password'=>Hash::make(Input::get('Password'))
			]);
		return '變更密碼成功！';
	}

	public function CheckDepulicatedAccount()
	{
		if( PlayerModel::where('Account', Input::get('Account'))->first())
			return Response::json(['valid'=>false]);
		return Response::json(['valid'=>true]);
	}

	public function GetIntroducerName()
	{
		$introducerName = DB::table('introducer')->select('ID','IntroducerName')->get();
		return $introducerName;
	}

}
