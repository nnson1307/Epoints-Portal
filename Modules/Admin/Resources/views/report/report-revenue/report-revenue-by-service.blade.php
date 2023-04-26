@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" style="height: 20px;">
        {{__('BÁO CÁO')}}
    </span>
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
                        {{__('BÁO CÁO DOANH THU THEO DỊCH VỤ')}}
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
                                <h6 class="ss--font-size-12"> {{__('TỔNG DOANH THU')}}</h6>
                                <h3 class="ss--font-size-18" id="totalOrderPaysuccess"></h3>
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
                                @foreach($branch as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            @else
                                <option value="">{{__('Tất cả chi nhánh')}}</option>
                                @foreach($branch as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select name="numberservice" style="width: 100%" id="number-service"
                                class="form-control m_selectpicker">
                            <option value="">{{__('Tất cả dịch vụ')}}</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group m--margin-top-10">
                <div id="container" style="min-width: 280px;" class="load_ajax"></div>
            </div>
            {{--<div class="form-group m-form__group row m--font-bold align-conter1">--}}
            {{--<div class="col-lg-3">--}}
            {{--<div class="m-demo">--}}
            {{--<div class="m-demo__preview" style="background: #A9A9F5;">--}}
            {{--<h6>Tổng số lượng</h6>--}}
            {{--<h5 id="totalOrder"></h5>--}}
            {{--<hr>--}}
            {{--<h6> Tổng tiền</h6>--}}
            {{--<h5 id="totalMoney"></h5>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-lg-3"></div>--}}
            {{--<div class="col-lg-3"></div>--}}
            {{--<div class="col-lg-3"></div>--}}
            {{--<div class="col-lg-3">--}}
            {{--<div class="m-demo">--}}
            {{--<div class="m-demo__preview" style="background: #A9F5BC;">--}}
            {{--<h6> Tổng doanh thu</h6>--}}
            {{--<h5 id="totalOrderPaysuccess"></h5>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-lg-3">--}}
            {{--<div class="m-demo">--}}
            {{--<div class="m-demo__preview" style="background: #F5BCA9;">--}}
            {{--<h6> Chưa thanh toán</h6>--}}
            {{--<h5 id="totalOrderNew"></h5>--}}
            {{--<hr>--}}
            {{--<h6> Số tiền chưa thanh toán</h6>--}}
            {{--<h5 id="totalMoneyOrderNew"></h5>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-lg-3">--}}
            {{--<div class="m-demo">--}}
            {{--<div class="m-demo__preview" style="background: #F5A9A9;">--}}
            {{--<h6> Hủy</h6>--}}
            {{--<h5 id="totalOrderPayFail"></h5>--}}
            {{--<hr>--}}
            {{--<h6> Số tiền hủy</h6>--}}
            {{--<h5 id="totalMoneyOrderPayFail"></h5>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/report-revenue/by-service.js   ')}}"
            type="text/javascript"></script>
@stop