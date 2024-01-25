<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Style;
use App\Models\Calender;


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
        $formatMonth = $now->format('m');  //改成有前導0
        if (is_null($year) && is_null($month)) {
            $year = $thisYear;
            $month = $thisMonth;
        } elseif (is_null($year) && !is_null($month)) {
            $year = $thisYear;
        }

        $hebrewYear = 5783 + ($year - 2024);

        // 其他參數
        $finance = $this->getFinance($year, $month);
        $style = $this->getStyle($year, $month);
        $calender = $this->getCalender($year, $formatMonth);

        $res = [
            'year' => $year,
            'hebrew' => $hebrewYear,
            'month' => $month,
            'income' => '',
            'expenses' => '',
            'balance' => '',
            'style' => $style,
            'calender' => $calender,
        ];
        return view('content.calender');
    }

    /* 取得財務 */
    protected function getFinance($year, $month)
    {
        // return auth()->user()->incomes;

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


    protected function getCalender($year, $formatMonth)
    {
        // get Calender table record
        $argdate = $year . $formatMonth . '%';
        $calender = Calender::where('user_id', auth()->user()->id)
            ->where('date', 'like', $argdate)
            ->get()
            ->toArray();

        // 串接TaiwanCalendar
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

        $res = [];
        foreach ($cdnCals as $cdnCal) {
            $found = false;
            foreach ($calender as $item) {
                if ($cdnCal['date'] === $item['date']) {
                    $res[] = array_merge($cdnCal, $item);
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
                    'photos_link' => '',
                ];
            }
        };
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
