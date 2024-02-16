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
                    <p class="year">2024</p>
                    <div>
                        <hr class="yearUnderLine">
                    </div>
                    <p class="year">5783</p>
                </div>
                <!-- month -->
                <div class="month">
                    <p>12</p>
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

        <!-- 主內文區 -->
        <div class="main col col-lg-6 col-md-6 col-sm-12">

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/15</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠
                            都準備了聖誕禮物，君君送了我們每人兩包燕麥餅乾，小珠則是送了我
                            們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
                <div class="photos d-flex">
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="more"><img src="" alt="">more</div>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/16</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/17</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥餅乾，小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/18</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥餅小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
                <div class="photos d-flex">
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="more"><img src="" alt="">more</div>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/19</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥乾，小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
                <div class="photos d-flex">
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="photo"><img src="" alt="">+</div>
                    <div class="more"><img src="" alt="">more</div>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/19</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥餅乾，小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/19</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥餅乾，小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
            </div>


            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/19</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/19</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥小珠則是送了我們一人一條雅詩蘭黛護唇膏</p>
                    </div>
                    <p class="editBtn">EDIT</p>
                </div>
            </div>

            <!-- 單則內容 -->
            <div class="dailySet">
                <div class="dailyCon">
                    <p class="date">2/19</p>
                    <div class="dailyText">
                        <p class="topic">臉圓圓聖誕交換禮物</p>
                        <p class="text">今天臉圓圓在三重 老串角居酒屋，君君和小珠都準備了聖誕禮物，君君送了我們每人兩包燕麥餅乾</p>
                    </div>
                    <p class="editBtn" data-id="">EDIT</p>
                </div>
            </div>
        </div>

    </div>
    @endsection

    @section('endJs')
    <script src="/js/journal.js"></script>
    <script src="/js/module.js"></script>

    @endsection