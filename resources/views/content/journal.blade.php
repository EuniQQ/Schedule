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
                        <h5 class="modal-title" id="editModalLabel">編輯日記</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="date">Date :&nbsp;</label>
                        <input type="date" name="date"><br>

                        <label for="title">Title :&nbsp;</label>
                        <input type="text" name="title"><br>

                        <label for="text">Content :&nbsp;</label>
                        <textarea name="content"></textarea><br>

                        <label for="photo1">Photo 1 :&nbsp;</label>
                        <input type="file" name="photo1">
                        <div class="photoPre">
                            <img id="photo1Pre" src="" alt="">
                        </div>

                        <label for="photo2">Photo 2 :&nbsp;</label>
                        <input type="file" name="photo2">
                        <div class="photoPre">
                            <img id="photo2Pre" src="" alt="">
                        </div>

                        <label for="photo3">Photo 3 :&nbsp;</label>
                        <input type="file" name="photo3">
                        <div class="photoPre">
                            <img id="photo3Pre" src="" alt="">
                        </div>

                        <label for="photo4">Photo 4 :&nbsp;</label>
                        <input type="file" name="photo4">
                        <div class="photoPre">
                            <img id="photo4Pre" src="" alt="">
                        </div>

                        <label for="link">Photo's Link :</label>
                        <input type="url" name="link"><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes<button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Modal END-->

        <!-- 主內文區 -->
        <div id="main" class="main col col-lg-6 col-md-6 col-sm-12">


        </div>
        @endsection

        @section('endJs')
        <script src="/js/journal.js"></script>
        <script src="/js/module.js"></script>

        @endsection