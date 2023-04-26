@extends('layout')
@section("after_css")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
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
        .m-image {
            max-width: 100px;
            max-height: 100px;
            width: 100px;
            height: 100px;
            background: #ccc;
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
        .ss--padding-13 {
            padding: 13px!important;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header">{{__('Trang chủ')}}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phieu-custom.css') }}">
@endsection
@section('content')
    <div id="m-dashbroad">
        @foreach($lstComponentDefault as $key => $value)
            <?php $listWidget =  array_keys(collect($value['widget'])->keyBy('widget_code')->toArray()); ?>
            @if($value['component_type'] == 'mini_column')
                <div class="row" id="m--star-dashbroad">
                @foreach($value['widget'] as $k => $v)
                    @if($v['widget_code'] == 'order_day')
                        <div class="col-lg-{{$v['size_column']}}">
                            <div id="car-paid-tab" class="m-portlet m--bg-success m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm tongtien move-tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light" >
                                                {{__('Đơn hàng')}} <small>{{__('Trong ngày')}}</small>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="m-widget25__price m--font-brand">{{$orders}}</span>
                                        <span class="m-widget25__desc">{{__('Đơn hàng')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($v['widget_code'] == 'appointment_day')
                        <div class="col-lg-{{$v['size_column']}}">
                            <div id="car-delivered-tab" class="m-portlet m--bg-brand m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm dathanhtoan move-tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light">
                                                {{__('Lịch hẹn')}} <small>{{__('Trong ngày')}}</small>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="m-widget25__price m--font-brand">{{$appointment}}</span>
                                        <span class="m-widget25__desc">{{__('Lịch hẹn')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($v['widget_code'] == 'customer_day')
                        <div class="col-lg-{{$v['size_column']}}">
                            <div id="car-still-tab" class="m-portlet m--bg-danger m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm chuathanhtoan move-tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light">
                                                {{__('Khách hàng')}}<small>{{__('Trong ngày')}}</small>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="m-widget25__price m--font-brand">{{$totalcustomerOnDay}}</span>
                                        <span class="m-widget25__desc">{{__('Khách hàng')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($v['widget_code'] == 'customer_request_day')
                    @if(in_array('call-center.list', session('routeList')))
                    <div class="col-lg-{{$v['size_column']}}">
                        <div id="car-paid-tab" class="m-portlet m--bg-success m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm tongtien move-tab">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text m--font-light" >
                                            {{__('Tiếp nhận')}} <small>{{__('Trong ngày')}}</small>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget25">
                                    <span class="m-widget25__price m--font-brand">{{$totalCustomerRequest}}</span>
                                    <span class="m-widget25__desc">{{__('Tiếp nhận')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @elseif($v['widget_code'] == 'revenue_day')
                        <div class="col-lg-{{$v['size_column']}}">
                            <div id="branch-revenue-report-tab" class="m-portlet m--bg-warning m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm sotienhuy move-tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light">
                                                {{__('Doanh thu')}} <small>{{__('Trong ngày')}}</small>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="m-widget25__price m--font-brand">{{number_format($sumRevenueInDay, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                                        <span class="m-widget25__desc">{{__(' đ')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($v['widget_code'] == 'car_day')
                        <div class="col-lg-{{$v['size_column']}}">
                            <div id="car-still-tab" class="m-portlet m--bg-danger m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm chuathanhtoan move-tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light">
                                                {{__('Xe còn')}} <small>{{__('Trong ngày')}}</small>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="m-widget25__price m--font-brand">{{$service['use']}}/{{$service['total']}}</span>
                                        <span class="m-widget25__desc">{{__('Xe')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($v['widget_code'] == 'revenue_branch_day')
                        <div class="col-lg-{{$v['size_column']}}">
                            <div id="branch-revenue-report-tab" class="m-portlet m--bg-warning m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm sotienhuy move-tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text m--font-light">
                                                {{__('Doanh thu chi nhánh')}} <small>{{__('Trong ngày')}}</small>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="m-widget25">
                                        <span class="m-widget25__price m--font-brand">{{number_format($sumRevenueInDay, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                                        <span class="m-widget25__desc">{{__(' đ')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                </div>
            @elseif($value['component_type'] == 'column')
                <div class="row">
                    @foreach($value['widget'] as $k => $v)
                        @switch($v['widget_code'])
                            @case('appointment_7_day')
                                <div class="col-lg-{{$v['size_column']}}">
                                    <div class="m-portlet m-portlet--head-sm">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon">
                                                        <i class="flaticon-calendar-1"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        {{__($v['widget_display_name'])}}
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body">
                                            <div id="m_appointments" style="height: 350px;"></div>
                                        </div>
                                    </div>

                                </div>
                            @break
                            @case('order_day_month')
                                <div class="col-lg-{{$v['size_column']}}">
                                    <div class="m-portlet m-portlet--head-sm">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon">
                                                        <i class="flaticon-cart"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        {{__($v['widget_display_name'])}}
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="m-portlet__head-tools">

                                                <div class="form-group m-form__group m--margin-right-5">
                                                    <select name="month" class="form-control m-bootstrap-select m_selectpicker" data-width="100px" id="m_month">
                                                        <option value="" title="{{__('Tất cả')}}">{{__('Tất cả')}}</option>
                                                        @for($i=1;$i<=12;$i++)
                                                            <option value="{{$i}}" title="@lang("Tháng $i")">
                                                                @lang("Tháng $i")
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="form-group m-form__group">
                                                    <select name="year" class="form-control m-bootstrap-select m_selectpicker" data-width="100px" id="m_year">
                                                        @for($i=0;$i<=4;$i++)
                                                            <option value="{{\Carbon\Carbon::now()->subYear($i)->year}}">
                                                                {{ \Carbon\Carbon::now()->subYear($i)->year}}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="m-portlet__body">
                                            <div id="m_orders" style="height: 350px;"></div>
                                        </div>
                                    </div>

                                </div>
                            @break
                            @case('info_sale')
                                <div class="col-lg-{{$v['size_column']}}">
                                    <div class="m-portlet m-portlet--head-sm">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon">
                                                        <i class="flaticon-notes"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        {{__($v['widget_display_name'])}}
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="m-portlet__head-tools">
                                                <div class="m-input-icon m-input-icon--right" id='m_daterangepicker_6'>
                                                    <input name="date" id="date_sale" type="text" class="form-control m-input" placeholder="Chọn ngày" value="{{\Carbon\Carbon::now()->format('d/m/Y')}} - {{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-calendar"></i></span></span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="m-portlet__body">
                                            <div id="m_amcharts_8" style="height: 350px;"></div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('top_10')
                                <div class="col-lg-{{$v['size_column']}}">
                                    <div class="m-portlet m-portlet--head-sm">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon">
                                                        <i class="flaticon-graphic-2"></i>
                                                    </span>
                                                    <h5 class="m-portlet__head-text">
                                                        {{__($v['widget_display_name'])}}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="m-portlet__head-tools">
                                                <div class="m-input-icon m-input-icon--right m-input--sm" id='m_daterangepicker_7'>
                                                    <input name="date" type="text" class="form-control m-input" placeholder="Chọn ngày" value="{{\Carbon\Carbon::now()->format('d/m/Y')}} - {{\Carbon\Carbon::now()->format('d/m/Y')}}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-calendar"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body">
                                            <div id="m_amcharts_5" style="height: 350px;"></div>
                                        </div>
                                    </div>

                                </div>
                            @break
                            @case('list_customer_request')
                            <div class="col-lg-{{$v['size_column']}}">
                                            <div class="m-portlet m-portlet--head-sm list_customer_request">
                                                <div class="m-portlet__head">
                                                    <div class="m-portlet__head-caption">
                                                        <div class="m-portlet__head-title">
                                                            <span class="m-portlet__head-icon">
                                                                <i class="flaticon-calendar"></i>
                                                            </span>
                                                            <h5 class="m-portlet__head-text">
                                                                {{__($v['widget_display_name'])}}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="m-portlet__body">
                                                    <div id="autotable">  
                                                        <div class="table-content" id="lstCustomerRequestToday">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <!-- @if(in_array('call-center.list', session('routeList')))
                                    
                                @endif -->
                            @break
                            @case('list_birthday')
                                <div class="col-lg-{{$v['size_column']}} m-portlet m-portlet--head-sm" m-portlet="true" id="m_portlet_tools_4">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon">
                                                    <i class="flaticon-calendar"></i>
                                                </span>
                                                <h5 class="m-portlet__head-text">
                                                    {{__($v['widget_display_name'])}} <small class="birtday"> {{\Carbon\Carbon::now()->addDay(0)->format('d/m')}} - {{\Carbon\Carbon::now()->addDay(6)->format('d/m')}}</small>
                                                </h5>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="m-portlet__body">
                                        @include('dashbroad::inc.list-birthday')
                                    </div>
                                </div>
                            @break
                            @case('report_revenue_branch')
                                <div class="col-lg-{{$v['size_column']}} m-portlet m-portlet--head-sm report_revenue_branch">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <h3 id="branch-revenue-report" class="m-portlet__head-text">
                                                    {{__($v['widget_display_name'])}}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form--label-align-right">
                                            <div class="row">
                                                <div class="col-lg-3 ss--col-xl-4"></div>
                                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                                        <input readonly="" class="form-control m-input daterange-picker"
                                                            id="date_revenue_branch" name="time" autocomplete="off"
                                                            placeholder="{{__('Từ ngày - đến ngày')}}">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <select name="branch" style="width: 100%"
                                                            {{Auth::user()->is_admin != 1?"disabled":""}} id="branch"
                                                            class="form-control">
                                                        @if (Auth::user()->is_admin != 1)in
                                                        @foreach($branch as $key=>$value)
                                                            <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                                        @endforeach
                                                        @else
                                                            <option value="">{{__('Tất cả chi nhánh')}}</option>
                                                            @foreach($branch as $key=>$value)
                                                                <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <select name="customer_group" style="width: 100%"
                                                            {{Auth::user()->is_admin != 1?"disabled":""}} id="customer_group"
                                                            class="form-control">
                                                        @if (Auth::user()->is_admin != 1)
                                                            @foreach($customerGroup as $key=>$value)
                                                                <option value="{{$value['customer_group_id']}}">{{$value['group_name']}}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="">{{__('Tất cả nhóm khách hàng')}}</option>
                                                            @foreach($customerGroup as $key=>$value)
                                                                <option value="{{$value['customer_group_id']}}">{{$value['group_name']}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <div class="m--margin-top-5" time style="">
                                                <div class="row col-12 load_ajax" id="branch_container"></div>
                                                <div class="row col-12 mt-3" id="chart-payment-method"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('dashboard_ticket')
                                <div class="col-lg-{{$v['size_column']}} m-portlet m-portlet--head-sm">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon">
                                                    <i class="la la-th-list"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    {{__($v['widget_display_name'])}}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="m-widget16">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="m-widget16__stats mt-0">
                                                        <div class="m-widget16__visual">
                                                            <div id="m_chart_support_tickets2" class="m-widget16__chart"
                                                                style="height: 200px">
                                                                <div class="m-widget16__chart-number total-text">
                                                                    @lang('Tổng') <br>
                                                                    <span class="dashboard_ticket_total"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="m-widget16__legends">
                                                            <div class="m-widget16__legend">
                                                                <span class="m-widget16__legend-bullet bg-chart2"></span>
                                                                <span class="m-widget16__legend-text">
                                                                    <span class="dashboard_ticket_new_percent"></span>% @lang('Mới')
                                                                </span>
                                                            </div>
                                                            <div class="m-widget16__legend">
                                                                <span class="m-widget16__legend-bullet bg-chart1"></span>
                                                                <span class="m-widget16__legend-text">
                                                                    <span class="dashboard_ticket_inprogress_percent"></span>% @lang('Đang xử lý')
                                                                </span>
                                                            </div>
                                                            <div class="m-widget16__legend">
                                                                <span class="m-widget16__legend-bullet bg-chart3"></span>
                                                                <span class="m-widget16__legend-text">
                                                                    <span class="dashboard_ticket_expired_percent"></span>% @lang('Quá hạn')
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="m-widget16__body">
                                                        <div class="m-widget16__item">
                                                        <span class="m-widget16__date m--font-bolder">
                                                            @lang('Ticket mới')
                                                        </span>
                                                            <span class="m-widget16__price m--align-right m-font-second">
                                                            <a class="m-font-second dashboard_ticket_new_ticket"
                                                            href="{{ route('ticket') }}?ticket_status_id=1"></a>
                                                        </span>
                                                        </div>
                                                        <!--end::widget item-->
                                                        <!--begin::widget item-->
                                                        <div class="m-widget16__item">
                                                        <span class="m-widget16__date m--font-bolder">
                                                            @lang('Ticket đang xử lý')
                                                        </span>
                                                            <span class="m-widget16__price m--align-right m-font-second">
                                                            <a class="m-font-second dashboard_ticket_inprocess_ticket"
                                                            href="{{ route('ticket') }}?ticket_status_id=2"></a>
                                                        </span>
                                                        </div>
                                                        <!--end::widget item-->
                                                        <!--begin::widget item-->
                                                        <div class="m-widget16__item">
                                                        <span class="m-widget16__date m--font-bolder">
                                                            @lang('Ticket quá hạn')
                                                        </span>
                                                            <span class="m-widget16__price m--align-right text-normal">
                                                            <a class="m-font-second dashboard_ticket_expired_ticket"
                                                            href="{{ route('ticket') }}?ticket_status_id=7"></a>
                                                        </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('statistics_customer')
                                <div class="col-lg-{{$v['size_column']}} m-portlet m-portlet--head-sm statistics_customer">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon">
                                                    <i class="la la-th-list"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    {{__($v['widget_display_name'])}}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="m-portlet__head-tools">
                                            @if(in_array('admin.report-growth.customer.export-total', session()->get('routeList')))
                                                <form action="{{route('admin.report-growth.customer.export-total')}}" method="POST">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" id="statistics_customer_export_time_total" name="export_time_total">
                                                    <input type="hidden" id="statistics_customer_export_branch_total" name="export_branch_total">

                                                    <button type="submit"
                                                            class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                            <span>
                                                <i class="la la-files-o"></i>
                                                <span>{{__('Export Tổng')}}</span>
                                            </span>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(in_array('admin.report-growth.customer.export-detail', session()->get('routeList')))
                                                <form action="{{route('admin.report-growth.customer.export-detail')}}" method="POST">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" id="statistics_customer_export_time_detail" name="export_time_detail">
                                                    <input type="hidden" id="statistics_customer_export_branch_detail" name="export_branch_detail">
                                                    <button type="submit"
                                                            class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                            <span>
                                                <i class="la la-files-o"></i>
                                                <span>{{__('Export Chi Tiết')}}</span>
                                            </span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form__group m-form--label-align-right">
                                            <div class="row">
                                                <div class="col-lg-6 ss--col-xl-4"></div>
                                                <div class="col-lg-3  ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                                        <input readonly="" class="form-control m-input daterange-picker"
                                                            id="statistics_customer_time" name="statistics_customer_time" autocomplete="off"
                                                            placeholder="{{__('Từ ngày - đến ngày')}}">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3  ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <select name="statistics_customer_branch" {{Auth::user()->is_admin != 1?"disabled":""}} style="width: 100%"
                                                            id="statistics_customer_branch" class="form-control">
                                                        @if (Auth::user()->is_admin != 1)
                                                            @foreach($branch as $value)
                                                                <option value="{{ $value['branch_id'] }}">{{ $value['branch_name'] }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="">{{__('Tất cả chi nhánh')}}</option>
                                                            @foreach($branch as $value)
                                                                <option value="{{ $value['branch_id'] }}">{{ $value['branch_name'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row m--margin-top-10">
                                            <div class="col-lg-12" id="div-chart-growth-customer">
                                                <div id="statistics-customer-chart-growth-customer" style="width: 100%; height: 450px;"></div>
                                                <div id="statistics_customer_autotable" class="pt-4">
                                                    <form class="frmFilter">
                                                        <input type="hidden" id="statistics_customer_time_detail" name="time_detail">
                                                        <input type="hidden" id="statistics_customer_branch_detail" name="branch_detail">
                                                        <div class="form-group m-form__group" style="display: none;">
                                                            <button class="btn btn-primary color_button statistics_customer_btn-search">
                                                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                    <div class="table-content div_table_detail">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break
                            @case('performance_report')
                                <div class="col-lg-{{$v['size_column']}} m-portlet m-portlet--head-sm performance_report">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon">
                                                    <i class="la la-th-list"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    {{__($v['widget_display_name'])}}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="m-portlet__head-tools">
                                            <div class="form-group pt-3">
                                                <div class="m-input-icon m-input-icon--right pr-4 mr-4">
                                                    <select class="form-control"
                                                            id="performance_report_department_id"
                                                            name="performance_report_department_id">
                                                        <option value="">@lang("Chọn phòng ban")</option>
                                                        @foreach($department as $key => $value)
                                                            @if($value['department_id'] == 3)
                                                                <option value="{{$value['department_id']}}" selected>{{$value['department_name']}}</option>
                                                            @else
                                                                <option value="{{$value['department_id']}}">{{$value['department_name']}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group pt-3">
                                                <div class="m-input-icon m-input-icon--right pr-4 mr-4">
                                                    <select class="form-control"
                                                            id="performance_report_branch_code"
                                                            name="performance_report_branch_code">
                                                        <option value="">@lang("Chọn chi nhánh")</option>
                                                        @foreach($branch as $key => $value)
                                                            <option value="{{$value['branch_code']}}">{{$value['branch_name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group pt-3">
                                                <div class="m-input-icon m-input-icon--right pr-4 mr-4">
                                                    <select class="form-control"
                                                            id="performance_report_staff_id"
                                                            name="performance_report_staff_id">
                                                        <option value="">@lang("Chọn nhân viên")</option>
                                                        @foreach($staff as $key => $value)
                                                            <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group pt-3">
                                                <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                                    <input  style="width: auto;" readonly="" class="form-control m-input daterange-picker"
                                                            id="performance_report_time_overview" name="performance_report_time_overview" autocomplete="off"
                                                            placeholder="{{__('Từ ngày - đến ngày')}}">
                                                    <span class="m-input-icon__icon m-input-icon__icon--right"><span><i class="la la-calendar"></i></span></span>
                                                </div>
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
                                                            <span class="performance_report_total_revenue" style="color:green;font-size:18px!important;">0</span>
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
                                                            <span class="performance_report_customer_approach" style="color:green;font-size:18px!important;">0</span>
                                                            <div class="progress" id="performance_report_progress-sms">
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
                                                            <span class="performance_report_total_lead_convert" style="color:green;font-size:18px!important;">0</span>
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
                                                            <span class="performance_report_deal_success" style="color:green;font-size:18px!important;">0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row m--font-bold align-conter1">
                                            <div class="col-lg-6">
                                                <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                                    <div class="form-group m-form__group ss--margin-bottom-0">
                                                        <label class="m--margin-top-20 ss--text-center ss--font-weight-400" id="performance_report_total_staff">
                                                            {{__('TỔNG NHÂN VIÊN')}}
                                                        </label>
                                                    </div>
                                                    <div id="performance_report_list_staff_scroll" class="row"
                                                        style="overflow-y: scroll; min-width: 290px; height: 580px; max-width: 580px; margin: 0 auto">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
                                                    <div class="form-group m-form__group ss--margin-bottom-0 bg">
                                                        <label class="m--margin-top-20 ss--text-center ss--font-weight-400" id="performance_report_total_department">
                                                            {{__('TỔNG PHÒNG BAN')}}
                                                        </label>
                                                    </div>
                                                    <div class="tab-content m--margin-top-40">
                                                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist" style="margin-bottom: 0;" id="id_ne">
                                                            <li class="nav-item m-tabs__item">
                                                                <a class="nav-link m-tabs__link active son" data-toggle="tab" show style=" color: #4fc4cb; "
                                                                onclick="changeTab('sms')" value="@lang("SMS")">@lang("TỔNG DOANH THU")</a>
                                                            </li>
                                                            <li class="nav-item m-tabs__item">
                                                                <a class="nav-link m-tabs__link son" data-toggle="tab"
                                                                onclick="changeTab('email')" value="@lang("EMAIL")">@lang("TỈ LỆ LEAD CHUYỂN ĐỔI")</a>
                                                            </li>
                                                        </ul>
                                                        <div class="bd-ct row">
                                                            <div class="col-lg-12">
                                                                <div id="div-sms" style="display: block;">
                                                                    <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                                                        <div id="performance_report_chart_total_revenue"
                                                                            style="width:100%"></div>
                                                                    </div>
                                                                </div>
                                                                <div id="div-email" style="display: none">
                                                                    <div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
                                                                        <div id="performance_report_chart_total_lead_convert"
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
                            @break
                            @case('lead_report_cs')
                                <div class="col-lg-{{$v['size_column']}} m-portlet m-portlet--head-sm lead_report_cs">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon">
                                                    <i class="la la-th-list"></i>
                                                 </span>
                                                <h3 class="m-portlet__head-text">
                                                    {{__($v['widget_display_name'])}}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="m-portlet__head-tools">
                                            <form action="{{route('customer-lead.report.export-excel-view-lead-report-cs')}}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" id="time_export" name="time">
                                                <input type="hidden" id="pipeline_code_export" name="pipeline_code">
                                                <input type="hidden" id="customer_source_id_export" name="customer_source_id">

                                                <button type="submit"
                                                        class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                                        <span>
                                                            <i class="la la-files-o"></i>
                                                            <span>{{__('Export báo cáo')}}</span>
                                                        </span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="m-form m-form--label-align-right">
                                            <div class="row">
                                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <div class="m-input-icon m-input-icon--right">
                                                        {{--<input readonly="" class="form-control m-input daterange-picker"--}}
                                                        {{--id="time" name="time" autocomplete="off"--}}
                                                        {{--placeholder="{{__('Từ ngày - đến ngày')}}">--}}
                                                        {{--<span class="m-input-icon__icon m-input-icon__icon--right">--}}
                                                        <input readonly class="form-control m-input daterange-picker"
                                                               style="background-color: #fff" id="time" name="time"
                                                               autocomplete="off" placeholder="@lang('Ngày diễn ra')">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                                        <span><i class="la la-calendar"></i></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <select name="pipeline" style="width: 100%" id="pipeline"
                                                            class="form-control" onchange="">
                                                        @if(isset($optionPipeline) && count($optionPipeline) > 0)
                                                            @foreach($optionPipeline as $item)
                                                                <option value="{{$item['pipeline_code']}}">
                                                                    {{$item['pipeline_name']}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <select name="customer_source_id" style="width: 100%" id="customer_source_id"
                                                            class="form-control" onchange="">
                                                        @if(isset($optionCs) && count($optionCs) > 0)
                                                            <option value="">{{__("Chọn nguồn lead")}}</option>
                                                            @foreach($optionCs as $item)
                                                                <option value="{{$item['customer_source_id']}}">
                                                                    {{$item['customer_source_name']}}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                                    <button type="button" class="btn btn-info" onclick="lead.renderTableReportCS()">
                                                        <i class="la la-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <div class="m--margin-top-5" id="container">
                                                <div id="table-report">
                                                    @include('customer-lead::report.report-according-to-cs');
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="modal-lead-report-cs">

                                </div>
                                <input type="hidden" id="flag" value="0">
                                <div id="value-12-month-controller">

                                </div>
                                <input type="hidden" readonly="" class="form-control m-input daterange-picker"
                                       id="time-hidden" name="time-hidden" autocomplete="off">
                            @break
                            @case('report_contract_overview')
                                <div class="col-lg-{{$v['size_column']}} m-portlet m-portlet--head-sm report_contract_overview">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon">
                                                    <i class="la la-th-list"></i>
                                                 </span>
                                                <h3 class="m-portlet__head-text">
                                                    {{__($v['widget_display_name'])}}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="m-portlet__head-tools">
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row m--font-bold align-conter1 ss--text-white" id="m-dashbroad">
                                            <div class="col-lg-3 form-group">
                                                <div class="m-portlet m--bg-success m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm tongtien">
                                                    <div class="">
                                                        <div class="ss--padding-13">
                                                            <h6 class="ss--font-size-12"> {{__('TỔNG HỢP ĐỒNG')}}</h6>
                                                            <h3 class="ss--font-size-18" id="countTotalContract"></h3>
                                                            <hr class="ss--hr">
                                                            <h6 class="ss--font-size-13">
                                                                <span id="amountTotalContract"></span><span> @lang('VNĐ')</span>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 form-group">
                                                <div class="dathanhtoan">
                                                    <div class="ss--padding-13">
                                                        <h6 class="ss--font-size-12"> {{__('CÒN HIỆU LỰC')}}</h6>
                                                        <h3 class="ss--font-size-18" id="countValidated"></h3>
                                                        <hr class="ss--hr">
                                                        <h6 class="ss--font-size-13">
                                                            <span id="amountValidated"></span><span> @lang('VNĐ')</span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 form-group">
                                                <div class="chuathanhtoan">
                                                    <div class="ss--padding-13">
                                                        <h6 class="ss--font-size-12"> {{__('ĐÃ THANH LÝ')}}</h6>
                                                        <h3 class="ss--font-size-18" id="countLiquidated"></h3>
                                                        <hr class="ss--hr">
                                                        <h6 class="ss--font-size-13">
                                                            <span id="amountLiquidated"></span><span> @lang('VNĐ')</span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 form-group">
                                                <div class="sotienhuy">
                                                    <div class="ss--padding-13">
                                                        <h6 class="ss--font-size-12"> {{__('CHỜ THANH LÝ')}}</h6>
                                                        <h3 class="ss--font-size-18" id="countWaitingLiquidation"></h3>
                                                        <hr class="ss--hr">
                                                        <h6 class="ss--font-size-13">
                                                            <span id="amountWaitingLiquidation"></span><span> @lang('VNĐ')</span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-form m-form--label-align-right">
                                            <div class="row">
                                                <div class="col-lg-3 form-group">
                                                    <div class="m-input-icon m-input-icon--right">
                                                        <input readonly="" class="form-control m-input daterange-picker"
                                                               id="contract_overview_time" name="contract_overview_time" autocomplete="off"
                                                               placeholder="{{__('Từ ngày - đến ngày')}}">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 form-group">
                                                    <select name="contract_overview_branch_id" style="width: 100%"
                                                            {{Auth::user()->is_admin != 1?"disabled":""}} id="contract_overview_branch_id"
                                                            class="form-control">
                                                        @if (Auth::user()->is_admin != 1)
                                                            @foreach($branch as $key=>$value)
                                                                @if(Auth::user()->branch_id == $value['branch_id'])
                                                                    <option value="{{$value['branch_id']}}" selected>{{$value['branch_name']}}</option>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <option value="">{{__('Tất cả chi nhánh')}}</option>
                                                            @foreach($branch as $key=>$value)
                                                                @if(Auth::user()->branch_id == $value['branch_id'])
                                                                    <option value="{{$value['branch_id']}}" selected>{{$value['branch_name']}}</option>
                                                                @else
                                                                    <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 form-group">
                                                    <select name="contract_overview_department_id" style="width: 100%"
                                                            id="contract_overview_department_id"
                                                            class="form-control">
                                                        <option value="">{{__('Tất cả phòng ban')}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 form-group">
                                                    <select name="contract_overview_staff_id" style="width: 100%"
                                                            id="contract_overview_staff_id"
                                                            class="form-control">
                                                        <option value="">{{__('Tất cả nhân viên')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <div class="m--margin-top-5" id="" style="">
                                                <div class="load_ajax" id="contract_overview_container"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @break
                        @endswitch
                    @endforeach
                </div>
            @elseif($value['component_type'] == 'tab')
                <div class="m-portlet m-portlet--head-sm m-portlet--tabs" id="m_portlet_tools_5">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                                @foreach($value['widget'] as $k => $v)
                                    @if($v['widget_code'] == 'chart_order_day')
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_order" role="tab" id="car-paid">
                                                <i class="flaticon-bag"></i> {{__($v['widget_display_name'])}}
                                            </a>
                                        </li>
                                    @elseif($v['widget_code'] == 'chart_appointment_day')
                                        <li class="nav-item m-tabs__item" onclick="Appointments.tab_appointment()">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_appointment" role="tab" id="car-delivered">
                                                <i class="flaticon-event-calendar-symbol"></i> {{__($v['widget_display_name'])}}
                                            </a>
                                        </li>
                                    @elseif($v['widget_code'] == 'chart_car_day')
                                        <li class="nav-item m-tabs__item" onclick="Services.tab_services()">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_services" role="tab" id="car-still">
                                                <i class="flaticon-event-calendar-symbol"></i> {{__($v['widget_display_name'])}}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                                @if(session()->get('brand_code') == 'giakhang')
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            @foreach($value['widget'] as $k => $v)
                                @if($v['widget_code'] == 'chart_order_day')
                                    <div class="tab-pane active" id="m_order" role="tabpanel">
                                        @include('dashbroad::inc.list-order')
                                    </div>
                                @elseif($v['widget_code'] == 'chart_appointment_day')
                                    <div class="tab-pane" id="m_appointment" role="tabpanel">
                                        @include('dashbroad::inc.list-appointments')
                                    </div>
                                @elseif($v['widget_code'] == 'chart_car_day')
                                    <div class="tab-pane" id="m_services" role="tabpanel">
                                        @include('dashbroad::inc.list-services')
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

@stop

@section('after_style')
{{--    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">--}}
@stop

@section('after_script')
    <script>

    var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/backend/js/dashbroad/orders.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/backend/js/dashbroad/appointment.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/backend/js/dashbroad/services.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/backend/js/dashbroad/birthday.js')}}"></script>
    <script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('static/backend/js/dashbroad/all.js')}}"></script>
    <style>
        .amcharts-chart-div > a {
            display: none !important;
        }
        .move-tab:hover{
            cursor: pointer;
        }
    </style>
    <script>
        $('#m_appointment').on('change',function (e) {
            // alert('dd')
        });
        $("#branch-revenue-report-tab").click(function() {
            $('body,html').animate({
                scrollTop: $("#branch-revenue-report").offset().top -80
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
    <script src="{{asset('static/backend/js/report/highcharts.js')}}"></script>
    <script src="{{asset('static/backend/js/dashbroad/report-revenue-branch.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/report/loader.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/dashbroad/by-customer/script.js?t='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/dashbroad/performance-report/script.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/report/script.js?t='.time())}}"
            type="text/javascript"></script>
    {{-- <script src="{{asset('static/backend/js/dashbroad/contract-overview/script.js?t='.time())}}"
            type="text/javascript"></script> --}}
    <script>
        
        if($('.list_customer_request').length > 0){
            statisticCustomer.getCustomerRequestToday();
        }
        if($('.report_revenue_branch').length > 0){
            revenueByBranch._init();
        }
        if($('.statistics_customer').length > 0){
            statisticCustomer._init();
        }
        if($('.performance_report').length > 0){
            performanceReport._init();
        }
        if($('.lead_report_cs').length > 0){
            $('#pipeline').select2();
            $('#customer_source_id').select2();

            lead.renderTableReportCS();
        }
        if($('.report_contract_overview').length > 0){
            contractOverviewReport._init();
        }

    </script>
@stop
