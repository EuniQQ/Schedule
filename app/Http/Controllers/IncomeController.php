<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Donate;
use App\Models\Income;
use Carbon\Carbon;

class IncomeController extends Controller
{
    /**
     * 載入收入頁
     */
    public function index($year = '')
    {
        if (empty($year)) {
            $now = Carbon::now()->locale('zh-tw');
            $year = $now->year;
        }

        $userId = auth()->user()->id;
        $query = Income::where('user_id', $userId);
        $yearList = $this->getRecordYears($query);
        $incomeRecord = $query->where('date', 'like', $year . '%')
            ->orderBy('date', 'asc')
            ->get();

        $total = $query->sum('amount');

        $args = [
            'records' => $incomeRecord,
            'thisYear' => $year,
            'yearList' => array_unique($yearList),
            'total' => $total
        ];

        return view('content.income')->with($args);
    }


    /**
     * 新增NPO組織資訊
     */
    public function createNpo(Request $request)
    {
        $content = $request->validate([
            'method' => 'required|integer',
            'name' => 'required',
            'account' => 'nullable|numeric',
            'code' => 'nullable|string',
            'bank' => 'nullable',
            'pay_on_line' => 'nullable|url',
            'form_link' => 'nullable|url',
        ],);

        auth()->user()->donates()->create($content);
        return redirect()->route('income.index');
    }


    /**
     * 新增收入資訊
     */
    public function createIncome(Request $request)
    {
        $content = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'payer' => 'required|string',
            'amount' => 'required|integer',
            'bank' => 'required|string',
            'tithe' => 'nullable|integer',
            'tithe_date' => 'nullable|date',
            'tithe_obj' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        auth()->user()->incomes()->create($content);
        return back();
    }


    /**
     * API-更新收入單格內容
     */
    public function update(Request $request, $id)
    {
        $_data = $request->all();
        $colName = $_data['name'];
        $newVal = $_data['value'];
        $userId = auth()->user()->id;

        $income = Income::find($id);
        if ($income) {
            $income->$colName = $newVal;
            $income->save();

            $year = substr($income->date, 0, 4);
            $total = Income::where('user_id', $userId)
                ->where('date', 'like', $year . '%')
                ->sum('amount');

            $res = [
                'message' => '更新成功',
                'total' => $total
            ];
            return Response::json($res);
        } else {
            abort(400, '找不到此紀錄');
        }
    }


    /**
     * API-刪除單筆INCOME
     */
    public function destroy($id)
    {
        $income = auth()->user()->incomes->find($id);
        $income->delete();
        return Response::json(['message' => '刪除成功']);
    }


    /**
     * API-取得NPO資訊
     */
    public function getNpoList()
    {
        $userId = auth()->user()->id;
        $res = Donate::where('user_id', $userId)->get();
        return Response::json($res);
    }

    /**
     * API-取得單筆NPO資訊
     */
    public function getNpo($id)
    {
        $res = Donate::find($id);
        return Response::json($res);
    }


    /**
     * API-更新單筆NPO資訊
     */
    public function updateNpo(Request $request, $id)
    {
        $validated = $request->validate([
            'method' => 'required|integer',
            'name' => 'required',
            'account' => 'nullable|numeric',
            'code' => 'nullable|string',
            'bank' => 'nullable',
            'pay_on_line' => 'nullable|url',
            'form_link' => 'nullable|url',
        ], $this->message());

        unset($validated['_token']);
        $res = Donate::updateOrCreate(
            ['id' => $id],
            $validated
        );

        if ($res) {
            return redirect()->route('income.index');
        } else {
            abort(500, '更新失敗');
        }
    }

    /**
     * API-刪除單筆NPO資訊
     */
    public function destroyNpo($id)
    {
        $userId = auth()->user()->id;
        $deleted = Donate::where('user_id', $userId)
            ->where('id', $id)
            ->delete();

        if ($deleted > 0) {
            $res = Donate::where('user_id', $userId)->get();
            return Response::json($res);
        } else {
            abort(500, '刪除失敗');
        }
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
        ];
    }



    protected function message()
    {
        return [
            'required' => ':attribute為必填',
            'numeric' => ':attribute須為數字',
            'url' => ':attribute須為有效網址'
        ];
    }
}
