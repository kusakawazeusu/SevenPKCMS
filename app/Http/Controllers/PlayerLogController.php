<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;
use App\PlayerAccModel;
use App\PlayerModel;
use DB;
use Excel;

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

	public function GetPlayerLogByID($playerID)
	{
		return view('Player.PlayerLogByID',['playerID'=>$playerID]);
	}

	public function GetPlayerLogDataByID($page,$num)
	{
		$query = DB::table('playerlogbyidview')->where('PlayerID','=',Input::get('playerID'));
		if(Input::get('StartTime')!=null && Input::get('EndTime')!=null)
			{
				$query = $query->where([
					['Created_at','>=',Input::get('StartTime').' 00:00:00'],
					['Created_at','<=',Input::get('EndTime').' 23:59:59']
				]);
			}
			$offset = $num * $page;
			$numOfEntries = $query->count();
			$playerLogDatasByID = $query->orderby('Created_at','desc')
			->offset($offset)
			->limit($num)
			->get();
			return Response::json(['playerLogDatasByID'=>$playerLogDatasByID,'numOfEntries'=>$numOfEntries]);
		}

		public function Export($playerID)
		{
			return $this->MakeExcel($playerID,null,null);
		}

		public function ExportByTime($playerID,$StartTime,$EndTime)
		{
			return $this->MakeExcel($playerID,$StartTime,$EndTime);
		}

		public function MakeExcel($playerID,$StartTime,$EndTime)
		{
			$query = DB::table('playerlogbyidview')->where('PlayerID','=',$playerID);
			$Account = PlayerModel::where('ID','=',$playerID)->value('Account');
			if($StartTime!=null && $EndTime!=null)
			{
				$query = $query->where([
					['Created_at','>=',$StartTime.' 00:00:00'],
					['Created_at','<=',$EndTime.' 23:59:59']
				]);
			}
			$LogDatas = $query->orderby('Created_at','desc')->get();

		//return $LogDatas;


			return Excel::create(iconv('UTF-8', 'BIG5', $Account.'_帳務資訊'),function($excel) use ($LogDatas,$Account)
			{
				$excel->setTitle($Account.'_帳務資訊');
				$excel->sheet($Account.'_帳務資訊', function($sheet) use ($LogDatas)
				{
					$sheet->row(1,['編號','會員名稱','機台編號','分區編號','押注金額','牌型名稱','牌型贏得倍率','比被次數','比倍贏得倍率','JP贏錢金額','總贏錢金額','建立時間']);
					$sheet->freezeFirstRow();

					$sheet->cells('A:L', function($cells) {
						$cells->setAlignment('right');
					});	
					$sheet->cells('A1:L1', function($cells) {
						$cells->setAlignment('center');
					});
					$sheet->setWidth(array(
						'A'     =>  10,
						'B'     =>  10,
						'C'     =>  10,
						'D'     =>  10,
						'E'     =>  15,
						'F'     =>  10,
						'G'     =>  15,
						'H'     =>  15,
						'I'     =>  15,
						'J'     =>  15,
						'K'     =>  15,
						'L'     =>  20,

					));
					$LogDatasLength = count($LogDatas);
					for($i=0;$i<count($LogDatas);++$i)
					{
						$CardResultRate;
						$CardResult;
						switch($LogDatas[$i]->GameResult)
						{
							case 0:
							{
								$CardResult = '沒中';
								$CardResultRate=0;
							}
							break;
							case 1:
							{
								$CardResult = '同花大順';
								$CardResultRate=500;
							}
							break;
							case 2:
							{
								$CardResult = '五梅';
								$CardResultRate=200;
							}
							break;
							case 3:
							{
								$CardResult = '同花順';
								$CardResultRate=120;
							}
							break;
							case 4:
							{
								$CardResult = '四梅';
								$CardResultRate=50;
							}
							break;
							case 5:
							{
								$CardResult = '葫蘆';
								$CardResultRate=7;
							}
							break;
							case 6:
							{
								$CardResult = '同花';
								$CardResultRate=5;
							}
							break;
							case 7:
							{
								$CardResult = '順子';
								$CardResultRate=3;
							}
							break;
							case 8:
							{
								$CardResult = '三條';
								$CardResultRate=2;
							}
							break;
							case 9:
							{
								$CardResult = '大兩對';
								$CardResultRate=1;
							}
							break;
						}
						if($LogDatas[$i]->DoubleStar=='1')
						{
							$CardResultRate *= 2;
						}
						$sheet->appendRow([
							count($LogDatas)-$i,
							$LogDatas[$i]->Name,
							$LogDatas[$i]->MachineName,
							$LogDatas[$i]->SectionID,
							number_format($LogDatas[$i]->Credit),
							$CardResult,
							number_format($CardResultRate),
							0,
							number_format($LogDatas[$i]->BonusRate),
							number_format($LogDatas[$i]->Jackpot),
							number_format($LogDatas[$i]->WinCredit),
							$LogDatas[$i]->Created_at
						]);
					}
					/*foreach ($LogDatas as $LogData) 
					{
						$CardResultRate;
						$CardResult;
						switch($LogData->GameResult)
						{
							case 0:
							{
								$CardResult = '沒中';
								$CardResultRate=0;
							}
							break;
							case 1:
							{
								$CardResult = '同花大順';
								$CardResultRate=500;
							}
							break;
							case 2:
							{
								$CardResult = '五梅';
								$CardResultRate=200;
							}
							break;
							case 3:
							{
								$CardResult = '同花順';
								$CardResultRate=120;
							}
							break;
							case 4:
							{
								$CardResult = '四梅';
								$CardResultRate=50;
							}
							break;
							case 5:
							{
								$CardResult = '葫蘆';
								$CardResultRate=7;
							}
							break;
							case 6:
							{
								$CardResult = '同花';
								$CardResultRate=5;
							}
							break;
							case 7:
							{
								$CardResult = '順子';
								$CardResultRate=3;
							}
							break;
							case 8:
							{
								$CardResult = '三條';
								$CardResultRate=2;
							}
							break;
							case 9:
							{
								$CardResult = '大兩對';
								$CardResultRate=1;
							}
							break;
						}
						if($LogData->DoubleStar=='1')
						{
							$CardResultRate *= 2;
						}
						$sheet->appendRow([
							$LogData->ID,
							$LogData->Name,
							$LogData->MachineName,
							$LogData->SectionID,
							number_format($LogData->Credit),
							$CardResult,
							number_format($CardResultRate),
							0,
							number_format($LogData->BonusRate),
							number_format($LogData->Jackpot),
							number_format($LogData->WinCredit),
							$LogData->Created_at
						]);
					}*/
				});

			})->export('xlsx');
		}

	}
