@extends('layouts/layout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/event.css') }}">
<link rel="stylesheet" href="{{asset('css/commonUse.css')}}">

@endsection

@section('title','每月事紀')

@section('content')
<div class="content container-fruid">

    <div>
        <!-- menu 區 -->
        @include('layouts.menu')
    </div>

    <div class="header d-flex">
        <div>
            @include("icons.menu")
            <span class="year">2023</span>
        </div>

        <div class="searchGroup">
            <select class="m-1">
                <option value="">2024</option>
            </select>
            @include("icons.search")
        </div>
    </div>

    <div class=" row main">

        <div class="col-lg-6 col-md-12 col-sm-12 group">

            <!-- 單月 -->
            <div class="monthGroup ">
                <div class="month">
                    <p>1</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt">123</div>
                        <div class="dotLine"></div>
                        <div class="tt">123123123</div>
                        <div class="dotLine"></div>
                        <div class="tt">大大</div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>2</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt">123</div>
                        <div class="dotLine"></div>
                        <div class="tt">123123123</div>
                        <div class="dotLine"></div>
                        <div class="tt">大大</div>
                        <div class="dotLine"></div>
                    </div>
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>


            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>3</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>4</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>5</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>6</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>


                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 group">
            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>7</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>8</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>


                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>9</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>10</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>11</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>

            <!-- 單月 -->
            <div class="monthGroup col-lg-6 col-md-6 col-sm-12">
                <div class="month">
                    <p>12</p>
                </div>
                <div class="textGroup d-flex">
                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>

                    <div class="text">
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                        <div class="tt"></div>
                        <div class="dotLine"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- </div> -->
</div>
@endsection

@section('endJs')
<script></script>
@endsection