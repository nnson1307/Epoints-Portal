@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-thong-ke.png')}}" alt="" style="height: 20px;">
        {{__('THỐNG KÊ')}}
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
                        {{__('THỐNG KÊ ĐƠN HÀNG')}}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools">
                @if(in_array('admin.statistical.order.export-total', session()->get('routeList')))
                    <form action="{{route('admin.statistical.order.export-total')}}" method="POST">
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
                @if(in_array('admin.statistical.order.export-detail', session()->get('routeList')))
                    <form action="{{route('admin.statistical.order.export-detail')}}" method="POST">
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
            <div class="m-form m-form__group m-form--label-align-right">
                <div class="row">
                    <div class="col-lg-6 ss--col-xl-4"></div>
                    <div class="col-lg-3  ss--col-xl-4 ss--col-lg-12 form-group">
                        <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                            <input readonly="" class="form-control m-input daterange-picker"
                                   id="time" name="time" autocomplete="off"
                                   placeholder="{{__('Từ ngày - đến ngày')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3  ss--col-xl-4 ss--col-lg-12 form-group">
                        <select name="branch" {{Auth::user()->is_admin != 1?"disabled":""}} style="width: 100%"
                                id="branch"
                                class="form-control m_selectpicker">--}}
                            @if (Auth::user()->is_admin != 1)
                                @foreach($branch as $value)
                                    <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                @endforeach
                            @else
                                <option value="">{{__('Tất cả chi nhánh')}}</option>
                                @foreach($branch as $value)
                                    <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group m--margin-top-10">
                <div id="chart-orderss" style="min-width: 280px; height: 273px;"></div>
                <div id="autotable" class="pt-4">
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
    <div class="form-group m-form__group row m--font-bold align-conter1">
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                <div class="form-group m-form__group ss--margin-bottom-0">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                        {{__('NHÓM KHÁCH HÀNG')}}
                    </label>
                </div>
                <div id="pie-chart-customer"
                     style="min-width: 310px; height: 273px; max-width: 500px; margin: 0 auto">

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                <div class="form-group m-form__group ss--margin-bottom-0">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                        {{__('TRẠNG THÁI')}}
                    </label>
                </div>
                <div id="pie-chart-service-category"
                     style="min-width: 310px; height: 300px; max-width: 600px; margin: 0 auto"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
                <div class="form-group m-form__group ss--margin-bottom-0">
                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400">
                        {{__('NGUỒN ĐƠN HÀNG')}}
                    </label>
                </div>
                <div id="pie-chart-product-category"
                     style="min-width: 310px; height: 300px; max-width: 600px; margin: 0 auto"></div>
            </div>
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/report/loader.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/report/statistics/order/script.js')}}"
            type="text/javascript"></script>
    <script>
        statisticOrder._init();
    </script>
@endsection
