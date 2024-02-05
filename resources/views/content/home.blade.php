@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/home.css')}}">
@endsection

@section('title','首頁')

@section('content')
<div class="container">
    <div class="row d-flex content">

        <!-- menu 區 -->
        @include('layouts.menu')

        <!-- 文字區 -->
        <div class="left">
            <div>
                <div class="title">
                    <p>Welcome<br>Home</p>
                </div>
                <div class="date d-flex">
                    <div class="dleft d-flex flex-column">
                        <p class="year">{{ $y }}</p>
                        <p class="month">{{ $m }}&nbsp;/</p>
                    </div>
                    <div class="dright d-flex">
                        <p class="day">{{ $d }}</p>
                        <p class="week">{{ $w }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 貼圖區 -->
        <div class="right">
            <!-- icon 區-->
            <div class="d-index icons">
                @include("icons.menu")
                @include("icons.setting")
            </div>

            <img src="{{ asset('storage/img/serprise.png') }}" alt="首頁插圖">
        </div>

    </div>
</div>
@endsection