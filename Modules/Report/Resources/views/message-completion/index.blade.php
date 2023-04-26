@extends('layout')
@section('title_header')
@endsection
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/custom-admin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/daterangepicker.css')}}">
@endsection
@section('content')
    <div class="box box-default">
        <form method="POST" action="{{route('message-completion.export')}}">
        <div class="box-body text-center">
            <label class="nt_text_brand color-ff bg_blue_chart padding25">Completion Message</label>
            <div class="form-group with-90">
                <input class="form-control range-picker nt_custome_input " name="date-range" id="date-range" type="text" placeholder="Chọn ngày">
            </div>
            <div class="form-group" id="chart_completion"></div>
            <div class="form-group text-center">

                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success nt_btn_text">Export</button>

            </div>
        </div>
        </form>
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
    <script type="text/javascript"
            src="{{ asset('static/backend/js/report/message-completion/message-completion.js?v='.time())}}"></script>
    <script>
        index._init();
    </script>
@endsection
