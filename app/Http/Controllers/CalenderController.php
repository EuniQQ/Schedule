<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Style;
use App\Models\Calender;
use App\Models\Income;
use App\Models\Expense;


class CalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($year = null, $month = null)
    {
        $now = Carbon::now()->locale('zh-tw');
        $thisYear = $now->year;
        $thisMonth = $now->month;
        $today = $now->format("d");
        // 月份改成有前導0
        $formatMonth = !is_null($month) ? $month : $now->format('m');
        if (is_null($year) && is_null($month)) {
            $year = $thisYear;
            $month = $thisMonth;
        } elseif (is_null($year) && !is_null($month)) {
            $year = $thisYear;
        }

        $hebrewYear = 5783 + ($year - 2024);

        // 其他參數
        $finance = $this->getFinance($year, $month);
        $style =  $this->getStyle($year, $month);
        $calender = $this->getCalender($year, $formatMonth);
        $res = [
            'year' => $year,
            'hebrewYear' => $hebrewYear,
            'month' => $month,
            'thisMonth' => $thisMonth,
            'today' => $today,
            'income' => '$' . $finance[0],
            'expense' => '$' . $finance[1],
            'balance' => '$' . $finance[0] - $finance[1] ?? '',
            'style' => $style,
            'calender' => $calender,
        ];

        return view('content.calender', $res);
    }


    /* 取得財務 */
    protected function getFinance($year, $month)
    {
        $monthKey = $year . '-' . $month . '%';
        $income =  Income::where('user_id', auth()->user()->id)
            ->where('date', 'like', $monthKey)
            ->pluck('amount')
            ->sum() ?? "";

        $expense = Expense::where('user_id', auth()->user()->id)
            ->where('date', 'like', $monthKey)
            ->pluck('amount')
            ->sum() ?? "";

        return [$income, $expense];
    }


    /* 取得視覺 */
    protected function getStyle($year, $month)
    {
        $styles = Style::where('user_id', auth()->user()->id)
            ->where('year', $year)
            ->where('month', $month)
            ->select('main_img', 'header_img', 'footer_img', 'footer_color', 'bg_color')
            ->first();
        return $styles;
    }


    /* 取得月曆 */
    protected function getCalender($year, $formatMonth)
    {
        // get DB Calender record
        $argdate = $year . $formatMonth . '%';
        $calender = Calender::where('user_id', auth()->user()->id)
            ->where('date', 'like', $argdate)
            ->get()
            ->toArray();

        // 串接當年度TaiwanCalendar
        $client = new Client();
        $cdnUrl = 'https://cdn.jsdelivr.net/gh/ruyut/TaiwanCalendar/data/' . $year . '.json';
        $response = $client->get($cdnUrl);
        $cdnCals = json_decode($response->getBody(), true);

        // 只留下當月的cdnCals data
        $searchPrefix = str($year . $formatMonth);
        $cdnCals = array_filter($cdnCals, function ($item) use ($searchPrefix) {
            // 檢查"date"是否以$searchPrefix開頭，如果是就留下來
            return strpos($item['date'], $searchPrefix) === 0;
        });

        $firDay = Carbon::parse($year . '/' . $formatMonth . '/01');
        $firWeekDay = $firDay->dayOfWeek;

        $res = [];
        for ($i = 1; $i < $firWeekDay; $i++) {
            $res[] = [
                'date' => '',
                'week' => '',
                'isHoliday' => '',
            ];
        }
        
        foreach ($cdnCals as $cdnCal) {
            $cdnCal['date'] = substr($cdnCal['date'], -2,);
            $found = false;
            foreach ($calender as $cal) {
                $cal['date'] = substr($cal['date'], -2,);
                if ($cdnCal['date'] === $cal['date']) {
                    $res[] = array_merge($cdnCal, $cal);
                    $found = true;
                    break;
                }
            };
            if (!$found) {
                $res[] = [
                    'date' => $cdnCal['date'],
                    'week' => $cdnCal['week'],
                    'isHoliday' => $cdnCal['isHoliday'],
                    'description' => $cdnCal['description'],
                    'birthday_person' => '',
                    'is_mc_start' => '',
                    'is_mc_end' => '',
                    'tag_color' => '',
                    'tag_title' => '',
                    'tag_from' => '',
                    'tag_to' => '',
                    'sticker' => '',
                    'plan' => '',
                    'plan_time' => '',
                    'photos_link' => '',
                ];
            }
        }

        return $res;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
