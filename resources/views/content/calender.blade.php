@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/calender.css')}}">
@endsection

@section('title','月曆')

@section('elseIcons')
<!-- setting icon -->
<span>
    <i class="fa-solid fa-gear fa-lg mt-2" style="color: #616161;"></i>
</span>
<span class="search">
    <!-- search input -->
    <input type="search" name="keyword" id="calYear" placeholder="keyword">
    <!-- select year -->
    <select>
        <option value="2023">2023</option>
    </select>
    <!-- icon search -->
    <i class="fa-solid fa-magnifying-glass" style="color: #636363;"></i>
</span>
@endsection

@section('content')
<!-- 左半邊 -->
<div class="sideBar">
    <img src="{{ asset('storage/img/QQsticker.png') }}" alt="QQ貼圖">
    <p class="year mt-3">2024</p>
    <p class="year">5784</p>

    <!-- 財務 -->
    <div class="finance">
        <p>本月總收入:</p>
        <div></div>

        <p>本月總支出:</p>
        <div></div>

        <p>結餘:</p>
        <div></div>
    </div>

    <div class="footerImg">
        <img src="{{ asset('storage/img/bell.png')}}" alt="footer圖">
    </div>
</div>

<!-- 右半邊 -->
<div class="right">

    <!-- month -->
    <div class="top d-flex">
        <p>1</p>
        <img src="{{ asset('storage/img/tree.png') }}" alt="topImg">
    </div>

    <!-- calender -->
    <div class="calender">
    </div>
</div>

<!-- footer -->
<div class="footer">
    <span>1</span>
    <span>2</span>
    <span>3</span>
    <span>4</span>
    <span>5</span>
    <span>6</span>
    <span>7</span>
    <span>8</span>
    <span>9</span>
    <span>10</span>
    <span>11</span>
    <span>12</span>
</div>

@endsection