@extends('layout')
@section('title_header')
@endsection
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/custom-admin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/daterangepicker.css')}}">
@endsection
@section('content')
    <div class="">
        <div class="box-body">
            <div class="row nt_bg">
                <div class="col-md-12 text-center">
                    <label class="nt_text_brand color-ff bg_blue_chart padding25">Monitoring Message</label>
                    <div class="form-group" id="chart_attribute_other" style="width: 100%; max-width: 100%"></div>
                    <div class="form-group text-center">
                        <form method="POST" action="{{route('message-attribute-other.export')}}">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-success btn-sm nt_btn_suss">Export</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- -->
    </div>
@endsection
@section('after_styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('after_script')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    {{--    <script src="{{ asset('vendor/backpack/select2/select2.js') }}"></script>--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript"
            src="{{ asset('static/backend/js/report/message-attribute-other/message-attribute-other.js')}}"></script>
    <script>
        index._init();
    </script>
@endsection
