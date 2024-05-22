<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpendingController extends Controller
{
    public function index()
    {
        return view('content.spending');
    }
}
