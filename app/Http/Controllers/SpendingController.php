<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Expense;
use Carbon\Carbon;

class SpendingController extends Controller
{
    public function index()
    {

        $userId = auth()->user()->id;
        $now = Carbon::now()->locale('zh-tw');
        $thisYear = $now->format('Y');
        $thisMonth = $now->format('m');
        $year = !empty($_GET['selY']) ? $_GET['selY'] : $thisYear;
        $month = !empty($_GET['selM']) ? $_GET['selM'] : $thisMonth;
        $searchKey = $year . '-' . $month . '%';
        $query = Expense::where('user_id', $userId);
        $cashQuery = clone $query;
        $cashData = $cashQuery->where('date', 'like', $searchKey)
            ->whereNull('bank')
            ->get();
        $cardQuery = clone $query;
        $cardData = $cardQuery->where('date', 'like', $searchKey)
            ->whereNotNull('bank')
            ->get();
        $cashDataWithWeekDay = $this->addWeekDayToCollection($cashData);
        $cardDataWithWeekDay = $this->addWeekDayToCollection($cardData);
        $yearList = $this->getRecordYears($query);

        $args = [
            'year' => $year,
            'month' => $month,
            'cashData' => $cashDataWithWeekDay,
            'cardData' => $cardDataWithWeekDay,
            'cashSum' => $cashData->sum('amount'),
            'cardSum' => $cardData->sum('actual_pay'),
            'blankCash' => 30 - ($cashData->count()),
            'blankCard' => 30 - ($cardData->count()),
            'yearList' => array_unique($yearList)
        ];

        return view('content.spending')->with('args', $args);
    }


    private function addWeekDayToCollection($data)
    {
        $weekDays = ['日', '一', '二', '三', '四', '五', '六'];
        $dataWithWeekDay = $data->map(function ($item) use ($weekDays) {
            $date = Carbon::parse($item->date);
            $weekDay = $weekDays[$date->dayOfWeek];
            $item->weekDay = $weekDay;
            return $item;
        });

        return $dataWithWeekDay;
    }



    public function createCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'shop' => 'required | string',
            'item' => 'required | string',
            'amount' => 'required | numeric',
            'refund' => 'nullable | numeric',
            'actual_pay' => 'nullable | numeric',
            'bank' => 'required | string',
            'notes' => 'nullable | string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('modalId', 'addCardModal')->withInput();
        }

        $validated = $validator->validated();
        auth()->user()->expenses()->create($validated);
        return redirect()->back();
    }



    public function createCash(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'item' => 'required | string',
            'amount' => 'required | numeric'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('modalId', 'addCashModal')->withInput();
        };

        $validated = $validator->validated();
        auth()->user()->expenses()->create($validated);
        return redirect()->back();
    }


    /**
     * API-更新(CASH/CARD)單格內容
     */
    public function update(Request $request, $id)
    {
        $_data = $request->all();
        $colName = $_data['name'];
        $newVal = $_data['value'];
        $userId = auth()->user()->id;

        $spending = Expense::find($id);
        if ($spending) {
            $spending->$colName = $newVal;
            $spending->save();

            if ($colName == 'amount' || $colName == 'actual_pay') {
                $searchKey = substr($spending->date, 0, 7) . '%';
                $query = Expense::where('user_id', $userId)
                    ->where('date', 'like', $searchKey);

                if (is_null($spending->bank)) {
                    $totalId = 'cashTotal';
                    $totalQuery = clone $query;
                    $total = $totalQuery->whereNull('bank')->sum('amount');
                } else {
                    $totalId = 'cardTotal';
                    $totalQuery = clone $query;
                    $total = $totalQuery->whereNotNull('bank')->sum('actual_pay');
                }

                $res = [
                    'total' => $total,
                    'totalId' => $totalId
                ];
            } else {
                $res = ['message' => 'ok'];
            }

            return Response::json($res);
        } else {
            abort(404, '找不到此紀錄');
        }
    }


    /**
     * API-刪除勾選的checkbox資料
     */
    public function destroy(Request $request)
    {
        $delIdArr = $request->ids;

        if (is_array($delIdArr) && !empty($delIdArr)) {
            auth()->user()->expenses()->whereIn('id', $delIdArr)->delete();
            return Response::json(['message' => 'ok']);
        } else {
            abort(400, '刪除失敗');
        }

        return Response::json(['message' => 'ok']);
    }
}
