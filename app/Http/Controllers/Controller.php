<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     *  處理未通過表單驗證回應訊息
     */
    protected function ifValidateFails($validator)
    {
        $errorArr = $validator->errors()->toArray();
        $errors = array_values($errorArr);
        $errorMsgs = [];
        foreach ($errors as $error) {
            foreach ($error as $msg) {
                $errorMsgs[] = $msg;
            }
        }
        $errorStr = implode("\r\n", $errorMsgs);
        return $errorStr;
    }


    /**
     * 取當年度回溯前4年的year list
     * 用於calender / monthlyEvent的下拉式選單opt
     */
    protected function getYearList()
    {
        $now = Carbon::now()->locale('zh-tw');
        $thisYear = $now->year;
        $yearList = [];
        for ($i = 0; $i < 5; $i++) {
            $yearList[] = $thisYear - $i;
        }

        return $yearList;
    }


    /**
     * 從所在資料夾刪除圖片
     */
    protected function delImgFromFolder($path)
    {
        // 取得完整圖片路徑
        $fullPath = public_path($path);
        unlink($fullPath);
        return;
    }
}
