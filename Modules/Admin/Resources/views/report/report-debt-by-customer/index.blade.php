@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
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
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" style="height: 20px;">
        {{__('BÁO CÁO')}}
    </span>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                {{__('BÁO CÁO CÔNG NỢ THEO KHÁCH HÀNG')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row m--font-bold align-conter1 ss--text-white">
                        <div class="col-lg-3 form-group">
                            <div class="tongtien">
                                <div class="">
                                    <div class="ss--padding-13">
                                        <h6 class="ss--font-size-12"> {{__('TỔNG TIỀN')}}</h6>
                                        <h3 class="ss--font-size-18" id="amount-debt-money"></h3>
                                        <hr class="ss--hr">
                                        <h6 class="ss--font-size-13">
                                            {{__('TỔNG')}}: <span id="total-debt"></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="dathanhtoan">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('SỐ TIỀN ĐÃ THANH TOÁN')}}</h6>
                                    <h3 class="ss--font-size-18" id="amount-debt-paid"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        {{__('TỔNG')}}: <span id="total-debt-paid"></span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="chuathanhtoan">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('SỐ TIỀN CHƯA THANH TOÁN VÀ CÒN NỢ')}}</h6>
                                    <h3 class="ss--font-size-18" id="amount-debt-unpaid"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        {{__('TỔNG')}}: <span id="total-debt-unpaid"></span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="sotienhuy">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('SỐ TIỀN HỦY')}}</h6>
                                    <h3 class="ss--font-size-18" id="amount-cancel"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        {{__('TỔNG')}}: <span id="total-cancel"></span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 ss--col-xl-4"></div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="branch" style="width: 100%" id="branch"
                                        class="form-control">
                                    <option value="">{{__('Tất cả chi nhánh')}}</option>
                                    @foreach($optionBranch as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="customer" style="width: 100%" id="customer"
                                        class="form-control">
                                    <option value="">{{__('Tất cả khách hàng')}}</option>
                                    <option value="10" selected>{{__('Top 10 KH')}}</option>
                                    <option value="20">{{__('Top 20 KH')}}</option>
                                    <option value="30">{{__('Top 30 KH')}}</option>
                                    <option value="50">{{__('Top 50 KH')}}</option>
                                    <option value="100">{{__('Top 100 KH')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="container" style="min-width: 280px; height: 273px;"></div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <input type="hidden" id="flag" value="0">
    <div id="value-12-month-controller">

    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/report-debt/report-customer.js')}}" type="text/javascript"></script>
    <script>
        $(document).ajaxStart(function () {
            mApp.block("#container", {
                overlayColor: "#000000",
                type: "loader",
                state: "success",
                message: __("Đang tải...")
            });
        });
        $(document).ajaxStop(function () {
            mApp.unblock("#container");
        });
    </script>
@stop
