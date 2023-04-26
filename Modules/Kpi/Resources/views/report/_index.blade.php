@extends('layout')


@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('BÁO CÁO KPI') }}
    </span>
@endsection

@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('BÁO CÁO KPI') }}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools nt-class">

            </div>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter bg">

                <!-- Filter -->
                <div class="row padding_row">
                    <!-- Chọn thời gian tính kpi -->
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="branch_id" onchange="Report.changeBranch()" class="branch_id form-control m-input ss--select-2">
                            <option value="">{{ __('Tất cả chi nhánh') }}</option>
                            @foreach($listBranch as $item)
                                <option value="{{$item['branch_id']}}">{{$item['branch_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="department_id" onchange="Report.changeDepartment()" class="form-control m-input ss--select-2 department_id">
                            <option value="">{{ __('Tất cả phòng ban') }}</option>
                            @foreach($listDepartment as $item)
                                <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="staff_id" onchange="Report.changeOption('this_month')" class="form-control m-input ss--select-2 staff_id">
                            <option value="">{{ __('Tất cả nhân viên') }}</option>
                            @foreach($listStaff as $item)
                                <option value="{{$item['staff_id']}}">{{$item['full_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="date_type" onchange="Report.changeOption()" class="form-control m-input ss--select-2 date_type" change>
                            <option value="this_month">{{__('Tháng này')}}</option>
                            <option value="after_month">{{__('Tháng trước')}}</option>
                            <option value="this_precious">{{__('Quý này')}}</option>
                            <option value="after_precious">{{__('Quý trước')}}</option>
                            <option value="select_year">{{__('Chọn năm')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-2 form-group yearpicker-block" style="display: none">
                        <input type="text" name="yearpicker" class="yearpicker form-control" onchange="Report.showChartTable()" value="{{\Carbon\Carbon::now()->format('Y')}}">
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                <div class="row">
                    <div class="col-12 insert_chart" id="insert_chart">

                    </div>
                    <div class="col-12 insert_table" id="insert_table">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">
    <style>
        .table th, .table td {
            vertical-align: inherit;
        }
    </style>
@stop
@section('after_script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="{{ asset('static/backend/js/kpi/report/script.js?v='.time())}}" type="text/javascript"></script>
    <script style="">
        $('.yearpicker').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            endDate: "+0y",
            onChange: function (){

            }
        });

        $(document).ready(function (){
           Report.showChartTable();
        });
    </script>
@stop
