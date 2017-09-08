<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\CardBuff;
use DB;
use Response;
use Carbon\Carbon;

class CardBuffController extends Controller
{
	//
	public function Index()
	{
		$numOfEntries = 1;
		return view('CardBuff.CardBuff',['numOfEntries'=>$numOfEntries]);
	}

	public function GetCardBuff($page,$num)
	{
		$offset = $num * $page;
		$numOfEntries = DB::table('CardBuffView')->count();
		$cardBuffs =  DB::table('CardBuffView')
		->orderby('ID')
		->offset($offset)
		->limit($num)
		->get();
		return Response::json(['cardBuffs'=>$cardBuffs,'numOfEntries'=>$numOfEntries]);
	}

	public function CreateCardBuff()
	{
		CardBuff::create([
			'CardTypeID'=>Input::get('CardTypeID'),
			'StartTime'=>Input::get('StartTime').':00',
			'EndTime'=>Input::get('EndTime').':59'
			]);
		return 'CreateSuccess';
	}

	public function GetCardBuffData()
	{
		$data = CardBuff::where('ID','=',Input::get('ID'))->get()[0];
		$data['StartTime'] =substr($data['StartTime'], 0, -3);
		$data['EndTime'] =substr($data['EndTime'], 0, -3);
		return $data;
	}

	public function UpdateCardBuff()
	{
		CardBuff::where('ID','=',Input::get('ID'))->update([
			'CardTypeID'=>Input::get('CardTypeID'),
			'StartTime'=>Input::get('StartTime').':00',
			'EndTime'=>Input::get('EndTime').':59'	
			]);
		return 'UpdateSuccess';
	}
}
