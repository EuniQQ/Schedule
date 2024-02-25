@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{asset('css/journal.css')}}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">
@endsection

@section('title','每日記事')

@section('content')
<div class="container">


    <div class="row d-flex content">

        <!-- menu 區 -->
        @include('layouts.menu')


        <!-- banner 區 -->
        <div class="col banner">
            <!-- year -->
            <div class="yearSet d-flex">
                <div class="years">
                    <p id="adYear" class="year">2024</p>
                    <div>
                        <hr class="yearUnderLine">
                    </div>
                    <p id="hebrewYear" class="year">5783</p>
                </div>
                <!-- month -->
                <div class="month">
                    <p id="month">12</p>
                </div>
            </div>

            <!-- icons-->
            <div class="icons">
                <div class="search d-flex">
                    <!-- switch icon -->
                    <div class="form-check form-switch m-1">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label switchLabel" for="flexSwitchCheckDefault">Edit Mode</label>
                    </div>
                </div>
                @include("icons.searchKey")
                <!-- select year -->
                <select class="m-1">
                    <option value="2024">2024</option>
                </select>
                @include("icons.searchMonth")
                @include("icons.search")
                @include("icons.menu")
                <!-- icons END -->
            </div>
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
                            <img id="photo1Pre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des1"><br>

                        <label>照片2 :&nbsp;</label>
                        <input id="photo2" class="upload" type="file" name="photo2">
                        <div class="imgSet">
                            <img id="photo2Pre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des2"><br>

                        <label>照片3 :&nbsp;</label>
                        <input id="photo3" class="upload" type="file" name="photo3">
                        <div class="imgSet">
                            <img id="photo3Pre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des3"><br>

                        <label>照片4 :&nbsp;</label>
                        <input id="photo4" class="upload" type="file" name="photo4">
                        <div class="imgSet">
                            <img id="photo4Pre">
                        </div>
                        <label>照片描述 ：</label>
                        <input type="text" name="des4"><br>

                        <labe>照片集連結 :</label>
                            <input type="url" name="link"><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="closeEdit btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="saveAdd" class="save btn btn-primary">Save<button>
                                <button id="saveEdit" class="save btn btn-warning">Save<button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Modal END-->

        <!-- 主內文區 -->
        <div id="main" class="main col col-lg-6 col-md-6 col-sm-12">
        </div>
        @error('date')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <footer>
            <div id="addBtn" data-bs-toggle="modal" data-bs-target="#editModal">
                <p><i class="fa-regular fa-face-smile-wink"></i>&nbsp;&nbsp;How's Today ?</p>
            </div>
        </footer>


        @endsection

        @section('endJs')
        <script src="/js/journal.js"></script>
        <script src="/js/module.js"></script>

        @endsection