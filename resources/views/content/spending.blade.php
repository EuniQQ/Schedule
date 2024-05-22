@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/spending.css')}}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">
@endsection

@section('title','支出')

@section('content')

@include('layouts.menu')

<div class="container-fruid">
    <div class="header text-start d-flex">
        @include("icons.menu")
        <div class="capsuleTag">2024 ‧ 05</div>
    </div>

    <div class="main d-flex">
        <div class="cash">
            <p class="px-2 tableTitle">CASH</p>
            <table class="table">
                <thead class="table-light">
                    <tr class="text-center">
                        <th scope="col" class="checkbox"></th>
                        <th scope="col" class="date">日&nbsp;期</th>
                        <th scope="col" class="weekDay"></th>
                        <th scope="col" class="item">項目</th>
                        <th scope="col" class="amount">金&nbsp;額</th>
                    </tr>
                </thead>

                <tbody class="cashTbody">
                    <?php
                    for ($i = 0; $i < 20; $i++) {
                    ?>
                        <tr class="text-center">
                            <td data-name="checkbox">
                                <input class="cashChbox d-none" type="checkbox" data-id="">
                            </td>
                            <td data-id="" data-name="date"></td>
                            <td data-id="" data-name="weekDay">
                            </td>
                            <td data-id="" data-name="item"></td>
                            <td data-id="" data-name="amount"></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="card">
            <P class="px-2 tableTitle">CREDIT CARD</P>
            <table class="table">
                <thead class="table-light">
                    <tr class="text-center">
                        <th scope="col" class="checkbox"></th>
                        <th scope="col" class="date">日期</th>
                        <th scope="col" class="weekDay"></th>
                        <th scope="col" class="shop">商家</th>
                        <th scope="col" class="item">項目</th>
                        <th scope="col" class="amount">金額</th>
                        <th scope="col" class="refund">退款</th>
                        <th scope="col" class="actulCost">實支</th>
                        <th scope="col" class="bank">卡別</th>
                        <th scope="col" class="notes">備註</th>
                    </tr>
                </thead>

                <tbody class="cardTbody">
                    <?php
                    for ($i = 0; $i < 20; $i++) {
                    ?>
                        <tr class="text-center">
                            <td data-name="checkbox">
                                <input class="cardChbox d-none" type="checkbox" data-id="">
                            </td>
                            <td data-id="" data-name="date"></td>
                            <td data-id="" data-name="weekDay">
                            </td>
                            <td data-id="" data-name="shop"></td>
                            <td data-id="" data-name="item"></td>
                            <td data-id="" data-name="amount"></td>
                            <td data-id="" data-name="refund"></td>
                            <td data-id="" data-name="actulCost"></td>
                            <td data-id="" data-name="bank"></td>
                            <td data-id="" data-name="notes"></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row d-flex">
        <div class="cashFooter py-2">
            <button id="cashEditBtn" class="btn btn-secondary btn-sm">編輯</button>
            <button id="cashDelBtn" class="btn btn-danger btn-sm d-none">刪除</button>
            <button id="cashDoneBtn" class="btn btn-warning btn-sm d-none">完成</button>
            <span id="cashTotal" class="total">Total：$1000</span>
        </div>

        <div class="cardFooter py-2">
            <button id="cardEditBtn" class="btn btn-secondary btn-sm">編輯</button>
            <button id="cardDelBtn" class="btn btn-danger btn-sm d-none">刪除</button>
            <button id="cardDoneBtn" class="btn btn-warning btn-sm d-none">完成</button>
            <span id="cardTotal" class="total">Total：$1000</span>
        </div>
    </div>
    <div class="footer py-2">
        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
            查看其他日期
        </button>
    </div>
</div>

@section('endJs')
</script>
<script src="/js/spending.js"></script>
@endsection