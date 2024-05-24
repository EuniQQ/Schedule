@extends('layouts/layout')

@section('links')
<script src="/js/doubleClick.js"></script>
@endsection

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
        <div class="capsuleTag">{{ $args['year'] }} ‧ {{ $args['month'] }}</div>
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
                    @foreach($args['cashData'] as $cash)
                    <tr class="text-center">
                        <td data-name="checkbox">
                            <input class="cashChbox d-none" type="checkbox" value="{{ $cash->id }}">
                        </td>
                        <td data-id="{{ $cash->id }}" data-name="date">
                            {{ $cash->date }}
                        </td>
                        <td data-id="{{ $cash->id }}" data-name="weekDay">
                            {{ $cash->weekDay }}
                        </td>
                        <td data-id="{{ $cash->id }}" data-name="item">
                            {{ $cash->item }}
                        </td>
                        <td data-id="{{ $cash->id }}" data-name="amount">
                            {{ $cash->amount }}
                        </td>
                    </tr>
                    @endforeach

                    <?php
                    for ($i = 0; $i < $args['blankCash']; $i++) {
                    ?>
                        <tr class="text-center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="cashFooter py-2">
                <button id="cashAddBtn" class="addbtn btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addCashModal">新增
                </button>
                <button id="cashEditBtn" class="btn btn-secondary btn-sm">編輯</button>
                <button id="cashDelBtn" class="btn btn-danger btn-sm d-none">刪除</button>
                <button id="cashDoneBtn" class="btn btn-warning btn-sm d-none">完成</button>
                <span id="cashTotal" class="total">&nbsp;Total：$ {{$args['cashSum']}}</span>
            </div>
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
                        <th scope="col" class="actulPay">實支</th>
                        <th scope="col" class="bank">卡別</th>
                        <th scope="col" class="notes">備註</th>
                    </tr>
                </thead>

                <tbody class="cardTbody">
                    @foreach($args['cardData'] as $card)
                    <tr class="text-center">
                        <td data-name="checkbox">
                            <input class="cardChbox d-none" type="checkbox" value="{{ $card->id}}">
                        </td>
                        <td data-name="date" data-id="{{ $card->id}}">
                            {{ $card->date }}
                        </td>
                        <td data-name="weekDay" data-id="{{ $card->id}}">
                            {{ $card->weekDay }}
                        </td>
                        <td data-name="shop" data-id="{{ $card->id}}">
                            {{ $card->shop }}
                        </td>
                        <td data-name="item" data-id="{{ $card->id}}">
                            {{ $card->item }}
                        </td>
                        <td data-name="amount" data-id="{{ $card->id}}">
                            {{ $card->amount }}
                        </td>
                        <td data-name="refund" data-id="{{ $card->id}}">
                            {{ $card->refund }}
                        </td>
                        <td data-name="actual_pay" data-id="{{ $card->id}}">
                            {{ $card->actual_pay }}
                        </td>
                        <td data-name="bank" data-id="{{ $card->id}}">
                            {{ $card->bank }}
                        </td>
                        <td data-name="notes" data-id="{{ $card->id}}">
                            {{ $card->notes }}
                        </td>
                    </tr>
                    @endforeach
                    <?php
                    for ($i = 0; $i < $args['blankCard']; $i++) {
                    ?>
                        <tr class="text-center">
                            <td data-name="checkbox"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="cardFooter py-2">
                <button id="cardAddBtn" class="addbtn btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addCardModal">新增
                </button>
                <button id="cardEditBtn" class="btn btn-secondary btn-sm">編輯</button>
                <button id="cardDelBtn" class="btn btn-danger btn-sm d-none">刪除</button>
                <button id="cardDoneBtn" class="btn btn-warning btn-sm d-none">完成</button>
                <span id="cardTotal" class="total">&nbsp;Total：$ {{$args['cardSum'] }}</span>
            </div>
        </div>
    </div>

    <div class="footer py-2">
        <button id="otherDateBtn" type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#otherDateModal">
            查看其他日期
        </button>
    </div>
</div>


<!-- 選取其他日期 Modal -->
<div id="otherDateModal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="cloBtn">
                    <button type="button" class=" btn-close mb-4 " data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('spending.index') }}" method="get">
                    @csrf
                    <div class="main">
                        <p class="title">
                            欲 查 詢 支 出 明 細 年 月 份：
                        </p>
                        <label for="selY">年&nbsp;&nbsp;份：</label>
                        <select name="selY" id="selY">
                            @foreach($args['yearList'] as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select><br>
                        <label for="selM">月&nbsp;&nbsp;份：</label>
                        <select name="selM" id="selM" class="my-2">
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="my-3 btn btn-warning">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- 新增 CASH Modal -->
<div class="modal fade" id="addCashModal" tabindex="-1" aria-labelledby="addCashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增現金支出</h5>
                <button type="button" class="modalClose btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('cash.create') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="fltCashDate" placeholder="" name="date" value="{{ old('date') }}">
                        <label for="fltCashDate">
                            <span class="text-danger">*&nbsp;</span>DATE
                        </label>
                        <p class='text-danger px-3'>
                            <small>{{ $errors->first('date') }}</small>
                        </p>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fltCashItem" placeholder="" name="item" value="{{ old('item') }}">
                        <label for="fltCashItem">
                            <span class="text-danger">*&nbsp;</span>ITEM
                        </label>
                        <p class='text-danger px-3'>
                            <small>{{ $errors->first('item') }}</small>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="fltCashAmount" placeholder="" name="amount" value="{{ old('amount') }}">
                        <label for="fltCashAmount">
                            <span class="text-danger">*&nbsp;</span>AMOUNT
                        </label>
                        <p class='text-danger px-3'>
                            <small>{{ $errors->first('amount') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modalClose btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="modalSaveBtn btn btn-warning">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- 新增 CARD Modal -->
<div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增刷卡支出</h5>
                <button type="button" class="modalClose btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('card.create') }}" method="post">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="fltCardDate" placeholder="刷卡日期" name="date" value="{{ old('date') }}">
                        <label for="fltCardDate">
                            <span class="text-danger">*&nbsp;</span>DATE
                        </label>
                        <p class="text-danger px-3">
                            <small>{{ $errors->first('date')}}</small>
                        </p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fltCardShop" placeholder="商家" name="shop" value="{{ old('shop') }}">
                        <label for="fltCardShop">
                            <span class="text-danger">*&nbsp;</span>SHOP
                        </label>
                        <p class="text-danger px-3">
                            <small>{{ $errors->first('shop')}}</small>
                        </p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fltCardItem" placeholder="項目" name="item" value="{{ old('item') }}">
                        <label for="fltCardItem">
                            <span class="text-danger">*&nbsp;</span>ITEM
                        </label>
                        <p class="text-danger">
                            <small>{{ $errors->first('item')}}</small>
                        </p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="fltCardAmount" placeholder="金額" name="amount" value="{{ old('amount') }}">
                        <label for="fltCardAmount">
                            <span class="text-danger">*&nbsp;</span>AMOUNT
                        </label>
                        <p class="text-danger px-3">
                            <small>{{ $errors->first('amount')}}</small>
                        </p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="fltCardRefund" placeholder="退款金額" name="refund" value="{{ old('refund') }}">
                        <label for="fltCardRefund">REFUND</label>
                        <p class="text-danger">
                            <small>{{ $errors->first('refund')}}</small>
                        </p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="fltCardActulPay" placeholder="實際消費" name="actual_pay" value="{{ old('actual_pay') }}">
                        <label for="fltCardActulPay">ACTUL PAY</label>
                        <p class="text-danger px-3">
                            <small>{{ $errors->first('actual_pay')}}</small>
                        </p>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fltCardBank" placeholder="發卡銀行" name="bank" value="{{ old('bank') }}">
                        <label for="fltCardBank">
                            <span class="text-danger">*&nbsp;</span>BANK
                        </label>
                        <p class="text-danger px-3">
                            <small>{{ $errors->first('bank')}}</small>
                        </p>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" id="fltCardNotes" placeholder="備註" name="notes" value="{{ old('notes') }}">
                        <label for="fltCardNotes">NOTES</label>
                        <p class="text-danger px-3">
                            <small>{{ $errors->first('notes')}}</small>
                        </p>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="modalClose btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="modalSaveBtn btn btn-warning">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


@section('endJs')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors -> any())
        const modalId = "{{Session::get('modalId')}}";
        console.log(modalId);
        var addIncomeModal = new bootstrap.Modal(document.getElementById(modalId));
        addIncomeModal.show();
        @endif
    });
</script>
<script src="/js/spending.js"></script>
@endsection