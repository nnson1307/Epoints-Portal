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
        .fund {
            background-image: url("{{asset('static/backend/images/report/hinh3.jpg')}}");
            background-size: cover;
        }
        .receiptVoucher {
            background-image: url("{{asset('static/backend/images/report/hinh4.jpg')}}");
            background-size: cover;
        }
        .paymentVoucher {
            background-image: url("{{asset('static/backend/images/report/hinh2.jpg')}}");
            background-size: cover;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header">{{__('BÁO CÁO')}}</span>
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
                                {{__('BÁO CÁO THU CHI TỔNG HỢP')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row m--font-bold align-conter1 ss--text-white">
                        <div class="col-lg-4 form-group">
                            <div class="fund">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('TỒN QUỸ')}}</h6>
                                    <h6 class="ss--font-size-18">
                                        <span id="totalFund">0</span>
                                    </h6>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="receiptVoucher">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('TỔNG THU')}}</h6>
                                    <h6 class="ss--font-size-18">
                                        <span id="totalReceiptVoucher">0</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="paymentVoucher">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('TỔNG CHI')}}</h6>
                                    <h6 class="ss--font-size-18">
                                        <span id="totalPaymentVoucher">0</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Chi nhánh'):<b class="text-danger"> *</b>
                                </label>
                                <select name="branch" style="width: 100%" id="branch"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Tất cả chi nhánh')}}</option>
                                    @foreach($optionBranch as $key => $value)
                                        <option value="{{$value['branch_code']}}">{{$value['branch_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Loại phiếu thu'):<b class="text-danger"> *</b>
                                </label>
                                <select name="receipt_type" style="width: 100%" id="receipt_type"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Chọn loại phiếu thu')}}</option>
                                    @foreach($optionReceiptType as $key => $value)
                                        <option value="{{$value['receipt_type_code']}}">{{$value['receipt_type_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Loại phiếu chi'):<b class="text-danger"> *</b>
                                </label>
                                <select name="payment_type" style="width: 100%" id="payment_type"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Chọn loại phiếu chi')}}</option>
                                    @foreach($optionPaymentType as $key => $value)
                                        <option value="{{$value['payment_type_id']}}">{{$value['payment_type_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Hình thức thanh toán'):<b class="text-danger"> *</b>
                                </label>
                                <select name="payment_method" style="width: 100%" id="payment_method"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Chọn hình thức thanh toán')}}</option>
                                    @foreach($optionPaymentMethod as $key => $value)
                                        <option value="{{$value['payment_method_code']}}">{{$value['payment_method_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <label class="black_title">
                                    @lang('Ngày ghi nhận'):<b class="text-danger"> *</b>
                                </label>
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group mt-5">
                        <div class="m--margin-top-5" id="chartBranch" style="min-width: 280px;">

                        </div>
                        <div class="m--margin-top-5" style="width:100%;">
                            <table id="tableSummaryByBranch" class="table-voucher table" style="width:90%;margin-left:5%">
                                <thead>
                                <tr>
                                    <td style="width:40%;">{{__('Chi nhánh')}}</td>
                                    <td style="width:15%;">{{__('Tổng thu')}}</td>
                                    <td style="width:15%;">{{__('Tổng chi')}}</td>
                                    <td style="width:20%;">{{__('Tồn quỹ')}}</td>
                                    <td style="">{{__('Tỷ lệ')}}</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><button onclick="showChartByBranch('receipt')" class="btn epoint-btn-default" id="button-receipt">Xem biểu đồ</button></td>
                                    <td><button onclick="showChartByBranch('payment')" class="btn epoint-btn-blank" id="button-payment">Xem biểu đồ</button></td>
                                    <td><button onclick="showChartByBranch('revenue')" class="btn epoint-btn-blank" id="button-balance">Xem biểu đồ</button></td>
                                    <td></td>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row mt-5">
                            <div class="col-lg-9 ss--col-xl-4 ss--col-lg-12">
                                <div class="m--margin-top-5" id="chartPaymentMethod" style="min-width: 280px;">

                                </div>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12">
                                <div class="m--margin-top-5" id="tablePaymentMethod" style="min-width: 280px;">

                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12">
                                <div class="m--margin-top-5" id="chartReceiptVoucher" style="min-width: 280px;">

                                </div>
                            </div>
                            <div class="col-lg-9 ss--col-xl-4 ss--col-lg-12" style="margin-top: 78px;">
                                <div class="m--margin-top-5" id="tableReceiptVoucher" style="min-width: 280px;">

                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12">
                                <div class="m--margin-top-5" id="chartPaymentVoucher" style="min-width: 280px;">

                                </div>
                            </div>
                            <div class="col-lg-9 ss--col-xl-4 ss--col-lg-12" style="margin-top: 78px;">
                                <div class="m--margin-top-5" id="tablePaymentVoucher" style="min-width: 280px;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <style>
        .epoint-btn-default {
            color: #fff!important;
            border: 1px solid #08f!important;
            background: linear-gradient(
                    180deg
                    ,#08f,#4697fe);
            box-shadow: inset 0 1px 0 0 #1391ff;
        }
        .epoint-btn-blank:hover {
            background: linear-gradient(
                    180deg
                    ,#f9fafb,#f4f6f8);
            border-color: #c4cdd5;
            box-shadow: 0 1px 0 0 rgb(22 29 37 / 5%);
            color: #212b35;
            text-decoration: none;
        }
        .epoint-btn-blank {
            background: linear-gradient(
                    180deg
                    ,#fff,#f9fafb);
            color: #212b35;
            transition-property: background,border,box-shadow;
            transition-timing-function: cubic-bezier(.64,0,.35,1);
            transition-duration: .2s;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/payment/report-synthesis/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        reportSynthesis._init();
    </script>
@stop
