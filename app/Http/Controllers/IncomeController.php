<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index($year='')
    {
        return view('content.income');
    }
}

