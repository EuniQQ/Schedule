<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $today = Carbon::today()->locale('zh-tw');
        $year = $today->year;
        $month = $today->month;
        $date = $today->day;
        $weekDay =  $this->getChineseWeekDay($today);
        $arg = [
            'y' => $year,
            'm' => $month,
            'd' => $date,
            'w' => $weekDay,
        ];
        return view('content/home', $arg);
    }

    public function getChineseWeekDay($today)
    {
        $chineseWeekDay = [
            '日', '一', '二', '三', '四', '五', '六'
        ];

        $weekIndex = $today->dayOfWeek;
        $res = $chineseWeekDay[$weekIndex];
        return $res;
    }
}
