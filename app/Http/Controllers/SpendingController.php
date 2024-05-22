<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpendingController extends Controller
{
    public function index()
    {
        return view('content.spending');
    }

    public function createCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'shop' => 'required | string',
            'item' => 'required | string',
            'amount' => 'required | numeric',
            'refund' => 'nullable | numeric',
            'actual_pay' => 'nullable | numeric',
            'bank' => 'required | string',
            'notes' => 'nullable | string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('modalId', 'addCardModal')->withInput();
        }

        auth()->user()->expenses()->create($content);
        return redirect()->back();
    }



    public function createCash(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'item' => 'required | string',
            'amount' => 'required | numeric'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('modalId', 'addCashModal')->withInput();
        };

        auth()->user()->expenses()->create($content);
        return redirect()->back();
    }
}
