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
use Exception;

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

        $yearList = $this->getYearList();

        // 其他參數
        $finance = $this->getFinance($year, $month);
        $style =  $this->getStyle($year, $month) ?? " ";
        $calender = $this->getCalender($year, $month);
        $weatherDes = $this->getWeatherDes();
        $weatherType = $this->getWeatherType();
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
            'yearList' => $yearList,
            'weatherDes' => $weatherDes,
            'weatherType' => $weatherType
        ];

        return Response::json($res);
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
                'mc' => '',
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
                    'mc' => '',
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
     * 取得天氣敘述(降雨機率&氣溫)
     */
    public function getWeatherDes()
    {
        $ch = curl_init();

        $url =  'https://opendata.cwa.gov.tw/api/v1/rest/datastore/F-D0047-071?Authorization=CWB-8CE3FEDC-E7BF-43FA-931F-1AD1B143E0E2&format=JSON&locationName=%E6%B3%B0%E5%B1%B1%E5%8D%80&elementName=WeatherDescription';

        $res = $this->curl($ch, $url);
        return $res;
    }



    /**
     * 取得天氣型態(icon)
     */
    public function getWeatherType()
    {
        $ch = curl_init();
        $url =  'https://opendata.cwa.gov.tw/api/v1/rest/datastore/F-D0047-071?Authorization=CWB-8CE3FEDC-E7BF-43FA-931F-1AD1B143E0E2&format=JSON&locationName=%E6%B3%B0%E5%B1%B1%E5%8D%80&elementName=Wx';

        $res = $this->curl($ch, $url);
        return $res;
    }



    /**
     * 打氣象局api
     */
    protected function curl($ch, $url)
    {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        $decodeResult = json_decode($result);
        return $decodeResult->records->locations[0]->location[0]->weatherElement[0]->time;
    }



    /**
     * Creating a new resource.
     */
    public function create(Request $request)
    {
        $_data = $request->post();

        $validator = Validator::make($_data, [
            'date' => 'required | string',
            'birthday_person' => 'string | nullable',
            'mc' => 'nullable',
            'plan' => 'string | nullable',
            'plan_time' => 'nullable',
            'tag_color' => 'string | required_with:tag_title',
            'tag_title' => 'string | nullable',
            'tag_to' => 'date | nullable | required_with:tag_title',
            'sticker' => 'image | nullable',
            'photos_link' => 'url | nullable'
        ], $this->message());

        if ($validator->fails()) {
            $errors = $this->ifValidateFails($validator);
            return Response(['message' => $errors], 422);
        }
        $validated = $validator->validated();

        $validated['sticker'] = $request->has('sticker') ? $this->handleImg($request) : null;
        $validated['user_id'] = auth()->user()->id;

        // 確認不是黑色，避免誤存
        if (isset($validated['tag_color']) && $validated['tag_color'] == '#000000') {
            $validated['tag_color'] = null;
        }

        Calender::create($validated);

        if (isset($validated['tag_title'])) {
            $originalTagTo = null;
            $this->saveTagColors($validated, $originalTagTo);
        }

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
        $_data = $request->post();
        $validator = Validator::make($_data, [
            'date' => 'required',
            'birthday_person' => 'string | nullable',
            'mc' => ' nullable',
            'plan' => 'string | nullable',
            'plan_time' => 'nullable',
            'tag_color' => 'string | nullable',
            'tag_title' => 'string | nullable',
            'tag_to' => 'date | nullable',
            'sticker' => 'nullable',
            'sticker_pre' => 'nullable',
            'photos_link' => 'url | nullable'
        ], $this->message());

        if ($validator->fails()) {
            $error = $this->ifValidateFails($validator);
            return Response(['message' => $error], 422);
        }

        $validated = $validator->validated();
        $validated['user_id'] = auth()->user()->id;

        $record = Calender::where('id', $id)->first();

        if ($request->hasFile('sticker')) {
            $validated['sticker'] =  $this->handleImg($request);
        } else {
            if (!is_null($record->sticker) && !is_null($validated['sticker_pre'])) {
                $validated['sticker'] = $record->sticker;
            } else {
                $validated['sticker'] = NULL;
            }
        };

        if (isset($validated['tag_color']) && $validated['tag_color'] == '#000000') {
            $validated['tag_color'] = NULL;
        };

        if (!isset($validated['mc'])) {
            $validated['mc'] = 0;
        };

        $originalTagTo = is_null($record->tag_to) ? $validated['date'] : $record->tag_to;
        $this->saveTagColors($validated, $originalTagTo);
        $res = $record->update($validated);

        if ($res >= 1) {
            return back();
        } else {
            abort(500, '更新失敗');
        };
    }



    /**
     * save days of tag color
     */
    protected function saveTagColors($validated, $originalTagTo)
    {
        $userId = auth()->user()->id;
        $formatOriTagto = carbon::parse($originalTagTo)->format('Ymd');
        $tagStart = $validated['date'];
        $tagEnd = is_null($validated['tag_to']) ? $validated['date'] : Carbon::parse($validated['tag_to'])->format("Ymd");
        $tagColor = isset($validated['tag_color']) ? $validated['tag_color'] : null;
        $interval = carbon::parse($tagStart)->diffInDays($tagEnd);
        $updateInterval = carbon::parse($formatOriTagto)->diffInDays($tagEnd, false) ?? null;

        if ($updateInterval < 0) {
            // 修改(天數減少)
            $this->createTagColors($interval, $tagStart, $tagColor);
            for ($i = 0; $i > $updateInterval; $i--) {
                $reverseDate = intval($formatOriTagto) + $i;
                $reserseData = Calender::where('user_id', $userId)->where('date', $reverseDate)->first();
                if (
                    $reserseData->birthday_person == null &&
                    $reserseData->plan == null &&
                    $reserseData->plan_time == null &&
                    $reserseData->tag_title == null &&
                    $reserseData->tag_to == null &&
                    $reserseData->sticker == null &&
                    $reserseData->photos_link == null &&
                    $reserseData->mc == 0
                ) {
                    $reserseData->delete();
                } else {
                    $reserseData->update([$reserseData->tag_color = null]);
                }
            }
        }
        // 新增(天數增加) or 修改(原有幾天)
        $this->createTagColors($interval, $tagStart, $tagColor);

        return;
    }


    protected function createTagColors($interval, $tagStart, $tagColor)
    {
        if ($interval > 0 && $tagColor !== '#000000') {
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
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $request = $request->all();
        $schedule = Calender::find($id);
        if ($schedule->user_id == auth()->user()->id) {
            if (is_null($schedule)) {
                abort(404, 'Not found');
            }

            if ($schedule->sticker) {
                $path = $schedule->sticker;
                $this->delImgFromFolder($path);
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
        try {
            $data = $request->all();
            if ($request->hasAny(["main_img", "header_img", "footer_img"])) {
                $imgsUrl = $this->handleImg($request);
                $data['main_img'] = $imgsUrl['main_img'] ?? "";
                $data['header_img'] = $imgsUrl['header_img'] ?? "";
                $data['footer_img'] = $imgsUrl['footer_img'] ?? "";
            }

            $data['month'] = $month;
            $data['year'] = $year;
            $data['user_id'] = auth()->user()->id;
            Style::create($data);
        } catch (Exception $e) {
            return Response::json(['statusCode' => $e->getCode(), 'message' => $e->getMessage()]);
        }

        return Response::json(['statusCode' => 200, 'message' => '新增完成']);
    }



    /**
     * 修改月曆視覺api
     */
    public function updateStyle(Request $request, $id)
    {
        try {

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
        } catch (Exception $e) {
            return Response::json([
                'statusCode' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
        }

        if ($res > 0) {
            return Response::json(['status' => 200, 'message' => 'success']);
        } else {
            abort(400, 'update failed');
        }
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

                $imgArr = ['main_img', 'header_img', 'footer_img'];
                for ($i = 0; $i < 3; $i++) {
                    $imgType = $imgArr[$i];
                    if ($style->$imgType) {
                        $path = $style->$imgType;
                        $this->delImgFromFolder($path);
                    }
                }

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



    protected function message()
    {
        return [
            'date.required' => 'date 為必填',
            'string' => ':attribute 必須為字串',
            'required_with' => '若設定tag title，:attribute 為必填',
            'tag_to.date' => 'tag_to 必須為日期格式',
            'sticker.image' => 'sticker 必須為圖片檔',
            'photos_link.url' => 'photos_link 必須為正確網址格式'
        ];
    }
}
