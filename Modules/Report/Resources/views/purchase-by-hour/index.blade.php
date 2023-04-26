@extends('layout')
@section('title_header')
    <span class="title_header">
        {{__('BÁO CÁO')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="la la-th-list"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('BÁO CÁO TỈ LỆ MUA HÀNG THEO KHUNG GIỜ')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">
            <div class="form-group row">
                <div class="col-xl-3 ss--col-xl-4 ss--col-lg-12 form-group">
                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                        <input readonly="" class="form-control m-input daterange-picker"
                               id="time" name="time" autocomplete="off"
                               placeholder="{{__('Từ ngày - đến ngày')}}">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div id="container" style="min-width: 280px;" class="load_ajax"></div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/report/highcharts.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/purchase-by-hour/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        reportPurchase._init();
    </script>
@stop


