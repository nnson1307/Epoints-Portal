@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-report.png')}}" alt="" style="height: 20px;">
        {{__('BÁO CÁO CHUYỂN ĐỔI')}}
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
                                {{__('BÁO CÁO PHỄU CHUYỂN ĐỔI')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="m-portlet__head-tools">

                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">

                    <div class="row form-group">
                        <div class="col-12">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('customer-lead.report.reportFunnel')}}">{{ __('Khách hàng tiềm năng') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active show" href="{{route('customer-lead.report.reportFunnelDeal')}}">{{ __('Cơ hội tiềm năng') }}</a>
                                </li>
                            </ul>
                            {{-- <a href="{{route('customer-lead.report.reportFunnel')}}">
                                <button type="button" class="btn bt-report">Khách hàng tiềm năng</button>
                            </a>
                            <a href="{{route('customer-lead.report.reportFunnelDeal')}}">
                                <button type="button" class="btn bt-report active">Cơ hội tiềm năng</button>
                            </a> --}}
                        </div>
                    </div>
                    <div class="m-form m-form--label-align-right">
                        <form id="search-report">
                            <div class="row">
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <select name="pipeline" style="width: 100%" id="pipeline"
                                            class="form-control" onchange="">
                                        {{--                                <option value="">{{__('Chọn pipeline')}}</option>--}}
                                        @if(isset($optionPipeline) && count($optionPipeline) > 0)
                                            @foreach($optionPipeline as $item)
                                                <option value="{{$item['pipeline_code']}}">
                                                    {{$item['pipeline_name']}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-2 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                        <input readonly="" class="form-control m-input daterange-picker"
                                               id="time" name="time" autocomplete="off"
                                               placeholder="{{__('Từ ngày - đến ngày')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                                                <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>

                                <div class="col-lg-2 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <select name="department_id" style="width: 100%" id="department_id" onchange="funnel.changeDepartment()"
                                            class="form-control" >
                                        <option value="">{{__("Chọn phòng ban")}}</option>
                                        @if(isset($optionDepartment) && count($optionDepartment) > 0)
                                            @foreach($optionDepartment as $item)
                                                <option value="{{$item['department_id']}}">
                                                    {{$item['department_name']}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-2 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <select name="staff_id" style="width: 100%" id="staff_id"
                                            class="form-control" onchange="">
                                        <option value="">{{__("Chọn người được phân bổ")}}</option>
                                        @if(isset($optionStaff) && count($optionStaff) > 0)
                                            @foreach($optionStaff as $item)
                                                <option value="{{$item['staff_id']}}">
                                                    {{$item['full_name']}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-2 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <button class="btn btn-primary color_button btn-search" onclick="funnel.chartLead()">
                                        {{ __('TÌM KIẾM') }}<i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                    {{-- <button type="button" class="btn btn-info" onclick="funnel.chartLead()">
                                        <i class="la la-search"></i>
                                    </button> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="container">
                            <div class="row">
                                <div class="col-6 form-group">
                                    <h4>Biểu đồ phễu chuyển đổi (Số lượng)</h4>
                                    <div id="table-report">

                                    </div>
                                </div>
                                <div class="col-6 form-group">
                                    <h4>Biểu đồ phễu chuyển đổi (%)</h4>
                                    <div id="table-report-percent">

                                    </div>
                                </div>

                                <div class="col-12 form-group">
                                    <h4>Báo cáo kết quả</h4>
                                    <div class="row" id="report_result">
                                        <div class="col-6">
                                            <div class="height-info">
                                                <p class="text-uppercase">Tổng số lượng khách hàng tiềm năng</p>
                                                <p class="totalLead"><span>10000</span></p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="height-info">
                                                <p class="text-uppercase">Tỷ lệ chuyển đổi từ deal lên hợp đồng</p>
                                                <p class="convertDeal"><span>20</span>%</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="height-info">
                                                <p class="text-uppercase">Tỷ lệ chuyển đổi deal thành khách hàng thực thụ</p>
                                                <p class="convertCustomer"><span>10</span>%</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="height-info">
                                                <p class="text-uppercase">Tỷ lệ chuyển đổi deal thất bại</p>
                                                <p class="convertFail"><span>10</span>%</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 form-group">
                                    <h4>Báo cáo chất lượng leads theo nhân viên</h4>
                                    <div id="table-sale">

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>

    <div id="modal-lead-report-convert">

    </div>
    <input type="hidden" id="flag" value="0">
    <div id="value-12-month-controller">

    </div>
    <input type="hidden" readonly="" class="form-control m-input daterange-picker"
           id="time-hidden" name="time-hidden" autocomplete="off">
@endsection

@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/cylinder.js"></script>
    <script src="https://code.highcharts.com/modules/funnel3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="{{asset('static/backend/js/customer-lead/report/funnel-deal.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $('#pipeline').select2();
        $('#customer_source_id').select2();

        funnel.chartLead();
    </script>
@stop
