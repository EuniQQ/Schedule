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
                    @foreach($yearList as $year)
                    <option value=$year>{{$year}}</option>
                    @endforeach
                </select>
                @include("icons.search")
                @include("icons.menu")
            </span>
        </div>

        <table>
            <thead>
                <tr class="donateRow">
                    <th scope="col" class="date">日&nbsp;期</th>
                    <th scope="col" class="reason">事&nbsp;由</th>
                    <th scope="col" class="payer">給付者</th>
                    <th scope="col" class="amount">金&nbsp;額</th>
                    <th scope="col" class="bank">銀&nbsp;行</th>
                    <th scope="col" class="donate">十一金額</th>
                    <th scope="col" class="dnDate">十一日期</th>
                    <th scope="col" class="dObj">奉獻單位</th>
                    <th scope="col" class="remark">備&nbsp;註</th>
                    <th scope="col" class="edit"></th>
                </tr>
            </thead>

            <tbody class="incomeTbody">
                @foreach($records as $income)
                <tr>
                    <td>{{ $income->date }}</td>
                    <td>{{ $income->content }}</td>
                    <td>{{ $income->payer }}</td>
                    <td>{{ $income->amount }}</td>
                    <td>{{ $income->bank }}</td>
                    <td>{{ $income->tithe }}</td>
                    <td>{{ $income->tithe_date }}</td>
                    <td>{{ $income->tithe_obj }}</td>
                    <td>{{ $income->notes }}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <span class="total">Total：${{ $total }}</span>

            <!-- Button trigger modal -->
            <button type="button" id="npoBtn" class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                NPO資訊
            </button>

            <button type="button" id="addIncomeBtn" class="btn" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
                新增
            </button>

            <!-- NPO info Modal -->
            <div id="staticBackdrop" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                            <button type="button" class="btn-close closeNpoModal" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table id="npoTable" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">方式</th>
                                        <th scope="col">教會/機構名稱</th>
                                        <th scope="col">帳號/刷卡連結</th>
                                        <th scope="col">代碼</th>
                                        <th scope="col">金融機構</th>
                                        <th scope="col">填寫表單</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="npoTbody">
                                    <!-- <tr> -->
                                    <!-- <td scope="row">1</td> -->
                                    <!-- <td>匯款</td> -->
                                    <!-- <td>社團法人基督教新竹靈糧堂</td> -->
                                    <!-- <td>0061541-0184795</td> -->
                                    <!-- <td>竹東二重埔郵局 700</td> -->
                                    <!-- <td><a href="">網址</a></td> -->
                                    <!-- <td> -->
                                    <!-- <i class="fa-solid fa-pen mx-2"></i> -->
                                    <!-- <i class="fa-solid fa-trash"></i> -->
                                    <!-- </td> -->
                                    <!-- </tr> -->
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <form id="editNpoForm" action="{{ route('income.createNpo') }}" class="addNpo text-start" method="post">
                                @csrf
                                <div class="mb-2">
                                    <label for="method">
                                        <span class="text-danger">*</span><span>方式</span>
                                    </label>
                                    <select name="method" id="method">
                                        <option value=1>匯款</option>
                                        <option value=2>刷卡</option>
                                        <option value=3>其他</option>
                                    </select>
                                    &nbsp;&nbsp;

                                    <label for="name">
                                        <span class="text-danger">*</span>
                                        <span>名稱</span>
                                    </label>
                                    <input type="text" id="name" name="name">
                                    &nbsp;&nbsp;

                                    <label for="account">帳號</label>
                                    <input type="text" id="account" name="account">
                                    &nbsp;&nbsp;

                                    <label for="code">代碼</label>
                                    <input type="text" id="code" name="code" size="4">
                                    &nbsp;&nbsp;<br>
                                </div>

                                <div class="mb-2">
                                    <label for="bank">金融機構</label>
                                    <input type="text" id="bank" name="bank" size="10">
                                    &nbsp;&nbsp;

                                    <label for="pay_on_line">線上刷卡連結</label>
                                    <input type="url" id="pay_on_line" name="pay_on_line" size="50">
                                    &nbsp;&nbsp;<br>
                                </div>

                                <div class="mb-2">
                                    <label for="form_link">表單連結</label>
                                    <input type="url" id="form_link" name="form_link" size="50">
                                    &nbsp;&nbsp;

                                    <button type="reset" class="btn btn-secondary btn-sm mx-2">清除</button>
                                    <button type="submit" class="btn btn-warning btn-sm">送出</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- NPO info Modal END-->

            <!-- Add Income Data Modal -->
            <div id="addIncomeModal" class="modal fade ">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">新增收入</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('income.create') }}" method="POST" class="formGroup">
                                @csrf
                                <div class="my-3">
                                    <label for="date"><span>*</span>日期：</label>
                                    <input type="date" name="date" id="date" value="{{ old('date') }}">
                                    @if($errors->has('date'))
                                    <p class="text-danger">{{ $errors->first('date')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="content"><span>*</span>事由：</label>
                                    <input type="text" name="content" id="content" value="{{ old('content') }}">
                                    @if($errors->has('content'))
                                    <p class="text-danger">{{ $errors->first('content')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="payer"><span>*</span>支付者：</label>
                                    <input type="text" name="payer" id="payer" value="{{ old('payer') }}">
                                    @if($errors->has('payer'))
                                    <p class="text-danger">{{ $errors->first('payer')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="amount"><span>*</span>金額：</label>
                                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}">
                                    @if($errors->has('amount'))
                                    <p class="text-danger">{{ $errors->first('amount')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="bank"><span>*</span>銀行：</label>
                                    <input type="text" name="bank" id="bank" value="{{ old('bank') }}">
                                    @if($errors->has('bank'))
                                    <p class="text-danger">{{ $errors->first('bank')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="tithe">十一金額：</label>
                                    <input type="number" name="tithe" id="tithe" value="{{ old('tithe') }}">
                                    @if($errors->has('tithe'))
                                    <p class="text-danger">{{ $errors->first('tithe')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="tithe_date">十一日期：</label>
                                    <input type="date" name="tithe_date" id="tithe_date" value="{{ old('tithe_date') }}">
                                    @if($errors->has('tithe_date'))
                                    <p class="text-danger">{{ $errors->first('tithe_date')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="tithe_obj">奉獻單位：</label>
                                    <input type="text" name="tithe_obj" id="tithe_obj" value="{{ old('tithe_obj') }}">
                                    @if($errors->has('tithe_obj'))
                                    <p class="text-danger">{{ $errors->first('tithe_obj')}}</p>
                                    @endif
                                </div>

                                <div class="my-3">
                                    <label for="notes">備註：</label>
                                    <input type="text" name="notes" id="notes" value="{{ old('notes') }}">
                                    @if($errors->has('notes'))
                                    <p class="text-danger">{{ $errors->first('notes')}}</p>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Income Data Modal END-->
        </div>
    </div>
</div>

@section('endJs')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors -> any())
        var addIncomeModal = new bootstrap.Modal(document.getElementById('addIncomeModal'));
        addIncomeModal.show();
        @endif
    });
</script>
<script src="/js/income.js"></script>
@endsection