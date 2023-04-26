@extends('layout')


@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('BÁO CÁO ĐƠN PHÉP') }}
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
                        {{ __('TỔNG QUAN THEO LOẠI PHÉP') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter">
                <!-- Filter -->
                <div class="row padding_row d-flex justify-content-end">
                    <!-- Chọn thời gian tính kpi -->
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="branch_id" onchange="Report.changeBranch(this, 'branch_id')" id="branch_id" class="branch_id form-control m-input ss--select-2">
                            <option value="">{{ __('Tất cả chi nhánh') }}</option>
                            @if(isset($listBranch))
                                @foreach($listBranch as $item)
                                    <option value="{{$item['branch_id']}}">{{$item['branch_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="department_id" onchange="Report.changeDepartment(this, 'department_id')" id="department_id" class="department_id form-control m-input ss--select-2">
                            <option value="">{{ __('Tất cả phòng ban') }}</option>
                            @if(isset($listDepartment))
                                @foreach($listDepartment as $item)
                                    <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="month" onchange="Report.reportByType(this)" id="month_type" class="month_type form-control m-input ss--select-2">
                            @for($i = 1; $i <= 12; $i++)
                            <option @if(date('m') == $i) selected @endif value="{{$i}}">Tháng {{$i}}/{{date("Y")}}</option>
                            @endfor
                        </select>
                    </div>
                
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                <div class="row">
                    <div class="col-12 insert_chart" id="insert_chart">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('SO SÁNH SỐ NGÀY PHÉP THEO QUÝ') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter">

                <!-- Filter -->
                <div class="row padding_row d-flex justify-content-end">
                    <!-- Chọn thời gian tính kpi -->
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="branch_id" onchange="Report.changeBranch(this, 'branch_id_precious')" id="branch_id_precious" class="branch_id_precious form-control m-input ss--select-2">
                            <option value="">{{ __('Tất cả chi nhánh') }}</option>
                            @if(isset($listBranch))
                                @foreach($listBranch as $item)
                                    <option value="{{$item['branch_id']}}">{{$item['branch_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" name="department_id" onchange="Report.changeDepartment(this, 'department_id_precious')" id="department_id_precious" class="department_id_precious form-control m-input ss--select-2">
                            <option value="">{{ __('Tất cả phòng ban') }}</option>
                            @if(isset($listDepartment))
                                @foreach($listDepartment as $item)
                                    <option value="{{$item['department_id']}}">{{$item['department_name']}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="col-lg-2 form-group">
                        @php
                            $precious = 0;
                            $month = date('m');
                            if(in_array($month, [1,2,3]))
                                $precious = 1;
                            else if(in_array($month, [4,5,6]))
                                $precious = 2;
                            else if(in_array($month, [7,8,9]))
                                $precious = 3;
                            else
                                $precious = 4;

                        @endphp
                        <select style="width: 100%;" name="precious" onchange="Report.reportByPrecious(this)" id="precious" class="date_type form-control precious m-input ss--select-2">
                            @for($i = 1; $i <= 4; $i++)
                            <option @if($precious == $i) selected @endif value="{{$i}}">Quý {{$i}}/{{date("Y")}}</option>
                            @endfor
                        </select>
                    </div>
                   
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                <div class="row">
                    <div class="col-12 insert_chart1" id="insert_chart1">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('TOP 10 NHÂN VIÊN NGHỈ NHIỀU NHẤT') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter">

                <!-- Filter -->
                <div class="row padding_row d-flex justify-content-end">
                    
                    <div class="col-lg-2 form-group">
                        <select style="width: 100%;" onchange="Report.reportByTopTen(this)" name="month" class="form-control month-top-ten m-input ss--select-2">
                            @for($i = 1; $i <= 12; $i++)
                            <option @if(date('m') == $i) selected @endif value="{{$i}}">Tháng {{$i}}/{{date("Y")}}</option>
                            @endfor
                        </select>
                    </div>
                    
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                <div class="row">
                    <div class="col-12 insert_chart2" id="insert_chart2">

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

    <script src="{{ asset('static/backend/js/timeoffdays/report/script.js?v='.time())}}" type="text/javascript"></script>
    <script style="">
        $('.yearpicker').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            endDate: "+0y",
            onChange: function (){

            }
        });

        $(document).ready(function (){

            // var precious  = $('#precious option:selected').val();

            // $.get('/timeoffdays/report/report-by-precious-ajax', {precious: precious}, function(res) {

            //     Highcharts.chart('insert_chart1', {

            //         chart: {
            //             type: 'column'
            //         },

            //         title: {
            //             text: ''
            //         },

            //         xAxis: {
            //             categories:  res.categories,
            //         },

            //         yAxis: {
            //             allowDecimals: false,
            //             min: 0,
            //             title: {
            //             text: 'Count medals'
            //             }
            //         },

            //         tooltip: {
            //             formatter: function () {
            //             return '<b>' + this.x + '</b><br/>' +
            //                 this.series.name + ': ' + this.y + '<br/>' +
            //                 'Total: ' + this.point.stackTotal;
            //             }
            //         },

            //         plotOptions: {
            //             column: {
            //             stacking: 'normal'
            //             }
            //         },

            //         series:  res.data,
                    
            //     });

            // });

            // $.get('/timeoffdays/report/report-by-top-ten-ajax', {id: 0}, function(res) {

            //     Highcharts.chart('insert_chart2', {
            //         chart: {
            //             type: 'column'
            //         },
            //         title: {
            //             text: ''
            //         },
            //         subtitle: {
            //             text: ''
            //         },
            //         xAxis: {
            //             categories: res.categories,
            //             crosshair: true
            //         },
            //         yAxis: {
            //             title: {
            //             useHTML: true,
            //             text: ''
            //             }
            //         },
            //         tooltip: {
        
            //             shared: true,
            //             useHTML: true
            //         },
            //         plotOptions: {
            //             column: {
            //                 pointPadding: 0.2,
            //                 borderWidth: 0
            //             }
            //         },
            //         series: [
            //             {
            //                 data: res.data
            //             },
            //         ]
            //     });

            // });

            // Highcharts.chart('insert_chart', {
            //     chart: {
            //         styledMode: true
            //     },
            //     title: {
            //         text: ''
            //     },
            //     xAxis: {
            //         categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            //     },
            //     series: [
            //         {
            //         type: 'pie',
            //         allowPointSelect: true,
            //         keys: ['name', 'y', 'selected', 'sliced'],
            //         data: [
            //             ['Nghỉ bù', 27.79, true, true],
            //         ],
            //         showInLegend: true}
            //     ]
            // });


            // Highcharts.chart('insert_chart1', {

            //     chart: {
            //         type: 'column'
            //     },

            //     title: {
            //         text: ''
            //     },

            //     xAxis: {
            //         categories: ['Nghỉ bù', 'Nghỉ không phép', 'Nghỉ luôn', 'Nghỉ bù', 'Nghỉ không phép', 'Nghỉ luôn']
            //     },

            //     yAxis: {
            //         allowDecimals: false,
            //         min: 0,
            //         title: {
            //         text: 'Count medals'
            //         }
            //     },

            //     tooltip: {
            //         formatter: function () {
            //         return '<b>' + this.x + '</b><br/>' +
            //             this.series.name + ': ' + this.y + '<br/>' +
            //             'Total: ' + this.point.stackTotal;
            //         }
            //     },

            //     plotOptions: {
            //         column: {
            //         stacking: 'normal'
            //         }
            //     },

            //     series: 
            //     [
            //         {
            //             name: 'Tháng 7/2022',
            //             data: [148, 133, 124, 148, 133, 124],
            //             stack: 'Tháng 7/2022'
            //         }, {
            //             name: 'Tháng 8/2022',
            //             data: [102, 98, 65, 148, 133, 124],
            //             stack: 'Tháng 7/2022'
            //         }, {
            //             name: 'Tháng 9/2022',
            //             data: [113, 122, 95, 148, 133, 124],
            //             stack: 'Tháng 7/2022'
            //         }

            //     ]
            // });
          

            

            // Highcharts.chart('insert_chart2', {
            //     chart: {
            //         type: 'column'
            //     },
            //     title: {
            //         text: ''
            //     },
            //     subtitle: {
            //         text: ''
            //     },
            //     xAxis: {
            //         categories: [
            //             'User 1',
            //             'User 2',
            //             'User 3',
            //             'User 4',
            //             'User 5',
            //             'User 6',
            //             'User 7',
            //             'User 8',
            //             'User 9',
            //             'User 10'
            //         ],
            //         crosshair: true
            //     },
            //     yAxis: {
            //         title: {
            //         useHTML: true,
            //         text: ''
            //         }
            //     },
            //     tooltip: {
       
            //         shared: true,
            //         useHTML: true
            //     },
            //     plotOptions: {
            //         column: {
            //             pointPadding: 0.2,
            //             borderWidth: 0
            //         }
            //     },
            //     series: [
            //         {
            //             data: [30, 20, 30, 40, 50, 70, 10, 100, 100, 300]
            //         },
            //     ]
            // });

            Report.reportByType(this);
            Report.reportByPrecious(this);
            Report.reportByTopTen(this);   
        });

    </script>
@stop
