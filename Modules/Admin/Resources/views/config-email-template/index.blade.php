@extends('layout')
@section('title_header')
    <span class="title_header">{{__('CẤU HÌNH EMAIL TEMPLATE')}}</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH CẤU HÌNH')}}
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="table-content">
                @include('admin::config-email-template.list')
            </div><!-- end table-content -->

        </div>
    </div>
    <div class="modal_view_render" >

    </div>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/config-email-template/script.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/config-print-service-card/color.js')}}"
            type="text/javascript"></script>
    <script type="text/template" id="image-tpl">
        <div class="wrap-img">
            <img class="m--bg-metal m-image img-sd" id="img"
                 src="{{asset('static/backend/images/default-placeholder.png')}}"
                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
            <span class="delete-img cl_image">
            <a href="javascript:void(0)" onclick="config_email_template.remove_image('{id}')">
                <i class="la la-close"></i>
            </a>
        </span>
            <input type="hidden" id="image" name="image" value="">
        </div>
    </script>

@stop
