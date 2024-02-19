<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
}
