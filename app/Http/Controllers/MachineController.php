<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MachineController extends Controller
{
    public function Index()
    {
        return view('Machine.Machine');
    }
}
