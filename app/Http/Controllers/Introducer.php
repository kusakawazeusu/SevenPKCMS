<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

class Introducer extends Controller
{
    public function ShowIntroducer()
    {
        return view('Introducer.introducer');
    }

    public function getIntroducer( Request $request )
    {
        $Page = $request->input('Page');
        $ShowEntries = $request->input('ShowEntries');
        $SearchText = $request->input('SearchText');

        $count = DB::table('introducer')->where('IntroducerName','like','%'.$SearchText.'%')->count();

        if($ShowEntries == "ALL")
            $ShowEntries = $count;

        $introducers = DB::table('introducer')->where('IntroducerName','like','%'.$SearchText.'%')->limit($ShowEntries)->offset($Page*$ShowEntries)->get();
        $introducers['count'] = $count;

        return Response::json($introducers);
    }

    function createIntroducer(Request $request)
    {
        DB::table('introducer')->insert([
            'IntroducerName' => $request->input('Name'),
            'Gender' => $request->input('Gender'),
            'Address' => $request->input('Address'),
            'Cellphone' => $request->input('Cellphone'),
            'ReturnThreshold' => $request->input('BonusThreshold'),
            'ReturnCreditRate' => $request->input('BonusRate'),
            'CalcWeeks' => $request->input('BonusPeriod'),
            'Memo' => $request->input('Memo'),
            'Create_at' => date("Y-m-d H:i:s"),
        ]);

        return Response('Success',200);
    }

    function deleteIntroducer(Request $request)
    {
        DB::table('introducer')->where('id',$request->input('id'))->delete();
        return Response('OK',200);
    }

    function getIntroducerData(Request $request)
    {
        $data = DB::table('Introducer')->where('id',$request->input('id'))->first();
        return Response::json($data);
    }

    function updateIntroducer(Request $request)
    {
        DB::table('Introducer')->where('ID',$request->input('id'))->update([
            'IntroducerName' => $request->input('Name'),
            'Gender' => $request->input('Gender'),
            'Address' => $request->input('Address'),
            'Cellphone' => $request->input('Cellphone'),
            'ReturnThreshold' => $request->input('BonusThreshold'),
            'ReturnCreditRate' => $request->input('BonusRate'),
            'CalcWeeks' => $request->input('BonusPeriod'),
            'Memo' => $request->input('Memo')
        ]);

        return Response('Success',200);
    }
}
