@extends('layout')
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
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <div class="m-input-icon m-portlet__head-icon">
                    </div>
                    <h3 class="m-portlet__head-text">
                    </h3>
                    <h2 style=" white-space:nowrap"
                        class="m-portlet__head-label m-portlet__head-label--primary">
                        <span><i class="la 	la-bar-chart m--margin-right-5"></i> {{__('BÁO CÁO DOANH THU DỊCH VỤ')}} </span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                            <input readonly="" class="form-control m-input daterange-picker"
                                   id="time" name="time" autocomplete="off"
                                   placeholder="{{__('Từ ngày - đến ngày')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <select name="branch" style="width: 100%" id="branch" class="form-control m_selectpicker">--}}
                            <option value="">{{__('Chọn chi nhánh')}}</option>
                            @foreach($branch as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <select name="service" style="width: 100%" id="service"
                                class="form-control m_selectpicker">--}}
                            <option value="">{{__('Chọn dịch vụ')}}</option>
                            @foreach($service as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group m--margin-top-10">
                <div id="container" style="min-width: 280px; height: 273px;"></div>
            </div>
            <div class="form-group m-form__group row m--font-bold align-conter1 m--margin-top-10">
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
                <div class="col-lg-3">
                    <div class="m-demo">
                        <div class="m-demo__preview" style="background: #A9F5BC;">
                            <h6> {{__('Đã thanh toán')}}</h6>
                            <h5 id="totalOrderPaysuccess"></h5>
                            <hr>
                            <h6> {{__('Số tiền đã thanh toán')}}</h6>
                            <h5 id="totalMoneyOrderPaysuccess"></h5>
                        </div>
                    </div>
                </div>
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
            </div>
        </div>
    </div>
    {{--Input hidden để lấy dữ liệu--}}
    <div id="value-12-month-controller">
        {{--@foreach($timeOrder as $key=>$value)--}}
        {{--<input type="hidden" class="value-12-month" value="{{$value}}">--}}
        {{--@endforeach--}}
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/report/report-revenue/report-revenue-service.js   ')}}"
            type="text/javascript"></script>
@stop