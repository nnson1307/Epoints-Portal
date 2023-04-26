@extends('layout')
@section("after_style")
    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('THÔNG TIN DỰ ÁN')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            /*background-color: #4fc4cb;*/
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }

        .m-portlet .m-portlet__body {
            padding: 1.2rem 2.2rem;
            background-color: white;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both
        }

        .m-portlet {
            margin-bottom: 0.2rem;
        }

        .column-pie-chart {
            width: 100%;
            font-weight: bold;
        }

        .chart-name {
            font-size: 20px;
            font-weight: bold;
        }

        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 320px;
            max-width: 660px;
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 100%;
            border-radius: 5px;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        img {
            border-radius: 5px 5px 0 0;
        }

        .container {
            padding: 2px 16px;
        }

        table, th, td {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        .statistical td {
            border: none;
            /*display:flex*/
        }

        .card-title {
            padding: 10px 20px;
            margin: 0;
        }

        .card-status {
            font-size: 15px;
            color: #5CACEE;
            border: 1px solid #CAE1FF;
            border-radius: 4px;
            background: #CAE1FF;
            margin: 5px;
            padding: 5px 10px !important;
            margin-top: -5px;
        }


        .hight-risk {
            border: 1px solid #FFF0F5;
            background: #FFF0F5;
            border-radius: 5px;
            color: #A0522D;
            font-weight: 600;
        }

        .fs-15 {
            font-size: 15px;
        }

        .style-icon-statistical {
            font-size: 2rem;
            padding: 7px
        }

        .issue {
            border: 1px solid;
            border-radius: 10px;
            padding: 10px;
        }

        .display-flex {
            display: flex;
        }

        .inline-block {
            display: inline-block;
        }

        .edit-name {
            border: none;
            background-color: white;
            color: #66CCFF;
        }

        .edit-name:hover {
            border: none;
            background-color: #66CCFF;
            color: white;
            border-radius: 5px;
            transition: 1s;
            cursor: pointer
        }
        .fa-trash-alt{
            font-weight: 900;
            color: red;
            border: 1px solid white;
            width: 30px;
            height: 30px;
            padding: 7px;
            border-radius: 50%;
            background-color: white;
        }
        .fa-trash-alt:hover{
            cursor:pointer;
            background-color: red;
            color: white;
            transition: 0.5s
        }
        .card-status-important{
            font-size: 15px;
            color: #FFCC00;
            border: 1px solid #FAFAD2;
            border-radius: 4px;
            background: #FAFAD2;
            margin: 5px;
            margin-top: -5px !important;


        }
        .card-status-red{
            font-size: 15px;
            color: red;
            border: 1px solid #EEB4B4;
            border-radius: 4px;
            background: #EEB4B4;
            margin: 5px;
            margin-top: -5px !important;
        }
        .number-status{
            font-size: 35px
        }
        .column-status {
            float: left;
            width: 25%;
            padding: 10px;
            color: white;
            font-weight: bold;
            text-align: center;
        }

    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÔNG TIN DỰ ÁN')}}
                    </h3>
                </div>
                <div style="    right: 1%;position: absolute;">
                    <a href="{{route('manager-project.project')}}" type="button" class="btn btn-secondary" data-dismiss="modal" style="    color: black;font-weight: bold;">
                        <span class="la 	la-arrow-left"></span>
                        {{__('TRỞ VỀ')}}
                    </a>
                </div>
            </div>
        </div>
        @include('manager-project::project-info.block-project-info-master')
    </div>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head m-portlet__head-update">
        @include('manager-project::layouts.project-info-tab-header')
        </div>
    </div>
    <div class="m-portlet" id="autotable" style="margin-bottom: 0.15rem;padding: 10px;height: 110px;">
        <div class="m-portlet__head" style="height: 6.1rem !important">
            <div class="row" style="width:100%">
                <div class="column-status" style="background-color:#3399FF;">
                    <p  class="mb-0">{{__('CÔNG VIỆC')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['totalWork'] : 0}}</p>
                </div>
{{--                <div class="column-status" style="background-color:#FFCC00;">--}}
{{--                    <p class="mb-0">{{__('CÓ NGUY CƠ TRỄ HẠN')}}</p>--}}
{{--                    <p class="mb-0 number-status">{{ $info['work-duration'] != [] ? $info['work-duration']['workMayBeLate']: 0}}</p>--}}
{{--                </div>--}}
                <div class="column-status" style="background-color:#66CC33;">
                    <p class="mb-0">{{__('HOÀN THÀNH ĐÚNG HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteOnTime']:0}}</p>
                </div>
                <div class="column-status" style="background-color:#CC33FF;">
                    <p class="mb-0">{{__('HOÀN THÀNH TRỄ HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workCompleteLate']:0}}</p>
                </div>
                <div class="column-status" style="background-color:#FF3300;">
                    <p class="mb-0">{{__('ĐÃ TRỄ HẠN')}}</p>
                    <p class="mb-0 number-status">{{$info['work-duration'] != [] ? $info['work-duration']['workOutOfDate']:0}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__body">
            <span class="chart-name">{{__('Tổng quan giai đoạn')}}</span>
            @if($info['chart_phase'] != [])
                <figure class="highcharts-figure-1">
                    <div id="barchart-phase"></div>
                </figure>
            @else
                <p>{{__('Chưa có thông tin giai đoạn')}}</p>
            @endif
        </div>
    </div>
    <div class="pie-chart" style="display: flex">
        <div class="col-6" style="padding: 0;padding-right:2px">
            <div class="m-portlet" id="autotable" style="{{$info['chart_budget'] != [] || $info['chart_member'] != [] ? 'height:539px' :'height:140px;margin-bottom: 10px'}}">
                <div class="m-portlet__body">
                    <span class="chart-name">{{__('Tổng quan dự án theo Ngân sách')}}</span>
                </div>
                <div class="m-portlet__body">
                    @if($info['chart_budget'] != [])
                        <figure class="highcharts-figure">
                            <div id="piechart-info-project-budget"></div>
                        </figure>
                    @else
                        <p>{{__('Chưa có thông tin ngân sách')}}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6" style="padding: 0;padding-left:2px">
            <div class="m-portlet" id="autotable" style="{{$info['chart_budget'] != [] || $info['chart_member'] != [] ? 'height:539px' :'height:140px;margin-bottom: 10px;'}}">
                <div class="m-portlet__body">
                    <span class="chart-name">{{__('Tổng quan dự án theo Thành viên')}}</span>
                </div>
                <div class="m-portlet__body">
                    @if($info['chart_member'] != [])
                        <figure class="highcharts-figure">
                            <div id="piechart-info-project-member"></div>
                        </figure>
                    @else
                        <p>{{__('Chưa có thông tin thành viên')}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
{{--    <div class="m-portlet" id="autotable" style="    margin-top: 7px;">--}}
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__body">
            <span class="chart-name">{{__('Báo cáo chi tiết')}}</span>
            <div class="table-responsive">
                <table class="table table-striped m-table ss--header-table">
                    <thead>
                    <tr class="ss--nowrap">
                        <th class="ss--font-size-th ss--text-center">{{__('Nhân viên')}}</th>
                        <th class="ss--font-size-th ss--text-center">{{__('Tổng công việc')}}</th>
                        <th class="ss--font-size-th  ss--text-center">{{__('Tổng thời gian ước lượng(h)')}}</th>
                        <th class="ss--font-size-th ss--text-center">{{__('Tổng thời gian thực hiện')}}</th>
                        <th class="ss--font-size-th ss--text-center">{{__('Hoàn thành đúng hạn')}}</th>
                        <th class="ss--font-size-th ss--text-center">{{__('Hoàn thành quá hạn')}}</th>
                        @foreach($info['summary']['listStatusWork'] as $key => $val)
                            <th class="ss--font-size-th ss--text-center">{{$val['manage_status_name']}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td class="ss--font-size-th ss--text-center">Tổng</td>
                        <td class="ss--font-size-th ss--text-center">{{count($info['summary']['listWork'])}}</td>
                        <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->sum('time')}}</td>
                        <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->sum('implement_time')}}</td>
                        <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('type_time_work','onTime')->count()}}</td>
                        <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('type_time_work','late')->count()}}</td>
                        @foreach($info['summary']['listStatusWork'] as $keyStatus => $valStatus)
                            <th class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('status_id','=', $valStatus['manage_status_id'])->count()}}</th>
                        @endforeach
                    </tr>
                    @foreach($info['summary']['listWorkGroupByDepartment'] as $key=>$val)
                        <tr class="ss--font-size-13 ss--nowrap">
                            <td class="ss--font-size-th ss--text-center">{{$key}}</td>
                            <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('department_name', $key)->count()}}</td>
                            <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('department_name', $key)->sum('time')}}</td>
                            <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('department_name', $key)->sum('implement_time')}}</td>
                            <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('type_time_work','onTime')->where('department_name', $key)->count()}}</td>
                            <td class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('type_time_work','late')->where('department_name', $key)->count()}}</td>
                            @foreach($info['summary']['listStatusWork'] as $keyStatus => $valStatus)
                                <th class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWork'])->where('status_id','=', $valStatus['manage_status_id'])->where('department_name', $key)->count()}}</th>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('after_script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script src="https://code.highcharts.com/modules/variable-pie.js"></script>


    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>

    <script>
        $('.select2').select2();
        var jsonLang = JSON.parse(localStorage.getItem('tranlate'));
        @if(isset($info['chart_phase']) && isset($info['chart_phase']['categories']))
            Highcharts.chart('barchart-phase', {
                chart: {
                    type: 'bar'
                },
                title: 'none',
                xAxis: {
                    categories: {!! json_encode($info['chart_phase']['categories']) !!}
                },
                yAxis: {
                    min: 0,
                    title: 'none'
                },
                legend: {
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal',
                        dataLabels: 'none'
                    }
                },
                series: {!! json_encode($info['chart_phase']['series']) !!}
            });
        @endif
        @if(isset($info['chart_budget']) && $info['chart_budget']!=[])
        Highcharts.chart('piechart-info-project-budget', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: 'none',
            tooltip: {
                pointFormat:
                  jsonLang['Số lượng'] + ' : <b>{point.y}</b><br/>'+
                  jsonLang['Tỉ lệ'] + ': <b>{point.percentage:.1f}%</b><br/>'

            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Tỉ lệ',
                colorByPoint: true,
                data: {!! json_encode($info['chart_budget']) !!}
            }]
        });
        @endif
        @if(isset($info['chart_member']) && $info['chart_member']!=[])
        Highcharts.chart('piechart-info-project-member', {
            chart: {
                type: 'variablepie'
            },
            tooltip: {
                headerFormat: '',
                pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
                    jsonLang['Số lượng'] + ': <b>{point.y}</b><br/>' +
                    jsonLang['Tỉ lệ'] + ': <b>{point.percentage:.1f}%</b><br/>'

            },
            title: {
                // text: '',
                // align: 'left'
                text:jsonLang['Tổng'] + '</br>' + {!! json_encode(collect($info['chart_member'])->sum('y'))!!},
                style : {"font-size" : "20px" , "font-weigh" : "700" , "font-family" : "Roboto" },
                align: 'center',
                verticalAlign: 'middle',
                y: 8
            },
            plotOptions: {
                variablepie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                innerSize: '40%',
                zMin: 0,
                data: {!! json_encode($info['chart_member']) !!}
            }]
        });
        @endif

    </script>

@stop

