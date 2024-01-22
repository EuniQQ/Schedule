@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/home.css')}}">
@endsection

@section('title','首頁')

@section('elseIcons')
<!-- 設定 icon -->
<i class="fa-solid fa-gear fa-lg mt-2" style="color: 
#616161;"></i>
@endsection

@section('content')
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
    <img src="{{ asset('storage/img/serprise.png') }}" alt="首頁插圖">
</div>
@endsection