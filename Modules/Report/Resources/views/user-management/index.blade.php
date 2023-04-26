{{--@extends('backpack::layout')--}}
@extends('layout')
@section('title_header')
@endsection
<link rel="stylesheet" type="text/css" href="{{asset('vendors/adminlte/bootstrap/css/bootstrap.css')}}">
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/custom-admin.css')}}">
    {{--    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/bootstrap.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/daterangepicker.css')}}">
@endsection
@section('content')
    <div class="box box-default">
        <div class="box-body">
            <div class="row nt-paddding-row" style="margin-left: unset">
                <div class="col-12 col-md-6 text-center">
                    <div class="row">
                        <div class="bg-by-tag w-100">
                            <div class="form-group with-90 text-center nt-size-custome-v1">
                                <label class="nt_text_brand text-dark"> USER MANAGEMENT</label>
                                <div class="form-group with-90">
                                    <input class="form-control range-picker nt_custome_input " id="date-range" type="text" placeholder="Chọn ngày">
                                </div>
                            </div>
{{--                            <div class="form-group with-90 d-flex-nt">--}}
{{--                                <label class="text_brand float_left">Date Range</label>--}}
{{--                            </div>--}}
                        </div>

                        <label class="nt_text_brand w-100">UNIQUE USER BY BRAND</label>
                        <div id="total_user_follow" class="w-100" style="width: 97%; text-align: center; margin: 0 auto"></div>
                        <label class="nt_text_brand w-100">QUANTITY</label>
                    </div>
                </div>
                <div class="col-12 col-md-6 ">
                    <div class="bg_user">
                        <div class="row text-center nt_row_user nt-size-custome-v2">
                            <label class="nt_text_brand color-ff mangin_botton_0  m-auto">UNIQUE USER BY BRAND
                            </label>
                        </div>
                        <div class="form-group" id="total_unique_user_brand"></div>
                    </div>
                    <!-- -->
                </div>
            </div>
            <div class="row nt-paddding-row" style="margin-left: unset">
                <div class="col-12 col-md-6 text-center">
                    <div class="row mglr0">
                        <div class="bg_blue w-100">
                            <div class="form-group with-90 text-center nt-size-custome">
                                <label class="nt_text_brand color-fffff m-auto">UNIQUE USER BY TIME</label>
                            </div>
                        </div>
                        <div id="total_unique_user" class="w-100"></div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="bg_user">
                        <div class="form-group padding10 bg_blue nt-padding-css">
                            <label class="text_brand color-fffff text-left with88">UNIQUE USER BY SKU OF BRAND </label>
                            <select id="brand_sku" name="brand_sku" class="form-control nt_custome_input nt_custome_input_brand" onchange="index.change_brand_sku()">
                                @foreach($optionBrand as $key => $item) @if($key == 0)
                                    <option value="{{$item['entities']}}" selected>{{$item['brand_name']}}</option>
                                @else
                                    <option value="{{$item['entities']}}">{{$item['brand_name']}}</option>
                                @endif @endforeach
                            </select>
                            <h5 class="color-fffff text-left with88 mgl3rem ">VIEW BY BRAND
                            </h5>
                        </div>
                        <div class="form-group" id="total_user_sku" style="width: 99%"></div>
                    </div>
                </div>

            </div>
            <div class="row nt-paddding-row" style="margin-left: unset">
            {{--!DATA RANGE--}}
            <!-- user status-->
                {{--<div class="col-12 col-md-6">--}}
                    {{--<div class="bg_user">--}}
                        {{--<div class="form-group padding10 bg_location_nt">--}}
                            {{--<label class="text_brand color-fffff text-left with88 ">DATA RANGE</label>--}}
                            {{--<input class="form-control range-picker nt_custome_input " id="date-range_total_unique_user" type="text" placeholder="Chọn ngày">--}}
                            {{--<h5 class="color-fffff text-left with88 mgl3rem">UNIQUE USER BY SKU OF BRAND--}}
                            {{--</h5>--}}
                        {{--</div>--}}
                        {{--<div class="form-group" id="chart-time-line"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <!-- user status -->
{{--                <div class="col-12 col-md-6"></div>--}}
                <!---CHOOSE VIEW BY BRAND -->
                <div class="col-12 col-md-12">
                    <div class="bg_user">
                        <div class="form-group padding10 bg_location_nt text-center">
                            <label class="text_brand color-fffff text-left with88 text-center">UNIQUE USER BY ATTRIBUTES OF BRAND</label>
                            <select id="brand_attr" name="brand_attr" class="form-control nt_custome_input mx-auto-nt-x text-center" onchange="index.change_brand_attr()">
                                @foreach($optionBrand as $item) @if($key == 0)
                                    <option value="{{$item['entities']}}" selected>{{$item['brand_name']}}</option>
                                @else
                                    <option value="{{$item['entities']}}">{{$item['brand_name']}}</option>
                                @endif @endforeach
                            </select>
                            <h5 class="color-fffff text-left with88 mgl3rem text-center">VIEW BY BRAND
                            </h5>
                        </div>
                        <div class="form-group" id="total_user_attr"></div>
                    </div>
                </div>
                <!---!CHOOSE VIEW BY BRAND -->
            </div>
        </div>
    </div>
@endsection
@section('after_styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection
@section('after_script')
    <script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="{{ asset('static/backend/js/report/user-management/user-management.js?v='.time())}}"></script>
    <script>
        setTimeout(function () {
            $("#total_unique_user_brand").animate({ scrollTop: $('#total_unique_user_brand').prop("scrollHeight")}, 1000);
        }, 3000)

        $(document).ready(function (){
            index._init();
        });
    </script>

@endsection