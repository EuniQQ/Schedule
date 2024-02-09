<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Style;
use App\Models\Calender;
use App\Models\Income;
use App\Models\Expense;
use App\Http\Traits\UploadImgTrait;
use Illuminate\Http\RedirectResponse;

class CalenderController extends Controller
{
    use UploadImgTrait;

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
        $style =  $this->getStyle($year, $month) ?? " ";
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
            'userId' => auth()->user()->id,
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
            ->select('id', 'main_img', 'header_img', 'footer_img', 'footer_color', 'bg_color')
            ->first();

        if (is_null($styles)) {
            $styles['id'] = $styles['main_img'] = $styles['header_img'] = $styles['footer_img'] = $styles['footer_color'] = $styles['bg_color'] = null;
        }
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
                'id' => '',
                'fullDate' => ''
            ];
        }

        foreach ($cdnCals as $cdnCal) {
            $cdnCal['fullDate'] = $cdnCal['date'] ?? '';
            $cdnCal['date'] = substr($cdnCal['date'], -2,);
            $found = false;
            foreach ($calender as $cal) {
                $cal['plan_time'] = !empty($cal['plan_time']) ? carbon::createFromFormat('H:i:s', $cal['plan_time'])->format('H:i') : '';
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
                    'fullDate' => $cdnCal['fullDate'],
                    'id' => '',
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
    public function create(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required | integer | exists:App\Models\User,id',
            'date' => 'required | string',
            'birthday_person' => 'string | nullable',
            'is_mc_start' => 'boolean | nullable',
            'is_mc_end' => 'boolean | nullable',
            'plan' => 'string | nullable',
            'plan_time' => 'nullable',
            'tag_color' => 'string | nullable',
            'tag_title' => 'string | nullable',
            'tag_from' => 'date | nullable',
            'tag_to' => 'date | nullable',
            'sticker' => 'image | nullable',
            'photos_link' => 'url | nullable'
        ]);

        $newSchedul = $validated;
        $newSchedul['sticker'] = $this->handleImg($request);
        $newSchedul['user_id'] = auth()->user()->id;
        if ($newSchedul['tag_color'] == '#000000') {
            $newSchedul['tag_color'] = null;
        }
        Calender::create($newSchedul);

        return redirect()->back();
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
        $res = Calender::find($id);
        return Response::json($res);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $newSchedul = $request->all();
        unset($newSchedul['_token']);
        $newSchedul['sticker'] = $this->handleImg($request);
        $res = Calender::where('id', $id)
            ->update($newSchedul);
        if ($res >= 1) {
            return redirect()->back();
        } else {
            abort(500, '更新失敗');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $request = $request->all();
        $schedule = Calender::find($id);
        if ($request['userId'] == $schedule->user_id) {
            if (is_null($schedule)) {
                abort(404, 'Not found');
            }
            $message = $schedule->delete() == true ? "刪除成功" : "刪除失敗";
            return Response::json(['message' => $message]);
        } else {
            abort(403);
        }
    }



    /**
     * 新增月曆視覺api
     */
    public function storeStyle(Request $request, $year, $month)
    {
        $data = $request->all();
        if ($request->hasAny(["main_img", "header_img", "footer_img"])) {
            $imgsUrl = $this->handleImg($request);
            $data['main_img'] = $imgsUrl['main_img'] ?? "";
            $data['header_img'] = $imgsUrl['header_img'] ?? "";
            $data['footer_img'] = $imgsUrl['footer_img'] ?? "";
        }
        $data['month'] = $month;
        $data['year'] = $year;

        Style::create($data);
    }


    
    /**
     * 修改月曆視覺api
     */
    public function updateStyle(Request $request, $id)
    {
        $data = $request->all();
        if ($request->hasAny(["main_img", "header_img", "footer_img"])) {
            $imgsUrl = $this->handleImg($request);
            $data = array_merge($data, $imgsUrl);
        }
        $res = Style::where('id', $id)->update($data);

        $newArr =  Style::find($id)->toArray();
        $newArr['month'] = str_pad($newArr['month'], 2, 0, STR_PAD_LEFT);

        return Response::json($newArr);
    }



    /**
     * 刪除單月月曆視覺api
     */
    public function destroyStyle(Request $request, Style $style)
    {
        $request = $request->all();
        if ($style) {

            if (auth()->user()->id == $style->user_id) {
                $res = $style->delete();
            } else {
                abort(403, "權限不足，無法刪除");
            }
        } else {
            abort(404, "查無資料");
        }

        return Response::json($res);
    }



    /**
     * 上傳圖片 & 搬檔案
     */
    public function handleImg($request)
    {
        if ($request->hasFile('sticker')) {
            $type = "sticker";
            $file = $request->file($type);
            $uploadImg = $this->ImgProcessing($file, $type);
            $res = $uploadImg;
        } else {

            for ($i = 0; $i < 3; $i++) {
                $imgs = ["main_img", "header_img", "footer_img"];
                $type = $imgs[$i];
                if ($request->hasFile($type)) {
                    $file = $request->file($type);
                    $res[$type] = $this->ImgProcessing($file, $type);
                }
            }
        }

        return $res;
    }
}
