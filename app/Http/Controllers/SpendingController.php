<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Expense;
use Carbon\Carbon;

class SpendingController extends Controller
{
    public function index($year = '', $month = '')
    {
        $userId = auth()->user()->id;
        $now = Carbon::now()->locale('zh-tw');
        $thisYear = $now->format('Y');
        $thisMonth = $now->format('m');
        $year = !empty($year) ? $year : $thisYear;
        $month = !empty($month) ? $month : $thisMonth;
        $searchKey = $year . '-' . $month . '%';
        $query = Expense::where('user_id', $userId)->where('date', 'like', $searchKey);
        $allData = $query->get();
        $cashData = $allData->whereNull('bank');
        $cardData = $allData->whereNotNull('bank');
        $cashDataWithWeekDay = $this->addWeekDayToCollection($cashData);
        $cardDataWithWeekDay = $this->addWeekDayToCollection($cardData);


        $args = [
            'year' => $year,
            'month' => $month,
            'cashData' => $cashDataWithWeekDay,
            'cardData' => $cardDataWithWeekDay,
            'cashSum' => $cashData->sum('amount'),
            'cardSum' => $cardData->sum('actual_pay'),
            'blankCash' => 30 - ($cashData->count()),
            'blankCard' => 30 - ($cardData->count())
        ];

        return view('content.spending')->with('args', $args);
    }


    private function addWeekDayToCollection($data)
    {
        $weekDays = ['日', '一', '二', '三', '四', '五', '六'];
        $dataWithWeekDay = $data->map(function ($item) use ($weekDays) {
            $date = Carbon::parse($item->date);
            $weekDay = $weekDays[$date->dayOfWeek];
            $item->weekDay = $weekDay;
            return $item;
        });

        return $dataWithWeekDay;
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

        $validated = $validator->validated();
        auth()->user()->expenses()->create($validated);
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

        $validated = $validator->validated();
        auth()->user()->expenses()->create($validated);
        return redirect()->back();
    }
}
