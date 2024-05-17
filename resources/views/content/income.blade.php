@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/income.css')}}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">
@endsection

@section('title','收入')

@section('content')

@include('layouts.menu')

<div class="container-flude">

    <div class="row main">

        <div class="head">
            <span class="capsuleTag">2 0 2 4 ‧ INCOME</span>
            <span class="icons">
                <select class="selYear">
                    <option value="2023">2023</option>
                </select>
                @include("icons.search")
                @include("icons.menu")
            </span>
        </div>

        <table>
            <thead>
                <tr>
                    <th scope="col" class="date">日&nbsp;期</th>
                    <th scope="col" class="reason">事&nbsp;由</th>
                    <th scope="col" class="payer">給付者</th>
                    <th scope="col" class="amount">金&nbsp;額</th>
                    <th scope="col" class="donate">十一金額</th>
                    <th scope="col" class="bank">銀&nbsp;行</th>
                    <th scope="col" class="dnDate">十一日期</th>
                    <th scope="col" class="dObj">奉獻單位</th>
                    <th scope="col" class="remark">備&nbsp;註</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>2024-05-16</td>
                    <td>打工</td>
                    <td>老闆板</td>
                    <td>$1000</td>
                    <td>$100</td>
                    <td>玉山</td>
                    <td>2024-05-16</td>
                    <td>台北611</td>
                    <td>匯款</td>
                </tr>

                <tr>
                    <td>2024-05-17</td>
                    <td>接案</td>
                    <td>阿寶</td>
                    <td>$1500</td>
                    <td>$150</td>
                    <td>富邦</td>
                    <td>-</td>
                    <td>-</td>
                    <td>現金</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <span class="total">Total：xxx</span>
            <button class="btn addBtn">新增</button>
            <button class="btn npoBtn">NPO資訊</button>
        </div>

    </div>

</div>

@endsection