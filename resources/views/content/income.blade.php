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
                <tr class="npos">
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
            <button id="addBtn" class="btn">新增</button>

            <!-- Button trigger modal -->
            <button type="button" id="npoBtn" class="btn " data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                NPO資訊
            </button>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table npoTable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">方式</th>
                                        <th scope="col">教會/機構名稱</th>
                                        <th scope="col">帳號/刷卡連結</th>
                                        <th scope="col">金融機構</th>
                                        <th scope="col">填寫表單</th>
                                        <th scope="col">電話</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>匯款</td>
                                        <td>社團法人基督教新竹靈糧堂</td>
                                        <td>0061541-0184795</td>
                                        <td>竹東二重埔郵局 700</td>
                                        <td><a href="">網址</a></td>
                                        <td>03-58266151</td>
                                        <td>
                                            <i class="fa-solid fa-pen mx-2"></i>
                                            <i class="fa-solid fa-trash"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer px-2">
                            <div class="mb-2">
                                <label for="method">
                                    <span class="text-danger">*</span><span>方式</span>
                                </label>
                                <select name="method" id="method">
                                    <option value="card">刷卡</option>
                                    <option value="remit">匯款</option>
                                </select>
                                &nbsp;&nbsp;

                                <label for="name">
                                    <span class="text-danger">*</span>
                                    <span>名稱</span>
                                </label>
                                <input type="text" name="name">
                                &nbsp;&nbsp;

                                <label for="acc">帳號</label>
                                <input type="text" name="acc">
                                &nbsp;&nbsp;

                                <label for="code">代碼</label>
                                <input type="text" name="code" size="4">
                                &nbsp;&nbsp;
                            </div>

                            <div class="mb-2">
                                <label for="bank">金融機構</label>
                                <input type="text" name="bank" size="10">
                                &nbsp;&nbsp;

                                <label for="link">線上刷卡連結</label>
                                <input type="url" name="link" size="30">
                                &nbsp;&nbsp;
                            </div>

                            <div class="mb-2">
                                <label for="formLink">表單連結</label>
                                <input type="url" name="formLink" size="30">
                                &nbsp;&nbsp;

                                <label for="tel">電話</label>
                                <input type="tel" name="tel">
                                &nbsp;&nbsp;
                            </div>
                            <br>

                            <div class="mb-3">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-warning btn-sm">送出</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('endJs')
<script src="/js/income.js"></script>
@endsection