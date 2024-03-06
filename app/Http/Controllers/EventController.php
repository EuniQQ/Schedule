<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Calender;

class EventController extends Controller
{
    public function index()
    {
        return view('content/event');
    }


    public function getEvents($year)
    {

        $userId = auth()->user()->id;
        $records = Calender::where('user_id', $userId)->where('date', 'like', $year . '%')->whereNotNull('tag_title')->select('tag_title', 'date')->orderBy('date')->get();

        $events = $records->transform(function ($record) {
            return [
                'month' => substr($record->date, 4, 2),
                'content' => $record->tag_title
            ];
        });

        $mergedEvents = [];
        foreach ($events as $event) {
            $month = $event['month'];
            $content = $event['content'];

            if (array_key_exists($month, $mergedEvents)) {
                // event頁顯示每月6筆
                if (count($mergedEvents[$month]) < 6) {
                    $mergedEvents[$month][] = $content;
                }
            } else {
                $mergedEvents[$month] =  [$content];
            }
        }

        $keyList = [
            '01', '02', '03', '04', '05', '06', '07', '08',
            '09', '10', '11', '12'
        ];

        foreach ($keyList as $val) {
            if (!array_key_exists($val, $mergedEvents)) {
                $mergedEvents[$val] = [];
            } else {
                $mergedEvents[$val] = $mergedEvents[$val];
            }
        }


        $res = [
            'year' => $year,
            'data' => $mergedEvents,
            'yearList' => $this->getYearList()
        ];

        return Response::json($res);
    }
}
