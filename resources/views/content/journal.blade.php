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
        <div class="col banner d-flex">
            <!-- year -->
            <div class="yearSet">
                <p class="year mt-3">2024</p>
                <div>
                    <hr class="yearUnderLine">
                </div>
                <p class="year">5783</p>
            </div>

            <!-- month -->
            <div class="month">
                <p>2</p>
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

        <div class="d-flex main row">
            <div class="col col-lg-12 col-md-12 col-sm-6">
                <div class="dailySet">
                    <p class="date">2/14</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="record">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥餅乾，小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection