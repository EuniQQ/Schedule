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
        <option value="{{$year}}">{{$year}}</option>
    </select>
    <select>
        <option value="01">1</option>
        <option value="02">2</option>
        <option value="03">3</option>
        <option value="04">4</option>
        <option value="05">5</option>
        <option value="06">6</option>
        <option value="07">7</option>
        <option value="08">8</option>
        <option value="09">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
    </select>
    <!-- icon search -->
    <i class="fa-solid fa-magnifying-glass" style="color: #636363;"></i>
</span>
@endsection

@section('content')
<div class="row d-flex content">

    <!-- 左半邊 -->
    <div class="sideBar">
        @if (isset($style['main_img']) && !is_null($style['main_img']))
        <img src="{{ asset($style['main_img']) }}" alt="主視覺">
        @else
        <img src="{{ asset('storage/img/QQsticker.png') }}" alt="主視覺">
        @endif
        <p class="year mt-3">{{$year}}</p>
        <p class="year">{{$hebrewYear}}</p>

        <!-- 財務 -->
        <div class="finance">
            <p>本月總收入:</p>
            <div>{{$income}}</div>

            <p>本月總支出:</p>
            <div>{{$expense}}</div>

            <p>結餘:</p>
            <div>{{$balance}}</div>
        </div>

        <div class="footerImg">
            @if (isset($style['footer_img']) && !is_null($style['footer_img']))
            <img src="{{ asset($style['footer_img'])}}" alt="footer圖">
            @endif
        </div>
    </div>

    <!-- 右半邊 -->
    <div class="right">

        <!-- month -->
        <div class="month d-flex">
            <p>{{ $month }}</p>
            @if(isset($style['header_img'] ) && !is_null($style['header_img']))
            <img src="{{ asset($style['header_img']) }}" alt="headerImg">
            @endif
        </div>

        <div class="weekDays">
            <p>MON</p>
            <p>TUE</p>
            <p>WED</p>
            <p>TUR</p>
            <p>FRI</p>
            <p class="sat">SAT</p>
            <p class="sun">SUN</p>
        </div>

        <!-- calender -->
        <div class="calender d-flex">
            @foreach($calender as $data)
            <!-- 單一格子 -->
            @if ($month == $thisMonth && $data['date'] == $today)
            <div class="singleDay" style="background-color:rgba(255, 238, 150, 1)">
                @else
                <div class="singleDay">
                    @endif

                    <div class="firstLayer d-flex">
                        @if( $data['week'] == '六')
                        <p class="day sat">
                            @elseif($data['isHoliday']== '日' || $data['week'] == '日')
                        <p class="day sun">
                            @else
                        <p class="day">
                            @endif
                            {{$data['date']}}
                        </p>

                        <div class="d-flex">
                            @if (isset($data['birthday_person']) && $data['birthday_person'] !== "")
                            <i class="fa-solid fa-cake-candles" style="color:#c200bb">&nbsp;</i>

                            <span class="bthd">
                                <!-- 壽星 -->
                                {{ $data['birthday_person'] }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="middleLayer">
                    <i class="fa-solid fa-circle-plus fa-2xl plusIcon" style="color: #dedede;"></i>
                        @if (isset($data['description']) && $data['description']
                        !== "")
                        <li class="calDes">
                            {{ $data['description'] }}
                        </li>
                        @endif
                        @if (isset($data['plan']) && $data['plan'] !== "")
                        <li class="calPlan">
                            <!-- 行程紀錄 -->
                            {{ $data['plan_time'] }}&nbsp;{{ $data['plan'] }}
                        </li>
                        @endif
                    </div>

                    @if(isset($data['tag_color']) && !is_null($data['tag_color']))
                    <div class="endLayer" style="background-color: {{ $data['tag_color'] }} ">
                        <img class="tagImg" src=" {{ $data['sticker'] }}??'' " alt="">
                        <p class="tagText">{{ $data['tag_title']}}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>


        <!-- footer -->
        <div class="footer">
            <span></span><a href="/calender/{{$year}}/01">1</a>
            <span></span><a href="/calender/{{$year}}/02">2</a>
            <span></span><a href="/calender/{{$year}}/03">3</a>
            <span></span><a href="/calender/{{$year}}/04">4</a>
            <span></span><a href="/calender/{{$year}}/05">5</a>
            <span></span><a href="/calender/{{$year}}/06">6</a>
            <span></span><a href="/calender/{{$year}}/07">7</a>
            <span></span><a href="/calender/{{$year}}/08">8</a>
            <span></span><a href="/calender/{{$year}}/09">9</a>
            <span></span><a href="/calender/{{$year}}/10">10</a>
            <span></span><a href="/calender/{{$year}}/11">11</a>
            <span></span><a href="/calender/{{$year}}/12">12</a>
        </div>
    </div>
    @endsection