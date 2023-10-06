<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculationController extends Controller
{
   	
    public function calculate()
    {
   		return view('calculations.calculate');
    }
}
