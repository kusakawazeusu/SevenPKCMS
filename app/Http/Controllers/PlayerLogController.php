<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;
use App\PlayerAccModel;
use DB;

class PlayerLogController extends Controller
{
    //
    public function Index()
    {

		$numOfEntries = 1;
		return view('Player.PlayerLog',['numOfEntries'=>$numOfEntries]);
    }

    public function GetPlayerLog($page,$num)
    {
    	$offset = $num * $page;
		$name = Input::get('Name');

		$query = DB::table('playerlogview')->where([
			['Name','LIKE','%'.$name.'%'],
			]);

		$numOfEntries = $query->count();
		$playerLogs = $query
		->orderby('PlayerID')
		->offset($offset)
		->limit($num)
		->get();
		return Response::json(['playerLogs'=>$playerLogs,'numOfEntries'=>$numOfEntries]);
    }

    public function GetPlayerLogByID($ID)
    {
		return view('Player.PlayerLogByID',['ID'=>$ID]);
    }

    public function GetPlayerLogDataByID($page,$num)
    {
		$query = DB::table('playerlogbyidview')->where('PlayerID','=',Input::get('ID'));
    	if(Input::get('StartTime')!=null && Input::get('EndTime')!=null)
    	{
    		$query = $query->where([
    			['Created_at','>=',Input::get('StartTime').' 00:00:00'],
    			['Created_at','<=',Input::get('EndTime').' 23:59:59']
    			]);
    	}

    	$offset = $num * $page;
		$numOfEntries = $query->count();
		$playerLogDatasByID = $query
		->orderby('ID')
		->offset($offset)
		->limit($num)
		->get();
		return Response::json(['playerLogDatasByID'=>$playerLogDatasByID,'numOfEntries'=>$numOfEntries]);
    }
}
