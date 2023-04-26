@extends('layout')


@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('BÁO CÁO HIỆU QUẢ NGÂN SÁCH MARKETING') }}
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
                        {{ __('BÁO CÁO HIỆU QUẢ NGÂN SÁCH MARKETING') }}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools nt-class">

            </div>
        </div>

        <div class="m-portlet__body">

            <div class="row">
                <div class="col-12 tab-update-report">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="month-tab" onclick="Month.search()" data-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="true">{{__('Theo tháng')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="week-tab" onclick="Week.search()" data-toggle="tab" href="#week" role="tab" aria-controls="week" aria-selected="false">{{__('Theo tuần')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="day-tab" onclick="Day.search()" data-toggle="tab" href="#day" role="tab" aria-controls="day" aria-selected="false">{{__('Theo ngày')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="month" role="tabpanel" aria-labelledby="month-tab">
                            <form class="frmFilterMonth bg">
                                <div class="row padding_row">
                                    <div class="col-lg-3 form-group">
                                        <select style="width: 100%;" name="department_id" class="department_id form-control m-input ss--select-2">
                                            <option value="">{{ __('Tất cả phòng ban') }}</option>
                                            @foreach($listDepartment as $item)
                                                <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <input type="text" name="year" class="yearpicker form-control" value="{{\Carbon\Carbon::now()->format('Y')}}">
                                    </div>
                                    <div class="col-3">
                                        <a href="{{route('report-kpi.budget-efficiency')}}" class="btn btn-metal  btn-search padding9x color_button_fix">
                                            <span><i class="flaticon-refresh"></i></span>
                                        </a>
                                        <button type="button" class="btn btn-primary color_button p-3" onclick="Month.search()" style="width: 150px">
                                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="week" role="tabpanel" aria-labelledby="week-tab">
                            <form class="frmFilterWeek bg">
                                <div class="row padding_row">
                                    <div class="col-lg-3 form-group">
                                        <select style="width: 100%;" name="department_id" class="department_id form-control m-input ss--select-2">
                                            <option value="">{{ __('Tất cả phòng ban') }}</option>
                                            @foreach($listDepartment as $item)
                                                <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <input type="week" name="week_start" class="week_start form-control" value="{{\Carbon\Carbon::now()->format('Y-').'W'.\Carbon\Carbon::now()->weekOfYear}}">
                                    </div>
                                    <div class="col-3">
                                        <input type="week" name="week_end" class="week_end form-control" value="{{\Carbon\Carbon::now()->format('Y-').'W'.\Carbon\Carbon::now()->weekOfYear}}">
                                    </div>
                                    <div class="col-3">
                                        <a href="{{route('report-kpi.budget-efficiency')}}" class="btn btn-metal  btn-search padding9x color_button_fix">
                                            <span><i class="flaticon-refresh"></i></span>
                                        </a>
                                        <button type="button" class="btn btn-primary color_button p-3" onclick="Week.search()" style="width: 150px">
                                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="day" role="tabpanel" aria-labelledby="day-tab">
                            <form class="frmFilterDay bg">
                                <div class="row padding_row">
                                    <div class="col-lg-3 form-group">
                                        <select style="width: 100%;" name="department_id" class="department_id form-control m-input ss--select-2">
                                            <option value="">{{ __('Tất cả phòng ban') }}</option>
                                            @foreach($listDepartment as $item)
                                                <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <input type="text" name="daterange" id="rangepicker" class="rangepicker form-control">
                                    </div>
                                    <div class="col-3">
                                        <a href="{{route('report-kpi.budget-efficiency')}}" class="btn btn-metal  btn-search padding9x color_button_fix">
                                            <span><i class="flaticon-refresh"></i></span>
                                        </a>
                                        <button type="button" class="btn btn-primary color_button p-3" onclick="Day.search()" style="width: 150px">
                                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-content m--padding-top-30">
                <div class="row">
                    <div class="col-12 insert_table" id="insert_table">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <style>
        .table th, .table td {
            vertical-align: inherit;
        }

        .tab-update-report li a{
            color : #000000 !important;
            font-size: 16px;
        }
        .tab-update-report li a.active{
            color : #007177 !important;
            font-weight: 700 !important;
            background: transparent !important;
            border: 1px solid #E5E5E5 !important;
            border-radius: 5px 5px 0px 0px !important;
        }
        .color_button_fix{
            padding : 0.6rem 1.15rem !important;
        }

        .nav-tabs {
            border-bottom : 0;
        }

        input[type="week"]::-ms-clear {
            display: none !important;
        }
        input[type="week"]::-webkit-clear-button {
            display: none;
        }
    </style>
@stop
@section('after_script')

    <script src="{{ asset('static/backend/js/kpi/report/budget-efficiency/script.js?v='.time())}}" type="text/javascript"></script>
    <script style="">
        $('.yearpicker').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            endDate: "+0y",
        });
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

            $("#rangepicker").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                startDate: moment().startOf("month"),
                endDate: moment().endOf("month"),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            }, function(start, end, label) {
                $("#rangepicker").val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            });

            $("#rangepicker").val(moment().startOf("week").format('DD/MM/YYYY') + ' - ' + moment().endOf("week").format('DD/MM/YYYY'));
        });
        $(document).ready(function (){
            Month.search();
        })
    </script>
@stop
