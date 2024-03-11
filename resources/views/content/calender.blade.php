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
        <div class="sideBar py-1">
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
            @if($errors->any())
            <div id="addModal" class="modal" style="display:block">
                @else
                <div id="addModal" class="modal">
                    @endif
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel"></h5>
                            <span class="closeModal">&times;</span>
                        </div>
                        <div id="modalBody" class="modal-body">
                            <form id="modalForm">
                                <input id="modalDateInp" type="hidden" name="date"><!-- yyyymmdd -->
                                <input type="hidden" name="id">
                                <p>【 WHO'S BIRTHDAY 】</p>
                                <label for="bthdGuy" class="my-2">Name :</label>
                                <input type="text" id="bthdGuy" name="birthday_person" class="my-2"><br>
                                <p>【 MC 】</p>
                                <input id="mcStart" type="radio" value=1 name="mc">
                                <label >start&nbsp;&nbsp;</label>
                                <input id="mcEnd" type="radio" value=2 name="mc">
                                <label >end</label><br>
                                <p>【 PLAN 】</p>
                                <label >Time :</label>
                                <input type="time" name="plan_time" id="planTime">
                                <button id="clearPlanTime" class="btn btn-outline-secondary btn-sm">清除</button><br>
                                <label >Content :</label>
                                <input type="text" id="plan" name="plan"><br>
                                <p>【 ADD TAG 】</p>
                                <label >From :</label>
                                <input type="date" name="tag_from" disabled><br>
                                <label >To :</label>
                                <input type="date" name="tag_to" id="tagTo"><br>
                                <label >Tag Title :</label>
                                <input type="text" name="tag_title" id="tagTitle"><br>
                                <label >Tag Color :</label>
                                <input type="color" name="tag_color" id="tagColor"><br>
                                <label >Sticker :</label>
                                <input type="file" id="stickerInp" name="sticker" accept="image/*">
                                <div class="stickerPreGroup">
                                    <img id="stickerPre" src="#">
                                    <input type="button" value="刪除" id="clearStickerBtn" class="btn btn-outline-danger btn btn-sm"><br>
                                </div>
                                <label for="photos_link">Images Link :</ label>
                                    <input type="text" name="photos_link" id="photosLink"><br>
                                    <div class="modal-footer mt-3">
                                        <button id="modalReset" type="reset" class="btn btn-outline-secondary  btn-sm">Reset</button>
                                        <button type="button" class="btn btn-primary  btn-sm" id="addModalSubmit" data-id="">Save</button>
                                        <button type="button" class="btn btn-warning  btn-sm" id="editModalSubmit" data-id="">Save</button>
                                        <button type="button" class="btn btn-danger  btn-sm" id="delModalSubmit" data-id="">Del</button>
                                        <button type="button" class="closeModal btn btn-secondary btn-sm">Close</button>
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
                        <select id="conYearSel" class="m-1">
                            @foreach($yearList as $yearOpt)
                            <option value="{{ $yearOpt }}">{{ $yearOpt }}</option>
                            @endforeach
                        </select>
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
                                @if( $data['mc'] == 1 || $data['mc'] == 2)
                                @if( $data['week'] == '六')
                                <p class="day sat mc">
                                    @elseif($data['isHoliday']== '日' || $data['week'] == '日')
                                <p class="day sun mc">
                                    @else
                                <p class="day mc">
                                    @endif
                                    {{$data['date']}}
                                </p>
                                @else
                                @if( $data['week'] == '六')
                                <p class="day sat">
                                    @elseif($data['isHoliday']== '日' ||
                                    $data['week'] == '日')
                                <p class="day sun">
                                    @else
                                <p class="day">
                                    @endif
                                    {{$data['date']}}
                                </p>
                                @endif

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
                                <p class="wthText">{{ $data['tag_title']}}</p>
                            </div>
                            @else
                            <div id="e{{ $data['fullDate'] }}" class="endLayer">

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
        <script src="/js/module.js"></script>
        @endsection