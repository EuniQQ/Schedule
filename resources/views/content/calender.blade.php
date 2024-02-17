@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/calender.css')}}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">
@endsection

@section('style')
<style>
    .footer {
        background-color: {{$style['footer_color']}};
    }

    .singleDay:nth-child(odd) {
        background-color: {{$style['bg_color']}};
    }
</style>
@endsection

@section('title','月曆')

@section('content')
<div class="container">
    <div class="row d-flex content">


        <!-- 左半邊 -->
        <div class="sideBar py-1 ">
            @if (isset($style['main_img']) && !is_null($style['main_img']))
            <img id="mainImg" src="{{ asset($style['main_img']) }}" alt="">
            @else
            <img src="{{ asset('storage/img/QQsticker.png') }}" alt="">
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
                <img id="footerImg" src="{{ asset($style['footer_img'])}}" alt="">
                @endif
            </div>
        </div>

        <!-- 右半邊 -->
        <div class="right">
            @include('layouts.menu')

            <!-- Add Schedule Modal -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel"></h5>
                        <span class="closeModal">&times;</span>
                    </div>
                    <div class="modal-body d-flex">
                        <form id="modalForm" action="" enctype="multipart/form-data" method="post">
                            @csrf
                            <input type="hidden" name="date" value="">
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="user_id" value="{{ $userId }}">
                            <p>【 WHO'S BIRTHDAY 】</p>
                            <label for="name" class="my-2">Name :</label>
                            <input type="text" id="name" name="birthday_person" class="my-2"><br>
                            <p>【 MC 】</p>
                            <input type="radio" value=1 name="is_mc_start">
                            <label for="mc">start&nbsp;&nbsp;</label>
                            <input type="radio" value=1 name="is_mc_end">
                            <label for="mc">end</label><br>
                            <p>【 PLAN 】</p>
                            <label for="plan_time">Time :</label>
                            <input type="time" name="plan_time"><br>
                            <label for="content">Content :</label>
                            <input type="text" id="content" name="plan"><br>
                            <p>【 ADD TAG 】</p>
                            <label for="tag_from">From :</label>
                            <input type="date" name="tag_from"><br>
                            <label for="tag_to">To :</label>
                            <input type="date" name="tag_to"><br>
                            <label for="tagTitle">Tag Title :</label>
                            <input type="text" name="tag_title"><br>
                            <label for="tag_color">Tag Color :</label>
                            <input type="color" name="tag_color"><br>
                            <label for="sticker">Sticker :</label>
                            <input type="file" id="stickerInp" name="sticker" accept="image/*">
                            <img id="stickerPre" src="#" alt=""><br>
                            <label for="photos_link">Images Link :</ label>
                                <input type="text" name="photos_link"><br>
                                <input type="reset">
                                <div class="modal-footer">
                                    <button type="button" class="closeModal btn btn-secondary">Close</button>
                                    <button type="submit" class="btn btn-primary" id="addModalSubmit" data-id="">save</button>
                                    <button type="submit" class="btn btn-warning" id="editModalSubmit" data-id="">save</button>
                                    <button type="button" class="btn btn-danger" id="delModalSubmit" data-id="">del</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Schedule Modal END-->


            <!-- Offcanvas : Setting Style -->
            <div class="offcanvas offcanvas-end styleSettingOffcvs" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel"></h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body styleOffcanvas">
                    <!-- 主視覺 -->
                    <div class="d-flex">
                        <p>Main IMG :</p>
                        <input id="mainImgInp" class="imgInput" type="file" accept="image/*" name="main_img" onchange="previewSelect(event)">
                    </div>
                    <div class="imgSet">
                        <img id="mainImgPre" src="{{ asset($style['main_img']) }}" alt="">
                    </div>

                    <!-- header img -->
                    <div class="d-flex">
                        <p>Header IMG :</p>
                        <input id="headerImgInp" class="imgInput" type="file" accept="image/*" name="header_img" onchange="previewSelect(event)">
                    </div>
                    <div class="imgSet">
                        <img id="headerImgPre" src="{{ asset($style['header_img']) }}" alt="">
                    </div>

                    <!-- footer img -->
                    <div class="d-flex ">
                        <p>Footer IMG :</p>
                        <input id="footerImgInp" class="imgInput" type="file" accept="image/*" name="footer_img" onchange="previewSelect(event)">
                    </div>
                    <div class="imgSet">
                        <img id="footerImgPre" src="{{ asset($style['footer_img']) }}" alt="">
                    </div>

                    <div class="colorSet">
                        <!-- calender background -->
                        <p class="mx-2">Calender Color :</p>
                        <input id="calColorInp" type="color" value="{{ asset($style['bg_color']) }}" name="bg_color">
                        <span id="delCalColor">Delete</span>
                    </div>
                    <div class="colorSet">
                        <!-- footer color -->
                        <p class="mx-2">Footer Color :</p>
                        <input id="ftColorInp" type="color" value="{{ asset($style['footer_color']) }}" name="footer_color">
                        <span id="delFtColor">Delete</span>
                    </div>

                    <div class="d-index text-center mt-4">
                        <button id="resetStyleBtn" type="reset" class="secBtn btn btn-secondary px-2 mx-1" data-userId="{{ $userId }}" data-id="{{ $style['id'] }}">重置
                        </button>
                        <button id="offcanvasSmt" type="button" class="warBtn btn btn-warning px-2 mx-1" data-year="{{ $year }}" data-month="{{ $month }}" data-userId="{{ $userId }}" data-id="{{ $style['id'] }}">送出</button>
                    </div>
                </div>
            </div>
            <!-- Offcanvas : Setting END -->


            <!-- month -->
            <div class="month d-flex">
                <p>{{ $month }}</p>
                @if(isset($style['header_img'] ) && !is_null($style['header_img']))
                <img id="headerImg" src="{{ asset($style['header_img']) }}" alt="">
                @endif

                <!-- icon 區-->
                <div class="search d-flex">
                    @include("icons.menu")
                    @include("icons.setting")
                    @include("icons.searchKey")
                    @include("icons.searchYear")
                    @include("icons.searchMonth")
                    @include("icons.search")
                </div>
                <!-- icons END -->
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
                <div id="i{{ $data['fullDate'] }}" class="singleDay todayBg" data-id="{{ $data['id'] }}">
                    @else
                    <div id="i{{ $data['fullDate'] }}" class="singleDay" data-id="{{ $data['id'] }}">
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

                            <div class="d-flex bthdSet">
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
                            @if( !empty($data['date']))
                            <!-- hover plusIcon -->
                            <i id="{{ $data['fullDate'] }}" class="fa-solid fa-circle-plus fa-2xl plusIcon" style="color: #dedede;" data-id="{{ $data['id'] }}"></i>
                            @endif

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

                        @if(isset($data['tag_color']) && !empty($data['tag_color']))
                        <div class="endLayer" style="background-color:{{$data['tag_color']}}">
                            @if(!empty($data['sticker']))
                            <img class="sticker" src="{{ $data['sticker'] }}" alt="sticker">
                            @endif
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
        </div>
    </div>
    @endsection

    @section('endJs')
    <script src="/js/calender.js"></script>
    <script src="/js/calStyleSetting.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
    @endsection