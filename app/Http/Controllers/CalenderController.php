<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Style;
use App\Models\Calender;
use App\Models\Income;
use App\Models\Expense;
use App\Http\Traits\UploadImgTrait;


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
        $hebrewYear = 5783 + (intval($year) - 2024);
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

        $yearList = [];
        for ($i = 0; $i < 5; $i++) {
            $yearList[] = $thisYear - $i;
        }

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
            'yearList' => $yearList
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
                'fullDate' => '',
                'is_mc_start' => '',
                'is_mc_end' => ''
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


    public function getWeather()
    {

        $ch = curl_init();
        $url = 'https://opendata.cwa.gov.tw/api/v1/rest/datastore/F-D0047-071?Authorization=CWB-8CE3FEDC-E7BF-43FA-931F-1AD1B143E0E2&locationName=%E6%B3%B0%E5%B1%B1%E5%8D%80&elementName=&sort=time';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        $result = curl_exec($ch);
        $decodeResult = json_decode($result);
        $weathers = $decodeResult->records->locations[0]->location[0]->weatherElement;

        // data group
        $rainfallGroup = $weathers[0]->time;
        $desGroup = $weathers[6]->time;
        $lowestGroup = $weathers[8]->time;
        $highestGroup = $weathers[12]->time;
        $dataLength = count($rainfallGroup);

        $res = [];
        for ($i = 1; $i < $dataLength; $i + 2) {
            $fullDateTime = $rainfallGroup[$i]->startTime;

            // day
            $dateParse = carbon::parse($fullDateTime);
            $day = $dateParse->day;

            // desIntoIcon
            $des = $desGroup[$i]->elementValue[1]->value;
            $isSunny = ['01', '24'];
            $isSunnyWithCloudy = ['02', '03', '25', '26'];
            $isCloudy = ['04', '05', '06', '07', '27', '28'];
            $isSunnyWithRain = ['19'];
            $isCloudyWithRain = ['08', '09', '10', '12', '13', '20', '29', '30', '31', '32'];
            $isThunderstorm = ['15', '16', '17', '18', '21', '22', '33', '34', '35', '36', '41'];
            $isSnowing = ['23', '37', '42'];
            $isRainny = ['11', '14', '38', '39'];

            $urlPrefix = "/storage/img/weather/";
            if (in_array($des, $isSunny)) {
                $iconUrl = $urlPrefix . "sun.png";
            } elseif (in_array($des, $isSunnyWithCloudy)) {
                $iconUrl = $urlPrefix . "clear-sky.png";
            } elseif (in_array($des, $isCloudy)) {
                $iconUrl = $urlPrefix . "cloud.png";
            } elseif (in_array($des, $isSunnyWithRain)) {
                $iconUrl = $urlPrefix . "cloudy.png";
            } elseif (in_array($des, $isCloudyWithRain)) {
                $iconUrl = $urlPrefix . "rainy-day.png";
            } elseif (in_array($des, $isThunderstorm)) {
                $iconUrl = $urlPrefix . "storm.png";
            } elseif (in_array($des, $isSnowing)) {
                $iconUrl = $urlPrefix . "snow.png";
            } elseif (in_array($des, $isRainny)) {
                $iconUrl = $urlPrefix . "heavy-rain.png";
            } else {
                $iconUrl = '';
            }

            $res[] = [
                'fullDateTime' => $fullDateTime,
                'day' => $day,
                'rain' => $rainfallGroup[$i]->elementValue[0]->value,
                'lowest' => $lowestGroup[$i]->elementValue[0]->value,
                'highest' => $highestGroup[$i]->elementValue[0]->value,
                'img' => $iconUrl,
            ];

        }

        curl_close($ch);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $_data = $request->post();
        $validator = Validator::make($_data, [
            'user_id' => 'required | integer | exists:App\Models\User,id',
            'date' => 'required | string',
            'birthday_person' => 'string | nullable',
            'is_mc_start' => 'boolean | nullable',
            'is_mc_end' => 'boolean | nullable',
            'plan' => 'string | nullable',
            'plan_time' => 'nullable',
            'tag_color' => 'string | nullable',
            'tag_title' => 'string | nullable',
            'tag_to' => 'date | nullable',
            'sticker' => 'image | nullable',
            'photos_link' => 'url | nullable'
        ]);

        if ($validator->fails()) {
            $error = $this->ifValidateFails($validator);
            return Response(['message' => $error], 422);
        }
        $validated = $validator->validated();

        $validated['sticker'] = isset($validated['sticker']) ? $this->handleImg($request) : null;
        $validated['user_id'] = auth()->user()->id;

        if ($validated['tag_color'] == '#000000') {
            $validated['tag_color'] = null;
        }

        Calender::create($validated);
        $this->saveTagColors($validated);

        return redirect()->route('calender.index');
    }


    /**
     * save days of tag color
     */
    protected function saveTagColors($validated)
    {
        $tagStart = $validated['date'];
        $tagEnd = $validated['tag_to'];
        $tagColor = $validated['tag_color'];
        $interval = carbon::parse($tagStart)->diffInDays($tagEnd);

        if ($tagEnd !== $tagStart && $interval > 0) {

            for ($i = 1; $i <= $interval; $i++) {
                $addDate = intval($tagStart) + $i;
                Calender::updateOrCreate(
                    ['date' => $addDate, 'user_id' => auth()->user()->id],
                    ['tag_color' => $tagColor]
                );
            }
        }

        return;
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
        // $newSchedul = $request->all();
        $_data = $request->post();
        $validator = Validator::make($_data, [
            'user_id' => 'required | integer | exists:App\Models\User,id',
            'date' => 'required | string',
            'birthday_person' => 'string | nullable',
            'is_mc_start' => 'boolean | nullable',
            'is_mc_end' => 'boolean | nullable',
            'plan' => 'string | nullable',
            'plan_time' => 'nullable',
            'tag_color' => 'string | nullable',
            'tag_title' => 'string | nullable',
            'tag_to' => 'date | nullable',
            'sticker' => 'image | nullable',
            'photos_link' => 'url | nullable'
        ]);

        if ($validator->fails()) {
            $error = $this->ifValidateFails($validator);
            return Response(['message' => $error], 422);
        }
        $validated = $validator->validated();

        // unset($_data['_token']);
        $validated['sticker'] = $this->handleImg($request) ?? null;

        $res = Calender::where('id', $id)->update($validated);
        $this->saveTagColors($validated);

        if ($res >= 1) {
            return redirect()->route('calender.index');
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
        } elseif (isset($data['bg_color']) && $data['bg_color'] == '#000000') {
            $data['bg_color'] = null;
        } elseif (isset($data['footer_color']) && $data['footer_color'] == '#000000') {
            $data['footer_color'] = null;
        }

        $res = Style::where('id', $id)->update($data);

        // $newArr =  Style::find($id)->toArray();
        // 將月份改為有前導0 str
        // $newArr['month'] = str_pad($newArr['month'], 2, 0, STR_PAD_LEFT);

        return Response::json(['update_access' => $res]);
    }



    /**
     * 刪除單月月曆視覺api
     */
    public function destroyStyle(Request $request, $id)
    {
        $request = $request->all();
        $style = Style::find($id);
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
