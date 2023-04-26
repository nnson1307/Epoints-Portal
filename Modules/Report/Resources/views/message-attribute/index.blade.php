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
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('export-attr-not-response')}}">
                    <div class="text-center">
                        <label class="nt_text_brand color-ff bg_blue_chart padding25">Confusion Message</label>
                        <div class="form-group with-90">
                            <input class="form-control range-picker nt_custome_input " name="date-range" id="date-range" type="text" placeholder="Chọn ngày">
                        </div>
                        <div class="" id="chart_attribute_not_response" style="min-height: 800px"></div>
                        <div class="form-group text-center ">

                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-success btn-sm nt_btn_suss">Export</button>

                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
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
            src="{{ asset('static/backend/js/report/message-attribute/message-attribute.js?v='.time())}}"></script>
    <script>
        index._init();
    </script>
@endsection
