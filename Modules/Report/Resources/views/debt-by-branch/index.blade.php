
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
                                {{__('BÁO CÁO CÔNG NỢ THEO CHI NHÁNH')}}
                            </h3>
                        </div>
                    </div>

                    <div class="m-portlet__head-tools">
                        @if(in_array('admin.report-debt-branch.export-total', session()->get('routeList')))
                            <form action="{{route('admin.report-debt-branch.export-total')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="export_time_total" name="export_time_total">
                                <input type="hidden" id="export_branch_total" name="export_branch_total">

                                <button type="submit"
                                        class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>{{__('Export Tổng')}}</span>
                                        </span>
                                </button>
                            </form>
                        @endif
                        @if(in_array('admin.report-debt-branch.export-detail', session()->get('routeList')))
                            <form action="{{route('admin.report-debt-branch.export-detail')}}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="export_time_detail" name="export_time_detail">
                                <input type="hidden" id="export_branch_detail" name="export_branch_detail">
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
                        <div class="col-lg-4 form-group">
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
                        <div class="col-lg-4 form-group">
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
                    </div>
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-6 ss--col-xl-4"></div>
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
                                        @foreach($branch as $key => $value)
                                            <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="container" style="min-width: 280px; height: 273px;"></div>
                        <div id="autotable">
                            <form class="frmFilter">
                                <input type="hidden" id="time_detail" name="time_detail">
                                <input type="hidden" id="branch_detail" name="branch_detail">
                                <div class="form-group m-form__group" style="display: none;">
                                    <button class="btn btn-primary color_button btn-search">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="table-content div_table_detail">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/report/debt/by-branch/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        debtByBranch._init();
    </script>
@stop
