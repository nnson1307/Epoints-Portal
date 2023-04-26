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
                                {{__('BÁO CÁO DOANH THU THEO CHI NHÁNH')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        @if(in_array('admin.report-revenue.branch.export-total', session()->get('routeList')))
                            <form action="{{route('admin.report-revenue.branch.export-total')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="export_time_total" name="export_time_total">
                                <input type="hidden" id="export_branch_total" name="export_branch_total">
                                <input type="hidden" id="export_customer_group_total" name="export_customer_group_total">

                                <button type="submit"
                                        class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                        <span>
                            <i class="la la-files-o"></i>
                            <span>{{__('Export Tổng')}}</span>
                        </span>
                                </button>
                            </form>
                        @endif
                        @if(in_array('admin.report-revenue.branch.export-detail', session()->get('routeList')))
                            <form action="{{route('admin.report-revenue.branch.export-detail')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="export_time_detail" name="export_time_detail">
                                <input type="hidden" id="export_branch_detail" name="export_branch_detail">
                                <input type="hidden" id="export_customer_group_detail" name="export_customer_group_detail">

                                <button type="submit"
                                        class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                        <span>
                            <i class="la la-files-o"></i>
                            <span>{{__('Export Chi Tiết')}}</span>
                        </span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row m--font-bold align-conter1 ss--text-white">
                        <div class="col-lg-4 form-group">
                            <div class="tongtien">
                                <div class="">
                                    <div class="ss--padding-13">
                                        <h6 class="ss--font-size-12"> {{__('TỔNG DOANH THU')}}</h6>
                                        <h3 class="ss--font-size-18" id="totalMoney"></h3>
                                        <hr class="ss--hr">
                                        <h6 class="ss--font-size-13">
                                            {{__('Tổng đơn hàng')}}: <span id="totalOrder"></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="dathanhtoan">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('SỐ TIỀN ĐÃ THANH TOÁN')}}</h6>
                                    <h3 class="ss--font-size-18" id="totalMoneyOrderPaySuccess"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        {{__('Đã thanh toán')}}: <span id="totalOrderPaySuccess"></span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <div class="chuathanhtoan">
                                <div class="ss--padding-13">
                                    <h6 class="ss--font-size-12"> {{__('SỐ TIỀN CHƯA THANH TOÁN')}}</h6>
                                    <h3 class="ss--font-size-18" id="totalMoneyOrderNew"></h3>
                                    <hr class="ss--hr">
                                    <h6 class="ss--font-size-13">
                                        {{__('Chưa thanh toán')}}: <span id="totalOrderNew"></span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 col-lg-4 ss--col-lg-12 form-group">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-lg-4 ss--col-lg-12 form-group">
                                <select name="branch" style="width: 100%"
                                        {{Auth::user()->is_admin != 1?"disabled":""}} id="branch"
                                        class="form-control m_selectpicker">
                                    @if (Auth::user()->is_admin != 1)
                                        @foreach($branch as $key=>$value)
                                            <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                        @endforeach
                                    @else
                                        <option value="">{{__('Tất cả chi nhánh')}}</option>
                                        @foreach($branch as $key=>$value)
                                            <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-3 col-lg-4 ss--col-lg-12 form-group">
                                <select name="customer_group" style="width: 100%"
                                        {{Auth::user()->is_admin != 1?"disabled":""}} id="customer_group"
                                        class="form-control m_selectpicker">
                                    @if (Auth::user()->is_admin != 1)
                                        @foreach($customerGroup as $key=>$value)
                                            <option value="{{$value['customer_group_id']}}">{{$value['group_name']}}</option>
                                        @endforeach
                                    @else
                                        <option value="">{{__('Tất cả nhóm khách hàng')}}</option>
                                        @foreach($customerGroup as $key=>$value)
                                            <option value="{{$value['customer_group_id']}}">{{$value['group_name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="" style="">
                            <div class="row col-12 load_ajax" id="container"></div>
                            <div id="autotable">
                                <form class="frmFilter">
                                    <input type="hidden" id="time_detail" name="time_detail">
                                    <input type="hidden" id="branch_detail" name="branch_detail">
                                    <input type="hidden" id="customer_group_detail" name="customer_group_detail">
                                    <div class="form-group m-form__group" style="display: none;">
                                        <button class="btn btn-primary color_button btn-search">
                                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                    </div>
                                </form>
                                <div class="table-content div_table_detail">

                                </div>
                            </div>
                            <div class="row col-12 mt-3" id="chart-payment-method"></div>
                        </div>
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
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/revenue/by-branch/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        revenueByBranch._init();
    </script>
@stop
