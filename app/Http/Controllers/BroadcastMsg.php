<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class BroadcastMsg extends Controller
{
    public function MsgSetting()
    {
        $msg = DB::table('broadcastmsg')->where('id','1')->first();

        return view('BroadcastMsg',['msg'=>$msg]);
    }

    public function SetMsg(Request $request)
    {
        DB::table('broadcastmsg')->where('id','1')->update([
            'msg1' => $request->input('Msg1'),
            'msg2' => $request->input('Msg2'),
            'msg3' => $request->input('Msg3'),
            'msg4' => $request->input('Msg4'),
            'msg5' => $request->input('Msg5'),
            'starttime1' => $request->input('Msg1StartTime'),
            'starttime2' => $request->input('Msg2StartTime'),
            'starttime3' => $request->input('Msg3StartTime'),
            'starttime4' => $request->input('Msg4StartTime'),
            'starttime5' => $request->input('Msg5StartTime'),
            'endtime1' => $request->input('Msg1EndTime'),
            'endtime2' => $request->input('Msg2EndTime'),
            'endtime3' => $request->input('Msg3EndTime'),
            'endtime4' => $request->input('Msg4EndTime'),
            'endtime5' => $request->input('Msg5EndTime'),
        ]);

        $msg = DB::table('broadcastmsg')->where('id','1')->first();

        return view('BroadcastMsg',['msg'=>$msg]);
    }
    
}
