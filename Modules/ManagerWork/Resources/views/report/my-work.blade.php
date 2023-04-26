@extends('layout')
@section('title_header')
    <span class="title_header"> {{__('Báo cáo')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">

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
            font-family: "Roboto" !important;
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

        .tongtien {
            background-image: url("{{asset('static/backend/images/report/hinh3.jpg')}}");
            background-size: cover;
            color: white!important;
        }

        .dathanhtoan {
            background-image: url("{{asset('static/backend/images/report/hinh4.jpg')}}");
            background-size: cover;
            color: white!important;
        }

        .chuathanhtoan {
            background-image: url("{{asset('static/backend/images/report/hinh2.jpg')}}");
            background-size: cover;
            color: white!important;
        }

        .sotienhuy {
            background-image: url("{{asset('static/backend/images/report/hinh1.jpg')}}");
            background-size: cover;
            color: white !important;
        }

        .main-timeline .timeline:first-child:before {
            right: 97%;
        }

    </style>
@endsection
@section('content')
{{--    Tổng quan công việc--}}
    <div class="row">

        <div class="col-12" id="m-dashbroad">
            <div class="row" id="m--star-dashbroad">
                <div class="col-lg-3">
                    <a target="_blank" href="{{route('manager-work',[
                            'date_start' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                            'date_end' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
                            'assign_by' => 4,
                        ])}}">
                        <div id="car-paid-tab" class="m-portlet m--bg-success m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm tongtien move-tab">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light" >
                                            {{__('Việc của tôi')}} <small>{{__('Trong tháng')}}</small>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <span class="m-widget25__price m--font-brand">{{$mywork}}</span>
                                    <span class="m-widget25__desc">{{__('Công việc')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3">
                    <a target="_blank" href="{{route('manager-work',[
                            'date_start' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                            'date_end' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
                            'assign_by' => 2,
                        ])}}">
                        <div id="car-delivered-tab" class="m-portlet m--bg-brand m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm dathanhtoan move-tab">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{__('Việc tôi tạo')}} <small>{{__('Trong tháng')}}</small>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <span class="m-widget25__price m--font-brand">{{$created}}</span>
                                    <span class="m-widget25__desc">{{__('Công việc')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3">
                    <a target="_blank" href="{{route('manager-work',[
                            'date_start' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                            'date_end' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
                            'assign_by' => 1,
                        ])}}">
                        <div id="car-still-tab" class="m-portlet m--bg-danger m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm chuathanhtoan move-tab">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{__('Việc tôi hỗ trợ')}}<small>{{__('Trong tháng')}}</small>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <span class="m-widget25__price m--font-brand">{{$support}}</span>
                                    <span class="m-widget25__desc">{{__('Công việc')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3">
                    <a target="_blank" href="{{route('manager-work',[
                            'date_start' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d H:i:s'),
                            'date_end' => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d H:i:s'),
                            'assign_by' => 3,
                        ])}}">
                        <div id="branch-revenue-report-tab" class="m-portlet m--bg-warning m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm sotienhuy move-tab">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{__('Việc tôi duyệt')}} <small>{{__('Trong tháng')}}</small>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <span class="m-widget25__price m--font-brand">{{$approve}}</span>
                                    <span class="m-widget25__desc">{{__('Công việc')}}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
        <div class="col-9 my-work-page scroll">
            @foreach($getListBlock as $item)
                @if($item == 'my-work-assign')
                    {{--    Việc tôi giao--}}
                    <div class="m-portlet" data-key-block="my-work-assign" id="autotable_list">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                                <h3 class="m-portlet__head-text text-uppercase">
                                    {{__('Việc tôi giao')}}
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools" id="accordion">

                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <form class="frmFilter_list_my_work_assign">
                            <div class="col-12 list_priority listMyWorkAssign" id="accordion1">

                            </div>
                        </form>
                    </div>
                </div>
                    @continue
                @endif
                @if($item == 'my-work-list')
{{--                  Block danh sách công việc--}}
                    <div class="m-portlet" data-key-block="my-work-list" id="autotable_list">
                    <div class="m-portlet__body">
                        <form class="frmFilter_list_my_work">
                            <div class="col-12 listMyWork list_priority">

                            </div>
                        </form>
                    </div>
                </div>
                    @continue
                @endif
                @if($item == 'my-work')
{{--        Block việc của tôi--}}
                    <div class="m-portlet" data-key-block="my-work" id="autotable_chart">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                                <h3 class="m-portlet__head-text text-uppercase">
                                    {{__('Việc của tôi')}}
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <a href="javascript:void(0)" onclick="MyWork.showPopup()" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                            <span>
                                <i class="fa fa-plus-circle m--margin-right-5"></i>
                                <span> {{__('THÊM CÔNG VIỆC')}}</span>
                            </span>
                            </a>
                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <form class="frmFilter_chart">
                            <div class="row">
                                <div class="col-12 position-relative mb-5" style="height : 400px">
                                    <canvas id="report_chart_status" style="width: 100%; height: 100%;margin:auto;"></canvas>
                                    <span id="report_chart_status_text_update" class="text-center"></span>
                                    <span id="list_status_text_update" class="text-center"></span>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 list-my-work">

                            </div>
                        </div>
                    </div>
                </div>
                    @continue
                @endif
                @if($item == 'my-work-support')
{{--        Block việc hỗ trợ--}}
                    <div class="m-portlet" data-key-block="my-work-support" id="autotable_chart">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                                <h3 class="m-portlet__head-text text-uppercase">
                                    {{__('Việc hỗ trợ')}}
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <form class="frmFilter_chart">
                            <div class="col-12 position-relative mt-5" style="height : 400px">
                                <canvas id="report_chart_status_support" style="width: 100%; height: 100%;margin:auto;"></canvas>
                                <span id="report_chart_status_text_update_support" class="text-center"></span>
                                <span id="list_status_text_update_support" class="text-center"></span>
                            </div>
                        </form>
                        <div class="row mt-3">
                            <div class="col-12 list-work-support">

                            </div>
                        </div>
                    </div>
                </div>
                    @continue
                @endif
            @endforeach
        </div>
        <div class="col-3">
            <div class="m-portlet" id="autotable_chart">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text text-uppercase">
                                {{__('Lịch nhắc nhở của tôi')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="javascript:void(0)" onclick="MyWork.showPopupRemind()"><i class="fas fa-plus-square" style="font-size: 2rem;color: #0067AC;"></i></a>
                    </div>
                </div>

                <div class="m-portlet__body" style="padding: 1rem 1rem">
                    <form class="frmFilter_list_remind">
                      
                        <div class="row list_priority">
                            <div class="col-12 form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input type="text" class="form-control searchDate" name="date_remind" value="{{\Carbon\Carbon::now()->startOfMonth()->format('d/m/Y')}} - {{\Carbon\Carbon::now()->endOfMonth()->format('d/m/Y')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-2 form-group" style="display: flex;align-items: center;">
                                <input type="hidden" name="sort_date_remind" id="sort_date_remind" value="DESC">
                                <a href="javascript:void(0)" onclick="MyWork.changeSort();"><i class="fas fa-sort-numeric-up" style="font-size:25px"></i></a>
                            </div>
                            <div class="col-2 form-group" style="display: flex;align-items: center;">
                                <a href="javascript:void(0)" onclick="MyWork.removeRemind();"><i class="fas fa-trash-alt" style="font-size:25px"></i></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row listRemind listRemindHeight">
    
                                </div>
                            </div>
                        </div>
{{--                        <div class="col-12 text-center mt-5">--}}
{{--                            <a href="javascript:void(0)" onclick="MyWork.showPopupRemind()"><i class="fas fa-plus-square" style="font-size: 35px;color: #4fc4cb;"></i></a>--}}
{{--                        </div>--}}
                    </form>
                </div>
            </div>

            <div class="m-portlet" id="autotable_chart">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text text-uppercase">
                                {{__('Lịch sử hoạt động')}}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body" style="padding: 1rem 1.5rem">
                    <form class="frmFilter">
                        <div class="col-12 ">
                            {!! $listHistory !!}
{{--                            @if(count($listHistory) != 0)--}}
{{--                                <div class="main-timeline w-100 d-flex">--}}
{{--                                    @foreach($listHistory as $key => $item)--}}
{{--                                        <div class="timeline">--}}
{{--                                            <div class="timeline-icon"></div>--}}
{{--                                            <div class="timeline-content">--}}
{{--                                                <span class="date">{{$key == \Carbon\Carbon::now()->format('d/m/Y') ? __('Hôm nay') : $key}}</span>--}}
{{--                                                @foreach($item as $itemValue)--}}
{{--                                                    {{__('Lúc')}} {{\Carbon\Carbon::parse($itemValue['created_at'])->format('H:i')}}<br>--}}
{{--                                                    {!!' - <strong>'.$itemValue['staff_name'].'</strong> '.$itemValue['message'] !!}<br>--}}
{{--                                                @endforeach--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            @endif--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="append-popup"></div>
        <form id="form-work">
            <div id="append-add-work"></div>
        </form>
    </div>
    <div id="vund_popup"></div>
    <input type="hidden" id="routeName" value="{{$routeName}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/hight-chart/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/hight-chart/highcharts-more.js')}}"></script>
    <script src="{{asset('static/backend/js/hight-chart/solid-gauge.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/manager-work/report/my-work/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            scrollBlock();
        })
    </script>
@stop
