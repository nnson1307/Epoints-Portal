@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <style>
        .img_staff:hover {
            opacity: 0.3;
        }
        .m-demo .m-demo__preview {
            border: 0px solid #f7f7fa;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .align-conter1 {
            text-align: center;
        }
        .solid-region {
            border: solid 1px;
            border-color: #027177;
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
                                {{__('BÁO CÁO PERFORMANCE')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="row pt-3">
                            <div class="form-group col-lg-3">
                                <div class="m-input-icon m-input-icon--right">
                                        <select class="form-control"
                                                id="department_id"
                                                name="department_id">
                                            <option value="">@lang("Chọn phòng ban")</option>
                                            @foreach($optionDepartment as $key => $value)
                                                @if($value['department_id'] == 3)
                                                    <option value="{{$value['department_id']}}" selected>{{$value['department_name']}}</option>
                                                @else
                                                    <option value="{{$value['department_id']}}">{{$value['department_name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="m-input-icon m-input-icon--right">
                                        <select class="form-control"
                                                id="branch_code"
                                                name="branch_code">
                                            <option value="">@lang("Chọn chi nhánh")</option>
                                            @foreach($optionBranches as $key => $value)
                                                <option value="{{$value['branch_code']}}">{{$value['branch_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="m-input-icon m-input-icon--right">
                                        <select class="form-control"
                                                id="staff_id"
                                                name="staff_id">
                                            <option value="">@lang("Chọn nhân viên")</option>
                                            @foreach($optionStaffs as $key => $value)
                                                <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input  style="width: auto;" readonly="" class="form-control m-input daterange-picker"
                                           id="time_overview" name="time_overview" autocomplete="off"
                                           placeholder="{{__('Từ ngày - đến ngày')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
{{--                            <div class="col-lg-2 form-group">--}}
{{--                                <button class="btn btn-primary color_button btn-search">--}}
{{--                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row m--font-bold align-conter1"  id="m--star-dashbroad">
                        <div class="col-lg-3">
                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                @lang('TỔNG DOANH SỐ BÁN HÀNG')
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="total_revenue" style="color:green;font-size:18px!important;">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                @lang('TỔNG KHÁCH HÀNG TIẾP CẬN')
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25" style="margin-top: -19px;">
                                        <span class="customer_approach" style="color:green;font-size:18px!important;">0</span>
                                        <div class="progress" id="progress-sms">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                @lang('TỔNG LEAD CHUYỂN ĐỔI')
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="total_lead_convert" style="color:green;font-size:18px!important;">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="solid-region m-portlet m--bg-light m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm ">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light" style="color: #027177 !important;">
                                                @lang('CHỐT ĐƠN THÀNH CÔNG')
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="deal_success" style="color:green;font-size:18px!important;">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group m-form__group row m--font-bold align-conter1">
                        <div class="col-lg-6">
                            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                <div class="form-group m-form__group ss--margin-bottom-0">
                                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400" id="total_staff">
                                        {{__('TỔNG NHÂN VIÊN')}}
                                    </label>
                                </div>
                                <div id="list_staff_scroll" class="row"
                                     style="overflow-y: scroll; min-width: 290px; height: 580px; max-width: 580px; margin: 0 auto">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                <div class="form-group m-form__group ss--margin-bottom-0 bg">
                                    <label class="m--margin-top-20 ss--text-center ss--font-weight-400" id="total_department">
                                        {{__('TỔNG PHÒNG BAN')}}
                                    </label>
                                </div>
                                <div class="tab-content m--margin-top-40">
                                    <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;" id="id_ne">
                                        <li class="nav-item">
                                            <a class="nav-link active son" data-toggle="tab" show style=" color: #4fc4cb; "
                                               onclick="changeTab('sms')" value="@lang("SMS")">@lang("TỔNG DOANH THU")</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link son" data-toggle="tab"
                                               onclick="changeTab('email')" value="@lang("EMAIL")">@lang("TỈ LỆ LEAD CHUYỂN ĐỔI")</a>
                                        </li>
                                    </ul>
                                    <div class="bd-ct row">
                                        <div class="col-lg-12">
                                            <div id="div-sms" style="display: block;">
                                                <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                                    <div id="chart_total_revenue"
                                                         style="width:100%"></div>
                                                </div>
                                            </div>
                                            <div id="div-email" style="display: none">
                                                <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                                    <div id="chart_total_lead_convert"
                                                         style="width:100%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/report/loader.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/report/highcharts.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/exporting.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/export-data.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/report/performance-report/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        performanceReport._init();
    </script>
@stop
