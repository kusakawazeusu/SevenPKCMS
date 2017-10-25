<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\CardType;
use Response;

class CardTypeController extends Controller
{
    //

	public function Index()
	{
		$numOfEntries = 1;
		return view('CardType.CardType',['numOfEntries'=>$numOfEntries]);
	}

	public function CardTypeDatas()
	{

		$cardTypes =  CardType::all();
		for($i=0;$i<count($cardTypes);++$i)
		{
			$cardTypeArr = explode(',',$cardTypes[$i]['CardType']);
			$CardType='';
			for($j = 0;$j<count($cardTypeArr);++$j)
				$CardType = $CardType.$this->NumToChar($cardTypeArr[$j]).',';
			$CardType = substr($CardType,0,-1);
			$cardTypes[$i]->CardType = $CardType;
		}
		return $cardTypes;
	}

	public function GetCardType($page,$num)
	{
		$offset = $num * $page;
		$numOfEntries = CardType::count();
		$cardTypes =  CardType::orderby('ID')
		->offset($offset)
		->limit($num)
		->get();
		for($i=0;$i<count($cardTypes);++$i)
		{
			$cardTypeArr = explode(',',$cardTypes[$i]['CardType']);
			$CardType='';
			for($j = 0;$j<count($cardTypeArr);++$j)
				$CardType = $CardType.$this->NumToChar($cardTypeArr[$j]).',';
			$CardType = substr($CardType,0,-1);
			$cardTypes[$i]->CardType = $CardType;
		}
		return Response::json(['cardTypes'=>$cardTypes,'numOfEntries'=>$numOfEntries]);
	}

	public function CreateCardType()
	{
		CardType::create([
			'CardType'=>Input::get('CardType')
			]);
		return 'CreateCardTypeSuccess';
	}

	public function CardTypeData()
	{
		$cardTypeData = CardType::where('ID','=',Input::get('ID'))->get()[0];
		$cardTypeArr = explode(',',$cardTypeData['CardType']);
		return Response::json(['ID'=>$cardTypeData['ID'],'CardType'=>$cardTypeArr]);
		return $cardTypeData;
	}

	public function UpdateCardType()
	{
		CardType::where('ID','=',Input::get('ID'))->update([
			'CardType'=>Input::get('CardType')]);
		return 'CardTypeUpdateSuccess';
	}

	private function NumToChar($number)
	{
		switch ($number) 
		{
			case 1:
			return 'A';
			case 11:
			return 'J';
			case 12:
			return 'Q';
			case 13:
			return 'K';
			case 14:
			return '隨機';
			case 15:
			return '一般';
			default:
			return $number;
		}
	}

	public function CheckDuplicateCardType()
	{
		if( CardType::where('CardType', Input::get('CardType'))->first())
			return Response::json(['valid'=>false]);
		return Response::json(['valid'=>true]);
	}

}
