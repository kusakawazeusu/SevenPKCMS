<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class Agent extends Controller
{
    public function ShowAgent()
    {
        return view('Agent.agent');
    }

    public function getAgent( Request $request )
    {
        $Page = $request->input('Page');
        $ShowEntries = $request->input('ShowEntries');
        $SearchText = $request->input('SearchText');

        $count = DB::table('Agent')->where('Name','like','%'.$SearchText.'%')->count();

        if($ShowEntries == "ALL")
            $ShowEntries = $count;

        $Agents = DB::table('Agent')->where('Name','like','%'.$SearchText.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $Agents['count'] = $count;

        return Response::json($Agents);
    }

    function createAgent(Request $request)
    {
        DB::table('Agent')->insert([
            'Name' => $request->input('Name'),
            'Gender' => $request->input('Gender'),
            'Cellphone' => $request->input('Cellphone'),
            'DiscountRate' => $request->input('DiscountRate'),
            'Create_at' => date("Y-m-d H:i:s"),
        ]);

        return Response('Success',200);
    }

    function deleteAgent(Request $request)
    {
        DB::table('Agent')->where('id',$request->input('id'))->delete();
        return Response('OK',200);
    }

    function getAgentData(Request $request)
    {
        $data = DB::table('Agent')->where('id',$request->input('id'))->first();
        return Response::json($data);
    }

    function updateAgent(Request $request)
    {
        DB::table('Agent')->where('ID',$request->input('id'))->update([
            'Name' => $request->input('Name'),
            'Gender' => $request->input('Gender'),
            'Cellphone' => $request->input('Cellphone'),
            'DiscountRate' => $request->input('DiscountRate')
        ]);

        return Response('Success',200);
    }

    function manipulateCredit(Request $request)
    {
        if($request->input('manipulation') == 'increase')
        {
            DB::table('Agent')->where('ID',$request->input('id'))->increment('Credit',$request->input('credit'));
            DB::table('Agent')->where('ID',$request->input('id'))->increment('OweCredit',$request->input('OweCredit'));

            DB::table('AgentCreditLog')->insert([
                'AgentID' => $request->input('id'),
                'Operate' => 0,
                'Credit' => $request->input('credit'),
                'Status' => 1,
                'Create_at' => date("Y-m-d H:i:s"),
                'OperatorID' => $request->input('operatorID')
            ]);
        }
        else if($request->input('manipulation') == 'decrease')
        {
            DB::table('Agent')->where('ID',$request->input('id'))->decrement('Credit',$request->input('credit'));

            DB::table('AgentCreditLog')->insert([
                'AgentID' => $request->input('id'),
                'Operate' => 1,
                'Credit' => $request->input('credit'),
                'Status' => 1,
                'Create_at' => date("Y-m-d H:i:s"),
                'OperatorID' => $request->input('operatorID')
            ]);
        }
        else if($request->input('manipulation') == 'clear')
        {
            $originalCredit = DB::table('Agent')->where('ID',$request->input('id'))->first()->OweCredit;

            DB::table('Agent')->where('ID',$request->input('id'))->update(['OweCredit'=>0]);
            DB::table('AgentCreditLog')->insert([
                'AgentID' => $request->input('id'),
                'Operate' => 2,
                'Credit' => $originalCredit,
                'Status' => 1,
                'Create_at' => date("Y-m-d H:i:s"),
                'OperatorID' => $request->input('operatorID')
            ]);
        }

        return Response('Success',200);
    }

    function getCreditLog(Request $request)
    {
        $data = DB::table('agentcreditlog')->where('AgentID',$request->input('id'))->join('operator','agentcreditlog.operatorid','=','operator.id')->orderBy('Create_at','DESC')->get();
        return Response::json($data);
    }
}
