<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index($year = '')
    {
        return view('content.income');
    }


    /**
     * 新增NPO組織資訊
     */
    public function createNpo(Request $request)
    {
        $content = $request->validate([
            'method' => 'required',
            'name' => 'required',
            'account' => 'nullable|numeric',
            'code' => 'nullable|numeric',
            'bank' => 'nullable',
            'pay_on_line' => 'nullable|URL',
            'form_link' => 'nullable|URL',
            'tel' => 'nullable|numeric'
        ]);

        auth()->user()->donates()->create($content);
        return redirect()->route('income.index');
    }


    protected function attribute()
    {
        return [
            'method' => '奉獻方式',
            'name' => '奉獻對象',
            'account' => '帳號',
            'code' => '銀行代碼',
            'bank' => '金融機構',
            'pay_on_line' => '線上付款',
            'form_link' => '填寫表單連結',
            'tel' => '機構電話'
        ];
    }

    protected function message()
    {
        return [
            'required' => ':attribute為必填',
            'numeric' => ':attribute須為數字',
            'URL' => ':attribute須為有效網址'
        ];
    }
}
