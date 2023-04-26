@extends('layout')
@section('title_header')
    <span class="title_header">@lang('DANH SÁCH CẤU HÌNH BỐ CỤC TỔNG QUAN')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("TẠO CẤU HÌNH BỐ CỤC TỔNG QUAN")
                    </h2>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="body_create" class="row">

            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/dashbroad/dashboard-config/script.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        dashboardConfig._init();
        dashboardConfig.popCreateConfig();
    </script>
@stop
