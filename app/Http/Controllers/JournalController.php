<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index($year = '', $month = '')
    {
        return view('content.journal',);
    }
}
