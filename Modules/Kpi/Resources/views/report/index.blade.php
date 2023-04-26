@extends('layout')


@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;"/>
        {{ __('BÁO CÁO KPI') }}
    </span>
   
@endsection
@section('after_style')
<style>
    .table.table-striped tbody td{
        border: 1px solid rgba(142, 142, 142, 0.35) !important;
    }
</style>
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
            <!-- Filter -->
            <div class="row">
                <!-- Chọn thời gian tính kpi -->
                <div class="col-lg-2 form-group">
                    <select id="branch_id" name="branch_id" onchange="Report.changeBranch()"
                            class="form-control m-input ss--select-2">
                        <option value="">{{ __('Tất cả chi nhánh') }}</option>
                        @foreach($optionBranch as $item)
                            <option value="{{$item['branch_id']}}">{{$item['branch_name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 form-group">
                    <select id="department_id" name="department_id" onchange="Report.changeDepartment()"
                            class="form-control m-input ss--select-2">
                        <option value="">{{ __('Tất cả phòng ban') }}</option>
                    </select>
                </div>
                <div class="col-lg-2 form-group">
                    <select id="team_id" name="team_id" onchange="Report.changeTeam()"
                            class="form-control m-input ss--select-2">
                        <option value="">{{ __('Tất cả nhóm') }}</option>
                    </select>
                </div>
                <div class="col-lg-2 form-group">
                    <select id="date_type" name="date_type" onchange="Report.changeDateType()"
                            class="form-control m-input ss--select-2">
                        <option value="this_month">{{__('Tháng này')}}</option>
                        <option value="after_month">{{__('Tháng trước')}}</option>
                        <option value="this_precious">{{__('Quý này')}}</option>
                        <option value="after_precious">{{__('Quý trước')}}</option>
                        <option value="select_year">{{__('Chọn năm')}}</option>
                    </select>
                </div>
                <div class="col-lg-2 form-group yearpicker-block" style="display: none">
                    <input type="text" id="year_picker" name="year_picker" class="year_picker form-control"
                           onchange="Report.changeYearType()" value="{{\Carbon\Carbon::now()->format('Y')}}">
                </div>
            </div>

            <div class="form-group" id="div_chart">

            </div>

            <div class="form-group" id="div_table">
{{--                <table class="table table-bordered m-table m-table--border-success">--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        <th rowspan="4">Chi nhánh 1</th>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td rowspan="4">65%</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                    </tr>--}}

{{--                    <tr>--}}
{{--                        <th rowspan="4">Chi nhánh 1</th>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td rowspan="4">65%</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                    </tr>--}}



{{--                    </tbody>--}}
{{--                </table>--}}
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

    <script>
        Report._init();
    </script>
@stop
