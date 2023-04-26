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
                        {{__('BÁO CÁO DOANH THU THEO DỊCH VỤ PHỤ THU')}}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools">
                @if(in_array('admin.report-revenue.surcharge-service.export-total', session()->get('routeList')))
                    <form action="{{route('admin.report-revenue.surcharge-service.export-total')}}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="export_time_total" name="export_time_total">
                        <input type="hidden" id="export_branch_total" name="export_branch_total">
                        <input type="hidden" id="export_number_service_total" name="export_number_service_total">
                        <input type="hidden" id="export_surcharge_service_id_total" name="export_surcharge_service_id_total">

                        <button type="submit"
                                class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>{{__('Export Tổng')}}</span>
                                        </span>
                        </button>
                    </form>
                @endif
                @if(in_array('admin.report-revenue.surcharge-service.export-detail', session()->get('routeList')))
                    <form action="{{route('admin.report-revenue.surcharge-service.export-detail')}}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" id="export_time_detail" name="export_time_detail">
                        <input type="hidden" id="export_branch_detail" name="export_branch_detail">
                        <input type="hidden" id="export_number_service_detail" name="export_number_service_detail">
                        <input type="hidden" id="export_surcharge_service_id_detail" name="export_surcharge_service_id_detail">
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
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
                    <div class="col-lg-3 align-conter1 ss--text-white form-group">
                        <div class="tongtien">
                            <div class="ss--padding-30">
                                <h6 class="ss--font-size-12"> {{__('TỔNG DOANH THU')}}</h6>
                                <h3 class="ss--font-size-18" id="totalOrderPaySuccess"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 row align-conter1 ss--text-white form-group">
                        <div class="col-lg-12 row align-conter1 ss--text-white form-group">
                            <div class="col-lg-6 form-group">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker ss--search-datetime-hd"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group">
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
                        </div>
                        <div class="col-lg-12 row align-conter1 ss--text-white form-group">
                            <div class="col-lg-6 form-group">
                                <select name="number_service" style="width: 100%" id="number_service"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Giới hạn dịch vụ')}}</option>
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <select name="surcharge_service_id" style="width: 100%" id="surcharge_service_id"
                                        class="form-control m_selectpicker">--}}
                                    <option value="">{{__('Chọn dịch vụ phụ thu')}}</option>
                                    @foreach($surchargeService as $key => $value)
                                        <option value="{{$value['service_id']}}">{{$value['service_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group m--margin-top-10">
                <div id="container" style="min-width: 280px;"></div>
                <div id="autotable">
                    <form class="frmFilter">
                        <input type="hidden" id="time_detail" name="time_detail">
                        <input type="hidden" id="branch_detail" name="branch_detail">
                        <input type="hidden" id="number_service_detail" name="number_service_detail">
                        <input type="hidden" id="surcharge_service_id_detail" name="surcharge_service_id_detail">
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
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js')}}"></script>
    <script src="{{asset('static/backend/js/report/revenue/by-surcharge-service/script.js')}}"
            type="text/javascript"></script>
    <script>
        revenueBySurService._init();
    </script>
@stop
