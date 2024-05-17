@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">

@endsection

@section('title','每月事紀')

@section('content')
<div class="content">

    <div>
        <!-- menu 區 -->
        @include('layouts.menu')
    </div>

    <div class="header d-flex">
        <div>
            @include("icons.menu")
            <span id="yearTag" class="capsuleTag"></span>
        </div>

        <div class="searchGroup">
            <select id="yearSel" class="m-1">
                <!-- <option value=""></option> -->
            </select>
            @include("icons.search")
        </div>
    </div>

    <div class="container-fruid">
        <div id="main" class="row">


        </div>
    </div>
</div>
@endsection

@section('endJs')
<script src="/js/event.js"></script>
<script src="/js/module.js"></script>
@endsection


<!-- 半年 -->
<!-- <div class="group col-lg-6 col-md-12 col-sm-12"> -->
<!-- 單月 -->
<!-- <div class="monthGroup"> -->
<!-- <div class="month"> -->
<!-- <p>1</p> -->
<!-- </div> -->
<!-- <div class="row textGroup"> -->
<!-- <div class="col text"> -->
<!-- <div class="tt">123</div> -->
<!-- <div class="dotLine"></div> -->
<!-- <div class="tt">123123123</div> -->
<!-- <div class="dotLine"></div> -->
<!-- <div class="tt">大大</div> -->
<!-- <div class="dotLine"></div> -->
<!-- </div> -->
<!-- <div class="text col"> -->
<!-- <div class="tt"></div> -->
<!-- <div class="dotLine"></div> -->
<!-- <div class="tt"></div> -->
<!-- <div class="dotLine"></div> -->
<!-- <div class="tt"></div> -->
<!-- <div class="dotLine"></div> -->
<!-- </div> -->
<!-- </div> -->
<!-- </div> -->
<!-- </div> -->