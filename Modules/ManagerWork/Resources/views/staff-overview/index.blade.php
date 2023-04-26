@extends('layout')
@section('title_header')
    <span class="title_header"> {{__('Báo cáo')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <style>
        .modal .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
        .nav-tabs .nav-item:hover , .sort:hover {
            cursor: pointer;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link.active {
            color:#6f727d;
            border-bottom: #6f727d;
            background: #EEF3F9;
        }
        .nav.nav-pills .nav-item.dropdown.show > .nav-link, .nav.nav-pills .nav-link {
            padding: 15px;
        }
        .table th, .table td {
            vertical-align: middle !important;
        }
        .btn {
            font-family: "Helvetica" !important;
        }
        .sort{
            border: 0;
            background: 0;
        }

        a {
            color:#6f727d;
        }

        a:hover {
            color:#6f727d;
            text-decoration: unset;
        }

        .chart-fix {
            /*margin-bottom: 100px;*/
        }

        .block-status-chart {
            display: block;
            position: relative;
        }

        .block-status-chart li{
            width: fit-content;
            display: inline-block !important;
            margin-top: 15px;
            padding-right: 14px;
        }


    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-3 mb-3 text-right">
            <a href="{{route('manager-work.report')}}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">{{__('BÁO CÁO CHI TIẾT CÔNG VIỆC THEO NHÂN VIÊN')}}</a>
        </div>
        <div class="col-12 scroll">
            @foreach($getListBlock as $item)
                @if($item == 'hot-spot-detection')
                    {{--    Phát hiện điểm nóng--}}
                    <div class="m-portlet" data-key-block="hot-spot-detection" id="autotable">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                                    <i class="la la-th-list"></i>
                                 </span>
                                    <h3 class="m-portlet__head-text text-uppercase">
                                        {{__('Phát hiện điểm nóng')}}
                                    </h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">

                            </div>
                        </div>

                        <div class="m-portlet__body">
                            <form class="frmFilter">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4 form-group">
                                                <select class="form-control searchSelect" name="staff_branch_id" id="staff_branch_id" onchange="StaffOverview.hotSpotDetection()">
                                                    <option value="">{{__('Chi nhánh')}}</option>
                                                    @foreach($listBranch as $item)
                                                        <option value="{{$item['branch_id']}}" {{ $item['branch_id'] == $filter['branch_id'] ? 'selected' : '' }}>{{$item['branch_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3 form-group">
                                                <select class="form-control searchSelect" name="staff_department_id" id="staff_department_id" onchange="StaffOverview.hotSpotDetection()">
                                                    <option value="">{{__('Phòng ban')}}</option>
                                                    @foreach($listDepartment as $item)
                                                        <option value="{{$item['department_id']}}" {{ $item['department_id'] == $filter['department_id'] ? 'selected' : '' }}>{{$item['department_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3 form-group">
                                                <select class="form-control searchSelect" name="staff_manage_project_id" id="staff_manage_project_id" onchange="StaffOverview.hotSpotDetection()">
                                                    <option value="">{{__('Dự án')}}</option>
                                                    @foreach($listProject as $item)
                                                        <option value="{{$item['manage_project_id']}}">{{$item['manage_project_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    {{--                    <div class="col-6 col-md-3 list_staff_not_work p-5">--}}
                                    {{--                    </div>--}}
                                    <div class="col-6 col-md-3 list_staff_not_work_start_yet p-5">
                                    </div>
                                    {{--                    <div class="col-12 col-md-6 list_work_overdue p-5">--}}
                                    {{--                    </div>--}}
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                @if($item == 'job-overview')
                    {{--    Tổng quan công việc--}}
                    <div class="m-portlet" data-key-block="job-overview" id="autotable_chart">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                                        <h3 class="m-portlet__head-text text-uppercase">
                                            {{__('Tổng quan công việc')}}
                                        </h3>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools">

                                </div>
                            </div>

                            <div class="m-portlet__body">
                                <form class="frmFilter_chart">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-2 form-group">
                                                <select class="form-control searchSelect" name="chart_branch_id" id="chart_branch_id" onchange="StaffOverview.changeChartStatus()">
                                                    <option value="">{{__('Chi nhánh')}}</option>
                                                    @foreach($listBranch as $item)
                                                        <option value="{{$item['branch_id']}}" {{ $item['branch_id'] == $filter['branch_id'] ? 'selected' : '' }}>{{$item['branch_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2 form-group">
                                                <select class="form-control searchSelect" name="chart_department_id" id="chart_department_id" onchange="StaffOverview.changeChartStatus()">
                                                    <option value="">{{__('Phòng ban')}}</option>
                                                    @foreach($listDepartment as $item)
                                                        <option value="{{$item['department_id']}}" {{ $item['department_id'] == $filter['department_id'] ? 'selected' : '' }}>{{$item['department_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2 form-group">
                                                <select class="form-control searchSelect" name="chart_manage_project_id" id="chart_manage_project_id" onchange="StaffOverview.changeChartStatus()">
                                                    <option value="">{{__('Dự án')}}</option>
                                                    @foreach($listProject as $item)
                                                        <option value="{{$item['manage_project_id']}}">{{$item['manage_project_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3 form-group">
                                                <input type="text" class="form-control searchDate" name="chart_dateSelect" value="{{\Carbon\Carbon::now()->startOfMonth()->format('d/m/Y').' - '.\Carbon\Carbon::now()->endOfMonth()->format('d/m/Y')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <h5>{{__('BÁO CÁO CÔNG VIỆC THEO TRẠNG THÁI')}}</h5>
                                                    </div>
                                                    <div class="col-12 position-relative chart-fix" style="height : 400px">
                                                        <canvas id="report_chart_status" style="width: 100%; height: 100%;margin:auto;"></canvas>
                                                        <span id="report_chart_status_text" class="text-center"></span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span id="report_chart_status_update" class="text-center"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <h5>{{__('BÁO CÁO CÔNG VIỆC THEO MỨC ĐỘ')}}</h5>
                                                    </div>
                                                    <div class="col-12 chart-fix" style="height : 400px">
                                                        <canvas id="report_chart_priority" style="width: 100%; height: 100%;margin:auto;"></canvas>
                                                        <span id="report_chart_priority_text" class="text-center"></span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span id="report_chart_priority_text_update" class="text-center"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-5">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <h5>{{__('DANH SÁCH CÔNG VIỆC THEO TRẠNG THÁI')}}</h5>
                                                    </div>
                                                    <div class="col-12 list-work-status">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-5">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <h5>{{__('DANH SÁCH CÔNG VIỆC THEO MỨC ĐỘ')}}</h5>
                                                    </div>
                                                    <div class="col-12 list-work-level">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                @endif
                @if($item == 'work-progress')
                    {{--    Tiến độ công việc--}}
                    <div class="m-portlet" data-key-block="work-progress" id="autotable_list">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                                        <h3 class="m-portlet__head-text text-uppercase">
                                            {{__('Tiến độ công việc')}}
                                        </h3>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools" id="accordion">

                                </div>
                            </div>

                            <div class="m-portlet__body">
                                <form class="frmFilter_list">
                                    <div class="col-12">
                                        <div class="row">
                                            {{--                        <div class="col-2 form-group">--}}
                                            {{--                            <select class="form-control searchSelect" name="list_priority_status" id="list_priority_status">--}}
                                            {{--                                <option value="list">{{__('Danh sách')}}</option>--}}
                                            {{--                            </select>--}}
                                            {{--                        </div>--}}
                                            <div class="col-4 form-group">
                                                <select class="form-control searchSelect" name="list_branch_id" id="list_branch_id" onchange="StaffOverview.priorityWork()">
                                                    <option value="">{{__('Chi nhánh')}}</option>
                                                    @foreach($listBranch as $item)
                                                        <option value="{{$item['branch_id']}}" {{ $item['branch_id'] == $filter['branch_id'] ? 'selected' : '' }}>{{$item['branch_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3 form-group">
                                                <select class="form-control searchSelect" name="list_department_id" id="list_department_id" onchange="StaffOverview.priorityWork()">
                                                    <option value="">{{__('Phòng ban')}}</option>
                                                    @foreach($listDepartment as $item)
                                                        <option value="{{$item['department_id']}}" {{ $item['department_id'] == $filter['department_id'] ? 'selected' : '' }}>{{$item['department_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3 form-group">
                                                <select class="form-control searchSelect" name="list_manage_project_id" id="list_manage_project_id" onchange="StaffOverview.priorityWork()">
                                                    <option value="">{{__('Dự án')}}</option>
                                                    @foreach($listProject as $item)
                                                        <option value="{{$item['manage_project_id']}}">{{$item['manage_project_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{--                        <div class="col-3 form-group">--}}
                                            {{--                            <input type="text" class="form-control searchDate list_dateSelect" name="list_dateSelect" value="{{\Carbon\Carbon::now()->startOfMonth()->format('d/m/Y').' - '.\Carbon\Carbon::now()->endOfMonth()->format('d/m/Y')}}">--}}
                                            {{--                        </div>--}}
                                        </div>
                                    </div>
                                    <div class="col-12 list_priority">

                                    </div>
                                </form>
                            </div>
                        </div>
                @endif
            @endforeach
        </div>
    </div>

    <div id="append-popup"></div>
    <form id="form-work" autocomplete="off">
        <div id="append-popup-work"></div>
    </form>

    <div id="vund_popup"></div>
    <input type="hidden" id="routeName" value="{{$routeName}}">
@stop
@section('after_script')
    <script>
        var branch_id = '{{$filter['branch_id']}}';
        var department_id = '{{$filter['department_id']}}';
    </script>
    <script src="{{asset('static/backend/js/hight-chart/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/hight-chart/highcharts-more.js')}}"></script>
    <script src="{{asset('static/backend/js/hight-chart/solid-gauge.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/manager-work/staff-overview/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            scrollBlock();
        })
    </script>
@stop
