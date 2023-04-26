@extends('layout')
@section('title_header')
@endsection
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/custom-admin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/daterangepicker.css')}}">
@endsection
@section('content')
    <div class="box box-default row">
        <div class="box-body col-md-7">
             <div class="bg_blue_mess">
                 <div class="input-group">
                     <label class="float_left color-ff h2 mglrtop0">On/Off Chatbot</label>
                     <input class="form-control range-picker nt_custome_input_status" id="date-range" type="text" placeholder="Chọn ngày">
                 </div>
             </div>
             <div class="form-group">
                 <div id="chart-time-line" style="width:100%; height: 120%"></div>
             </div>
            {{--<div class="form-group text-center">--}}
                {{--<div id="chart-total-click-link"></div>--}}
            {{--</div>--}}
            {{--<div class="form-group text-center">--}}
                {{--<div id="chart-total-unique-user-click-link"></div>--}}
            {{--</div>--}}
        </div>
        <div class="col-md-4"></div>
    </div>
@endsection
@section('after_styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('after_script')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="{{ asset('static/backend/js/report/user-management-status/user-management-status.js?v='.time())}}"></script>
    <script>
        index._init();
    </script>
@endsection
