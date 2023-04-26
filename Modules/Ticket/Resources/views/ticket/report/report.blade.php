@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-order.png') }}" alt="" style="height: 20px;">
        @lang('BÁO CÁO TICKET')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phieu-custom.css') }}">
@endsection
@section('content')
<div class="m-portlet m-portlet--head-sm">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-1"></i>
                </span>
                <h3 class="m-portlet__head-text">
                    @lang("BÁO CÁO TICKET")
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="m-portlet m-portlet--head-sm">
            <div id="m-dashbroad">
                <div class="row" id="m--star-dashbroad">
                    <div class="col-lg-3 col-xs-6">
                        <div id="car-paid-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--accent">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('TỔNG') }} 
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?total-ticket=true" class="m-widget25__price m--font-brand">{{$quantity_ticket['total']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div id="car-delivered-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--success">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('MỚI') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?ticket_status_id=1" class="m-widget25__price m--font-brand">{{$quantity_ticket['new']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div id="branch-revenue-report-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--warning">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('ĐANG XỬ LÝ') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?ticket_status_id=2" class="m-widget25__price m--font-brand">{{$quantity_ticket['inprocess']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div id="car-still-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--info">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('HOÀN TẤT') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?ticket_status_id=3" class="m-widget25__price m--font-brand">{{$quantity_ticket['done']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    
                    <div class="col-lg-3 col-xs-6">
                        <div id="branch-revenue-report-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--metal">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('ĐÓNG') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?ticket_status_id=4" class="m-widget25__price m--font-brand">{{$quantity_ticket['close']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div id="car-still-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--danger">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('HỦY') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?ticket_status_id=5" class="m-widget25__price m--font-brand">{{$quantity_ticket['cancle']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div id="car-paid-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--focus">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('QUÁ HẠN') }} 
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?ticket_status_id=7" class="m-widget25__price m--font-brand">{{$quantity_ticket['overtime']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div id="car-delivered-tab"
                            class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm text-center move-tab m-badge m-badge--primary">
                            <div class="m-portlet__head justify-content-center">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light">
                                            {{ __('REOPEN') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <a href="{{route('ticket')}}?ticket_status_id=6" class="m-widget25__price m--font-brand">{{$quantity_ticket['reopen']}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet m-portlet--head-sm">
                <div class="m-portlet__head">
                </div>
                <div class="m-portlet__body">
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                    <input readonly="" class="form-control m-input daterange-picker"
                                           id="time" name="time" autocomplete="off"
                                           placeholder="{{__('Chọn thời gian')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="queue_process_id" style="width: 100%" class="form-control">
                                    <option value="">{{__('Chọn queue')}}</option>
                                    @foreach($queue as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="staff_id" style="width: 100%" class="form-control">
                                    <option value="">{{__('Chọn nhân viên')}}</option>
                                    @foreach($staff as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="ticket_issue_group_id" style="width: 100%" class="form-control">
                                    <option value="">{{__('Chọn loại yêu cầu')}}</option>
                                    @foreach($requestGroup as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                <select name="ticket_status_id" style="width: 100%"
                                        class="form-control">
                                        <option value="">{{__('Chọn trạng thái')}}</option>
                                        @foreach($ticketStatus as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="m--margin-top-5" id="" style="">
                            <div class="row col-12 load_ajax" id="container"></div>
                            <div class="row col-12 mt-3" id="chart-payment-method"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="m-widget16__stats">
                                <div class="m-widget16__visual">
                                    <div id="m_chart_support_tickets1" class="m-widget16__chart"
                                        style="height: 300px">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="m-widget16__stats">
                                <div class="m-widget16__visual">
                                    <div id="m_chart_support_tickets2" class="m-widget16__chart"
                                        style="height: 300px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-report">
                        <div id="autotable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    @endsection

    @section('after_style')
        <link rel="stylesheet" href="{{ asset('static/backend/css/customize.css') }}">
    @stop

    @section('after_script')
        @include('ticket::language.lang')
        <script src="{{ asset('static/backend/js/admin/service/autoNumeric.min.js?v=' . time()) }}"></script>
        <script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
        <script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
        <style>
            .amcharts-chart-div>a {
                display: none !important;
            }

            .move-tab:hover {
                cursor: pointer;
            }

        </style>
        <script>
            $('#m_appointment').on('change', function(e) {
                // alert('dd')
            });
            $("#branch-revenue-report-tab").click(function() {
                $('body,html').animate({
                    scrollTop: $("#branch-revenue-report").offset().top - 80
                }, 800);
            });
            $("#car-delivered-tab").click(function() {
                Appointments.tab_appointment();
                $('#car-delivered').trigger('click');
            });
            $("#car-paid-tab").click(function() {
                $('#car-paid').trigger('click');
            });
            $("#car-still-tab").click(function() {
                Services.tab_services();
                $('#car-still').trigger('click');
            });
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartist-plugin-legend/0.6.2/chartist-plugin-legend.min.js" integrity="sha512-J82gmCXFu+eMIvhK2cCa5dIiKYfjFY4AySzCCjG4EcnglcPQTST/nEtaf5X6egYs9vbbXpttR7W+wY3Uiy37UQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('static/backend/js/report/highcharts.js?v=' . time()) }}"></script>
        <script src="{{ asset('static/backend/js/ticket/ticket/report.js?v='.time()) }}" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                revenueByBranch._init();
            });
        </script>
       
    @stop
