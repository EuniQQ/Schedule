@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/journal.css')}}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">
@endsection

@section('title','每日記事')

@section('content')

<!-- menu 區 -->
@include('layouts.menu')

<div class="container">
    <div class="row d-flex content">

        <!-- banner 區 -->
        <div class="col col-12 banner">
            <!-- year -->
            <div class="yearSet d-flex">
                <div class="years">
                    <p id="adYear" class="year"></p>
                    <div>
                        <hr class="yearUnderLine">
                    </div>
                    <p id="hebrewYear" class="year"></p>
                </div>
                <!-- month -->
                <div class="month">
                    <p id="month"></p>
                </div>
            </div>

            <!-- icons-->
            <div class="icons">
                @include("icons.switch")
                @include("icons.searchKey")
                <!-- select year -->
                <select id="yearSel" class="m-1"></select>
                @include("icons.searchMonth")
                @include("icons.search")
                @include("icons.menu")
            </div>
            <!-- icons END -->
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel"></h5>
                        <button type="button" class="closeEdit btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div id="modalBody" class="modal-body">

                        <input type="hidden" name="id">

                        <label>
                            <p class="d-inline text-danger">*</p>日期 :&nbsp;
                        </label>
                        <input id="moDate" type="date" name="date"><br>

                        <label>
                            <p class="d-inline text-danger">*</p>標題 :&nbsp;
                        </label>
                        <input id="moTitle" type="text" name="title"><br>

                        <label>
                            <p class="d-inline text-danger">*</p>內文 :&nbsp;
                        </label>
                        <textarea id="moContent" name="content" minlength="30" rows="5" cols="30" placeholder="寫下美好的一天..."></textarea><br>

                        <label>照片1 :&nbsp;</label>
                        <input id="photo1" class="upload" type="file" name="photo1">
                        <div class="imgSet">
                            <img id="photo1Pre" class="photoPre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des1"><br>

                        <label>照片2 :&nbsp;</label>
                        <input id="photo2" class="upload" type="file" name="photo2">
                        <div class="imgSet">
                            <img id="photo2Pre" class="photoPre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des2"><br>

                        <label>照片3 :&nbsp;</label>
                        <input id="photo3" class="upload" type="file" name="photo3">
                        <div class="imgSet">
                            <img id="photo3Pre" class="photoPre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des3"><br>

                        <label>照片4 :&nbsp;</label>
                        <input id="photo4" class="upload" type="file" name="photo4">
                        <div class="imgSet">
                            <img id="photo4Pre" class="photoPre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des4"><br>

                        <labe>照片集連結 :</label>
                            <input type="url" name="photo_link"><br>
                    </div>
                    <div class="modal-footer">
                        <button id="saveAdd" class="save btn btn-primary">Save</button>
                        <button id="saveEdit" class="save btn btn-warning">Save</button>
                        <button id="del" class="btn btn-danger">Del</button>
                        <button type="button" class="closeEdit btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Modal END-->

        <!-- 主內文區 -->
        <img id="mainImg" src="/images/howstoday.png" data-bs-toggle="modal" data-bs-target="#editModal">

        <div id="main" class="main col col-lg-6 col-md-6 col-sm-12">
        </div>

        <footer>
            <div id="addBtn" data-bs-toggle="modal" data-bs-target="#editModal">
                <p><i class="fa-regular fa-face-smile-wink"></i>&nbsp;&nbsp;How's Today ?</p>
            </div>
        </footer>

    </div>
</div>
@endsection

@section('endJs')
<script src="/js/journal.js"></script>
<script src="/js/module.js"></script>

@endsection