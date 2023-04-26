@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CUỘC GỌI')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

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

        .dathanhtoan {
            background-image: url("{{asset('static/backend/images/report/hinh4.jpg')}}");
            background-size: cover;
        }

        .chuathanhtoan {
            background-image: url("{{asset('static/backend/images/report/hinh2.jpg')}}");
            background-size: cover;
        }

        .sotienhuy {
            background-image: url("{{asset('static/backend/images/report/hinh1.jpg')}}");
            background-size: cover;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("BÁO CÁO TỔNG QUAN")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools"></div>
        </div>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row m--font-bold align-conter1 ss--text-white">
                <div class="col-lg-4 form-group">
                    <div class="tongtien">
                        <div class="">
                            <div class="ss--padding-13">
                                <h6 class="ss--font-size-18">@lang('TỔNG CUỘC GỌI')</h6>
                                <hr class="ss--hr">
                                <h6 class="ss--font-size-18">
                                    <span id="totalCall">0</span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="dathanhtoan">
                        <div class="ss--padding-13">
                            <h6 class="ss--font-size-18">@lang('TỔNG CUỘC GỌI THÀNH CÔNG')</h6>
                            <hr class="ss--hr">
                            <h6 class="ss--font-size-18">
                               <span id="totalSuccess">0</span>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="chuathanhtoan">
                        <div class="ss--padding-13">
                            <h6 class="ss--font-size-18">@lang('TỔNG CUỘC GỌI THẤT BẠI')</h6>
                            <hr class="ss--hr">
                            <h6 class="ss--font-size-18">
                                <span id="totalFail">0</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-lg-3 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input readonly class="form-control m-input daterange-picker" style="background-color: #fff"
                               id="created_at" name="created_at" placeholder="@lang('Ngày gọi')">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <select class="form-control" id="staff_id" name="staff_id"
                            style="width:100%;" onchange="index.changeFilter()">
                        <option></option>
                        @foreach($optionStaff as $v)
                            <option value="{{$v['staff_id']}}">{{$v['full_name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <select class="form-control" id="status" name="status"
                            style="width:100%;" onchange="index.changeFilter()">
                        <option></option>
                        <option value="1">@lang('Thành công')</option>
                        <option value="0">@lang('Thất bại')</option>
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <select class="form-control" id="history_type" name="history_type"
                            style="width:100%;" onchange="index.changeFilter()">
                        <option></option>
                        <option value="out">@lang('Cuộc gọi đi')</option>
                        <option value="in">@lang('Cuộc gọi đến')</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div id="div_chart_1"></div>
            </div>
            <div class="form-group" id="div_list_1">
                <div class="table-content div_table_list_1"></div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@stop
@section('after_script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="{{asset('static/backend/js/on-call/report-overview/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        index._init();
    </script>
@stop