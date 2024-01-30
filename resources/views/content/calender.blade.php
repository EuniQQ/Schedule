@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/calender.css')}}">
@endsection

@section('title','月曆')

@section('content')
<div class="row d-flex content">


    <!-- 左半邊 -->
    <div class="sideBar py-1 ">
        @if (isset($style['main_img']) && !is_null($style['main_img']))
        <img src="{{ asset($style['main_img']) }}" alt="主視覺">
        @else
        <img src="{{ asset('storage/img/QQsticker.png') }}" alt="主視覺">
        @endif
        <p class="year mt-3">{{$year}}</p>
        <p>
            <hr class="yearUnderLine">
        </p>
        <p class="year">{{$hebrewYear}}</p>


        <!-- 財務 -->
        <div class="finance">
            <p>本 月 總 收 入:</p>
            <div>{{$income}}</div>

            <p>本 月 總 支 出:</p>
            <div>{{$expense}}</div>

            <p>結 餘:</p>
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
        @include('layouts.menu')

        <!-- month -->
        <div class="month d-flex">
            <p>{{ $month }}</p>
            @if(isset($style['header_img'] ) && !is_null($style['header_img']))
            <img src="{{ asset($style['header_img']) }}" alt="headerImg">
            @endif

            <!-- icon 區-->
            <div class="search d-flex">
                <!-- menu icon -->
                <button class="btn menuIcon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBackdrop" aria-controls="offcanvasWithBackdrop">
                    <i class="fa-solid fa-bars fa-lg" style="color: #616161;"></i>
                </button>

                <!-- setting icon -->
                <button class="btn settingIcon">
                    <i class="fa-solid fa-gear fa-lg " style="color: #616161;"></i>
                </button>

                <!-- search input -->
                <input type="search" name="keyword" id="calYear" placeholder="keyword">

                <!-- select year -->
                <select class="m-1">
                    <option value="{{$year}}">{{$year}}</option>
                </select>
                <!-- select month -->
                <select class="m-1 ">
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
                <button class="btn" type="button">
                    <i class="fa-solid fa-magnifying-glass" style="color: #636363;"></i>
                </button>
            </div>
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
            <div id="{{ $data['date'] }}" class="singleDay todayBg" data-id="{{ $data['id'] }}">
                @else
                <div id="{{ $data['date'] }}" class="singleDay" data-id="{{ $data['id'] }}">
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
                        <!-- hover plusIcon -->
                        <!-- <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal"> -->
                        <i class="fa-solid fa-circle-plus fa-2xl plusIcon" style="color: #dedede;"></i>
                        <!-- </button> -->

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
                    <div class="endLayer" style="background-color: {{$data['tag_color']}} ">
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
            <a href="/calender/{{$year}}/01">1</a>
            <a href="/calender/{{$year}}/02">2</a>
            <a href="/calender/{{$year}}/03">3</a>
            <a href="/calender/{{$year}}/04">4</a>
            <a href="/calender/{{$year}}/05">5</a>
            <a href="/calender/{{$year}}/06">6</a>
            <a href="/calender/{{$year}}/07">7</a>
            <a href="/calender/{{$year}}/08">8</a>
            <a href="/calender/{{$year}}/09">9</a>
            <a href="/calender/{{$year}}/10">10</a>
            <a href="/calender/{{$year}}/11">11</a>
            <a href="/calender/{{$year}}/12">12</a>
        </div>


        <!-- modal -->
        <!-- Button trigger modal -->




        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">新增行程</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <p>【 WHO'S BIRTHDAY 】</p>
                            <label for="name" class="my-2">Name :</label>
                            <input type="text" id="name" name="birthday_person" class="my-2"><br>

                            <p>【 MC 】</p>
                            <input type="radio" id="mc_start" value="mc_start" name="mc">
                            <label for="mc">start&nbsp;&nbsp;</label>
                            <input type="radio" id="mc_end" value="mc_end" name="mc">
                            <label for="mc">end</label><br>

                            <p>【 PLAN 】</p>
                            <label for="plan_time">Time :</label>
                            <input type="text" id="plan_time" name="plan_time"><br>
                            <label for="content">Content :</label>
                            <input type="text" id="content" name="content"><br>

                            <p>【 ADD TAG 】</p>
                            <label for="tag_from">From :</label>
                            <input type="date" id="tag_from" name="tag_from"><br>
                            <label for="tag_to">To :</label>
                            <input type="date" id="tag_to" name="tag_to"><br>
                            <label for="tag_title">Tag Title :</label>
                            <input type="text" id="tag_title" name="tag_title"><br>
                            <label for="tag_color">Tag Color :</label>
                            <input type="tag_color" id="tag_color" name="tag_color"><br>
                            <label for="sticker">Sticker :</label>
                            <input type="file" id="sticker" name="sticker" accept="image/gif,image/jpeg,image/png" data-target="preview_upload_sticker">
                            <img id="preview_upload_sticker" src="#" data-target="preview_upload_sticker"><br>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('endJs')
    <link rel="stylesheet" href="resources/js/calender.js">
    @endsection