@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/calender.css')}}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">
@endsection

@section('title','月曆')

@section('content')
<div class="container">
    <div class="row d-flex content">

        <!-- 左半邊 -->
        <div id="sideBar" class="sideBar py-1">
            <p id="calWesYear" class="year mt-3"></p>
            <p>
                <hr class="yearUnderLine">
            </p>
            <p id="calHebrewYear" class="year"></p>
            <!-- mainImg -->

            <!-- 財務 -->
            <div class="finance">
                <p>本 月 總 收 入:</p>
                <div id="calIncome"></div>

                <p>本 月 總 支 出:</p>
                <div id="calExp"></div>

                <p>結 餘:</p>
                <div id="calBal"></div>
            </div>

            <!-- footerImg -->
        </div>

        <!-- 右半邊 -->
        <div class="right">
            @include('layouts.menu')

            <!-- Add Schedule Modal -->
            <div id="addModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel"></h5>
                        <button type="button" class="closeModal btn-close"></button>
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
                            <label>start&nbsp;&nbsp;</label>
                            <input id="mcEnd" type="radio" value=2 name="mc">
                            <label>end</label><br>
                            <p>【 PLAN 】</p>
                            <label>Time :</label>
                            <input type="time" name="plan_time" id="planTime">
                            <button id="clearPlanTime" class="btn btn-outline-secondary btn-sm">清除</button><br>
                            <label>Content :</label>
                            <input type="text" id="plan" name="plan"><br>
                            <p>【 ADD TAG 】</p>
                            <label>From :</label>
                            <input type="date" name="tag_from" disabled><br>
                            <label>To :</label>
                            <input type="date" name="tag_to" id="tagTo"><br>
                            <label>Tag Title :</label>
                            <input type="text" name="tag_title" id="tagTitle"><br>
                            <label>Tag Color :</label>
                            <input type="color" name="tag_color" id="tagColor"><br>
                            <label>Sticker :</label>
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
                    <div>
                        <label>Main IMG : &nbsp;</label>
                        <input id="mainImgInp" class="imgInput" type="file" accept="image/*" name="main_img" onchange="previewSelect(event)">
                        <div class="imgSet">
                            <img id="mainImgPre" src="">
                        </div>
                    </div>

                    <!-- header img -->
                    <div>
                        <label>Header IMG : &nbsp;</label>
                        <input id="headerImgInp" class="imgInput" type="file" accept="image/*" name="header_img" onchange="previewSelect(event)">
                        <div class="imgSet">
                            <img id="headerImgPre" src="">
                        </div>
                    </div>

                    <!-- footer img -->
                    <div>
                        <label>Footer IMG : &nbsp;</label>
                        <input id="footerImgInp" class="imgInput" type="file" accept="image/*" name="footer_img" onchange="previewSelect(event)">
                        <div class="imgSet">
                            <img id="footerImgPre" src="">
                        </div>
                    </div>

                    <!-- calender background -->
                    <div class="colorSet">
                        <label>Calender Color :&nbsp; </label>
                        <input id="calColorInp" type="color" value="" name="bg_color">
                        <span id="delCalColor">Delete</span>
                    </div>

                    <!-- footer color -->
                    <div class="colorSet">
                        <label>Footer Color :&nbsp; </label>
                        <input id="ftColorInp" type="color" value="" name="footer_color">
                        <span id="delFtColor">Delete</span>
                    </div>

                    <!-- buttons -->
                    <div class="d-index text-center mt-4">
                        <button id="resetStyleBtn" type="reset" class=" btn btn-secondary px-2 mx-1" data-id="">重置
                        </button>
                        <button id="offcanvasSmt" type="button" class=" btn btn-warning px-2 mx-1" data-year="" data-month="" data-userId="" data-id="">送出</button>
                    </div>
                </div>
            </div>
            <!-- Offcanvas : Setting END -->


            <!-- month -->
            <div id="monthSec" class="month d-flex">
                <p id="headerMonth"></p>

                <!-- header img -->

                <!-- icon 區-->
                <div id="iconSec" class="search d-flex">
                    @include("icons.menu")
                    @include("icons.setting")
                    <select id="yearSel" class="m-1"></select>
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
            <div id="calMain" class="calender d-flex">

            </div>

        </div>
        <!-- footer -->
        <div id="footer" class="footer">
            <a class="footerAtag" href="">1</a>
            <a class="footerAtag" href="">2</a>
            <a class="footerAtag" href="">3</a>
            <a class="footerAtag" href="">4</a>
            <a class="footerAtag" href="">5</a>
            <a class="footerAtag" href="">6</a>
            <a class="footerAtag" href="">7</a>
            <a class="footerAtag" href="">8</a>
            <a class="footerAtag" href="">9</a>
            <a class="footerAtag" href="">10</a>
            <a class="footerAtag" href="">11</a>
            <a class="footerAtag" href="">12</a>
        </div>

    </div>
    @endsection

    @section('endJs')
    <script src="/js/calender.js"></script>
    <script src="/js/calStyleSetting.js"></script>
    <script src="/js/module.js"></script>
    @endsection