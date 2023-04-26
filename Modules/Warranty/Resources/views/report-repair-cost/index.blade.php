@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO')}}</span>
@endsection
@section('content')
    <style>
        .m-demo .m-demo__preview {
            border: 0px solid #f7f7fa;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .align-conter1 {
            text-align: center;
        }

        .tongtien {
            background-image: url("{{asset('static/backend/images/report/hinh3.jpg')}}");
            background-size: cover;
        }
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('BÁO CÁO CHI PHÍ BẢO DƯỠNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
                    <div class="col-lg-3 align-conter1 ss--text-white form-group">
                        <div class="tongtien">
                            <div class="ss--padding-30">
                                <h6 class="ss--font-size-12"> {{__('TỔNG CHI PHÍ')}}</h6>
                                <h3 class="ss--font-size-18" id="totalOrderPaySuccess"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                            <input readonly="" class="form-control m-input daterange-picker ss--search-datetime-hd"
                                   id="time" name="time" autocomplete="off"
                                   placeholder="{{__('Từ ngày - đến ngày')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select name="branch" style="width: 100%" id="branch"
                                {{Auth::user()->is_admin != 1?"disabled":""}}
                                class="form-control m_selectpicker">--}}
                            @if (Auth::user()->is_admin != 1)
                                @foreach($branch as $key => $value)
                                    <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                @endforeach
                            @else
                                <option value="">{{__('Tất cả chi nhánh')}}</option>
                                @foreach($branch as $key => $value)
                                    <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select name="number_object" style="width: 100%" id="number_object"
                                class="form-control m_selectpicker">--}}
                            <option value="">{{__('Tất cả đối tượng')}}</option>
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
{{--                    <div class="col-lg-3 form-group">--}}
{{--                        <input type="text" class="form-control m-input" id="object_name" name="object_name"--}}
{{--                               placeholder="@lang('Nhập tên sản phẩm')">--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-1 form-group">--}}
{{--                        <button class="btn btn-primary color_button btn-search" onclick="repairCost.loadChart()">--}}
{{--                            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="form-group m-form__group m--margin-top-10">
                <div id="container" style="min-width: 280px;"></div>
            </div>

        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/warranty/report-repair-cost/script.js')}}"
            type="text/javascript"></script>
    <script>
        repairCost._init();
    </script>
@stop
