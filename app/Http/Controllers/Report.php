<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class Report extends Controller
{
    public function SingleSessionReport()
    {
        return view('Report.SingleSessionReport');
    }

    public function getSingleSessionReport(Request $request)
    {
        if( $request->input('EndTime') )
        {
            $count = DB::table('session')
            ->join('Operator','session.OperatorID','=','Operator.id')
            ->whereDate('StartTime','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->whereDate('EndTime','<=',$request->input('EndTime'))
            ->where('Operator.Name','like','%'.$request->input('SearchName').'%')
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;      

            $data = DB::table('session')
            ->join('Operator','session.OperatorID','=','Operator.id')
            ->select('session.*','Operator.Name as OperatorName')
            ->where('StartTime','>=',$request->input('StartTime') ? $request->input('StartTime').' 00:00:00' : '%')
            ->where('EndTime','<=',$request->input('EndTime').' 23:59:59')
            ->where('Operator.Name','like','%'.$request->input('SearchName').'%')
            ->orderBy('StartTime','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();

        }
        else
        {
            $count = DB::table('session')
            ->join('Operator','session.OperatorID','=','Operator.id')
            ->whereDate('StartTime','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->where('Operator.Name','like','%'.$request->input('SearchName').'%')
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;

            $data = DB::table('session')
            ->join('Operator','session.OperatorID','=','Operator.id')
            ->select('session.*','Operator.Name as OperatorName')
            ->whereDate('StartTime','>=',$request->input('StartTime') ? $request->input('StartTime').' 00:00:00' : '%')
            ->where('Operator.Name','like','%'.$request->input('SearchName').'%')
            ->orderBy('StartTime','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();


        }

        $data['count'] = $count;

        return Response::json($data);
    }

    public function getSingleSessionReportByid(Request $request)
    {
        $data = DB::table('session')
        ->where('session.ID',$request->input('id'))
        ->join('Operator','session.OperatorID','=','Operator.id')
        ->select('session.*','Operator.Name as OperatorName')
        ->get();

        return Response::json($data);
    }

    public function DaySessionReport()
    {
        return view('Report.DaySessionReport');
    }

    public function RegenerateDaySessionReport()
    {
        //把單日資料抓出來
        DB::table('singledayreport')->truncate();

        $Date = DB::table('session')->select('StartTime')->get();
        $DateArray = array();

        for( $i=0; $i<count($Date); $i++ )
        {
            array_push( $DateArray, substr($Date[$i]->StartTime,0,10) );
        }

        $DateArray = array_unique($DateArray);

        foreach( $DateArray as &$SingleDay )
        {
            DB::table('singledayreport')->insert([
                'Date' => $SingleDay,
                'CreditIn' => DB::table('session')->whereDate('StartTime',$SingleDay)->sum('TotalCreditIn'),
                'CreditOut' => DB::table('session')->whereDate('StartTime',$SingleDay)->sum('TotalCreditOut'),
                'Throughput' => DB::table('session')->whereDate('StartTime',$SingleDay)->sum('Throughput'),
                'create_at' => date("Y-m-d H:i:s")
            ]);
        }

        return response(count($DateArray), 200);
    }

    public function SyncDaySessionReport()
    {
        //把單日資料抓出來

        $Date = DB::table('session')->select('StartTime')->orderBy('StartTime','desc')->get();
        $DateArray = array();

        for( $i=0; $i<count($Date); $i++ )
        {
            $ThisDate = substr($Date[$i]->StartTime,0,10);

            if( !DB::table('singledayreport')->where('Date',$ThisDate)->first() )
            {
                array_push( $DateArray, $ThisDate );
            }
            else
            {
                break;
            }
                
        }

        $DateArray = array_unique($DateArray);

        foreach( $DateArray as &$SingleDay )
        {
            DB::table('singledayreport')->insert([
                'Date' => $SingleDay,
                'CreditIn' => DB::table('session')->whereDate('StartTime',$SingleDay)->sum('TotalCreditIn'),
                'CreditOut' => DB::table('session')->whereDate('StartTime',$SingleDay)->sum('TotalCreditOut'),
                'Throughput' => DB::table('session')->whereDate('StartTime',$SingleDay)->sum('Throughput'),
                'create_at' => date("Y-m-d H:i:s")
            ]);
        }

        return response::json($DateArray);
    }

    public function getSingleDayReport( Request $request )
    {
        if( $request->input('EndTime') )
        {
            $count = DB::table('singledayreport')
            ->whereDate('Date','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->whereDate('Date','<=',$request->input('EndTime'))
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;      

            $data = DB::table('singledayreport')
            ->where('Date','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->where('Date','<=',$request->input('EndTime'))
            ->orderBy('Date','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();

        }
        else
        {
            $count = DB::table('singledayreport')
            ->whereDate('Date','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;

            $data = DB::table('singledayreport')
            ->whereDate('Date','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->orderBy('Date','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();


        }

        $data['count'] = $count;

        return Response::json($data);
    }

    public function MonthReport()
    {
        return view('Report.MonthReport');
    }

    public function RegenerateMonthReport()
    {
        //把單日資料抓出來
        DB::table('monthreport')->truncate();

        $Date = DB::table('session')->select('StartTime')->get();
        $MonthArray = array();

        for( $i=0; $i<count($Date); $i++ )
        {
            array_push( $MonthArray, substr($Date[$i]->StartTime,0,7) );
        }

        $MonthArray = array_unique($MonthArray);

        foreach( $MonthArray as &$SingleMonth )
        {
            $DateClip = explode('-',$SingleMonth);
            $Year = $DateClip[0];
            $Month = $DateClip[1];

            DB::table('monthreport')->insert([
                'Date' => $SingleMonth.'-01',
                'CreditIn' => DB::table('session')->whereYear('StartTime',$Year)->whereMonth('StartTime',$Month)->sum('TotalCreditIn'),
                'CreditOut' => DB::table('session')->whereYear('StartTime',$Year)->whereMonth('StartTime',$Month)->sum('TotalCreditOut'),
                'Throughput' => DB::table('session')->whereYear('StartTime',$Year)->whereMonth('StartTime',$Month)->sum('Throughput'),
                'create_at' => date("Y-m-d H:i:s")
            ]);
        }

        return response(count($MonthArray), 200);
    }

    public function SyncMonthReport()
    {
        //把單日資料抓出來

        $Date = DB::table('session')->select('StartTime')->orderBy('StartTime','desc')->get();
        $MonthArray = array();

        for( $i=0; $i<count($Date); $i++ )
        {
            $ThisDate = substr($Date[$i]->StartTime,0,7);

            if( !DB::table('monthreport')->where('Date',$ThisDate.'-01')->first() )
            {
                array_push( $MonthArray, $ThisDate );
            }
            else
            {
                break;
            }
        }

        $MonthArray = array_unique($MonthArray);

        foreach( $MonthArray as &$SingleMonth )
        {
            $DateClip = explode('-',$SingleMonth);
            $Year = $DateClip[0];
            $Month = $DateClip[1];

            DB::table('monthreport')->insert([
                'Date' => $SingleMonth.'-01',
                'CreditIn' => DB::table('session')->whereYear('StartTime',$Year)->whereMonth('StartTime',$Month)->sum('TotalCreditIn'),
                'CreditOut' => DB::table('session')->whereYear('StartTime',$Year)->whereMonth('StartTime',$Month)->sum('TotalCreditOut'),
                'Throughput' => DB::table('session')->whereYear('StartTime',$Year)->whereMonth('StartTime',$Month)->sum('Throughput'),
                'create_at' => date("Y-m-d H:i:s")
            ]);
        }

        return response::json($MonthArray);
    }

    public function getMonthReport( Request $request )
    {
        if( $request->input('EndTime') )
        {
            $count = DB::table('monthreport')
            ->whereDate('Date','>=',$request->input('StartTime') ? $request->input('StartTime').'-01' : '%')
            ->whereDate('Date','<=',$request->input('EndTime').'-01')
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;      

            $data = DB::table('monthreport')
            ->where('Date','>=',$request->input('StartTime') ? $request->input('StartTime').'-01' : '%')
            ->where('Date','<=',$request->input('EndTime').'-01')
            ->orderBy('Date','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();

        }
        else
        {
            $count = DB::table('monthreport')
            ->whereDate('Date','>=',$request->input('StartTime') ? $request->input('StartTime').'-01' : '%')
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;

            $data = DB::table('monthreport')
            ->whereDate('Date','>=',$request->input('StartTime') ? $request->input('StartTime').'-01' : '%')
            ->orderBy('Date','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();

        }

        $data['count'] = $count;

        return Response::json($data);
    }

    public function PlayerBetReport()
    {
        return view('Report.PlayerBetReport');
    }

    public function getPlayerBetReport(Request $request)
    {
        if( $request->input('EndTime') )
        {
            $count = DB::table('machinecreditlog')
            ->join('player','machinecreditlog.PlayerID','=','player.id')
            ->join('introducer','player.IntroducerID','=','introducer.id')
            ->whereDate('Create_at','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->whereDate('Create_at','<=',$request->input('EndTime'))
            ->where('player.Name','like','%'.$request->input('SearchName').'%')
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;      

            $data = DB::table('machinecreditlog')
            ->join('player','machinecreditlog.PlayerID','=','player.id')
            ->join('introducer','player.IntroducerID','=','introducer.id')
            ->select('machinecreditlog.*','player.Name as PlayerName','introducer.IntroducerName as IntroducerName')
            ->where('Create_at','>=',$request->input('StartTime') ? $request->input('StartTime').' 00:00:00' : '%')
            ->where('Create_at','<=',$request->input('EndTime').' 23:59:59')
            ->where('player.Name','like','%'.$request->input('SearchName').'%')
            ->orderBy('Create_at','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();

        }
        else
        {
            $count = DB::table('machinecreditlog')
            ->join('player','machinecreditlog.PlayerID','=','player.id')
            ->join('introducer','player.IntroducerID','=','introducer.id')
            ->whereDate('Create_at','>=',$request->input('StartTime') ? $request->input('StartTime') : '%')
            ->where('player.Name','like','%'.$request->input('SearchName').'%')
            ->count();

            if($request->input('ShowEntries') != 'ALL')
                $ShowEntries = $request->input('ShowEntries');
            else
                $ShowEntries = $count;      

            $data = DB::table('machinecreditlog')
            ->join('player','machinecreditlog.PlayerID','=','player.id')
            ->join('introducer','player.IntroducerID','=','introducer.id')
            ->select('machinecreditlog.*','player.Name as PlayerName','introducer.IntroducerName as IntroducerName')
            ->where('Create_at','>=',$request->input('StartTime') ? $request->input('StartTime').' 00:00:00' : '%')
            ->where('player.Name','like','%'.$request->input('SearchName').'%')
            ->orderBy('Create_at','desc')
            ->limit($ShowEntries)
            ->offset($ShowEntries * $request->input('Page'))
            ->get();
        }

        $data['count'] = $count;

        return Response::json($data);
    }
}
