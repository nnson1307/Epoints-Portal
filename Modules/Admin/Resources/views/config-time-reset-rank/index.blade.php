@extends('layout')
@section('title_header')
    <span class="title_header">{{__('THIẾT LẬP LẠI THỨ HẠNG THÀNH VIÊN')}}</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH THỜI GIAN THIẾT LẬP LẠI THỨ HẠNG THÀNH VIÊN')}}
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="table-content m--padding-top-30">
                @include('admin::config-time-reset-rank.list')
            </div><!-- end table-content -->

        </div>
    </div>
    <div id="my-popup"></div>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/config-time-reset-rank/script.js?v='.time())}}" type="text/javascript"></script>

@stop
