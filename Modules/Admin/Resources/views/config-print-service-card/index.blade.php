@extends('layout')
@section('title_header')
    <span class="title_header">{{__('CẤU HÌNH THẺ IN')}}</span>
@stop
@section('content')
    <div class="m-portlet ">
        <div class="m-portlet__body" id="autotable">
            <div class="table-content">
                @include('admin::config-print-service-card.list')
            </div><!-- end table-content -->

        </div>
    </div>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/config-print-service-card/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/config-print-service-card/color.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="logo-tpl">
        <div class="wrap-img">
            <img class="m--bg-metal m-image img-sd" id="logo_img_{id}"
                 src="{{asset('static/backend/images/default-placeholder.png')}}"
                 alt="Hình ảnh" width="100px" height="100px">
            <span class="delete-img cl_logo_{id}">
            <a href="javascript:void(0)" onclick="config_service_card.remove_logo('{id}')">
                <i class="la la-close"></i>
            </a>
        </span>
            <input type="hidden" id="logo_{id}" name="logo" value="">
        </div>
    </script>
    <script type="text/template" id="background-tpl">
        <div class="wrap-img">
            <img class="m--bg-metal m-image img-sd" id="background_img_{id}"
                 src="{{asset('static/backend/images/default-placeholder.png')}}"
                 alt="Hình ảnh" width="100px" height="100px">
            <span class="delete-img cl_background_{id}">
            <a href="javascript:void(0)" onclick="config_service_card.remove_background('{id}')">
                <i class="la la-close"></i>
            </a>
        </span>
            <input type="hidden" id="background_image_{id}" name="background_image" value="">
        </div>
    </script>
@stop
