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
    <span class="title_header">{{__('BÁO CÁO HỢP ĐỒNG')}}</span>
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
                                {{__('BÁO CÁO DOANH THU VÀ CHI PHÍ HỢP ĐỒNG')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-9 form-group">
                            </div>
                            <div class="col-lg-3 form-group">
                                <select name="chart_contract_category_id" style="width: 100%" id="chart_contract_category_id"
                                        class="form-control m_selectpicker">
                                        <option value="">{{__('Tất cả loại hợp đồng')}}</option>
                                        @foreach($optionCategory as $key=>$value)
                                            <option value="{{$value['contract_category_id']}}">{{$value['contract_category_name']}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="" style="">
                            <div class="load_ajax" id="container"></div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" id="autotable">
                    <form class="frmFilter bg">
                        <div class="row padding_row">
                            <div class="col-lg-12 form-group row">
                                <div class="col-lg-3 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input type="text"
                                               class="form-control m-input daterange-picker" id="created_at"
                                               name="created_at"
                                               autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select class="form-control select m-input" id="contract_category_id" name="contract_category_id" style="width:100%;">
                                        <option value="">@lang('Chọn loại hợp đồng')</option>
                                        @foreach($optionCategory as $item)
                                            <option value="{{$item['contract_category_id']}}">{{$item['contract_category_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select class="form-control select m-input" id="status_code" name="status_code" style="width:100%;">
                                        <option value="">@lang('Chọn trạng thái')</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <button class="btn btn-primary btn-search color_button">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                                <div class="col-lg-1 form-group">
                                    @if(in_array('contract.report.contract-revenue.export', session()->get('routeList')))
                                    <button onclick="contractRevenueReport.export()"
                                            class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc mr-2">
                                            <span>
                                                <i class="la la-files-o"></i>
                                                <span> {{ __('XUẤT DỮ LIỆU') }}</span>
                                            </span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-content m--padding-top-15">
                        {{--                @include('contract::report.contract-detail.list')--}}
                    </div><!-- end table-content -->
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/report/exporting.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/report/export-data.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/contract/report/contract-revenue/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        contractRevenueReport._init();
    </script>
@stop
