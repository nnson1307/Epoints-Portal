@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-order.png') }}" alt="" style="height: 20px;">
        @lang('QUẢN LÝ TICKET')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phieu-custom.css') }}">
    <style>
        .tableFixHead tbody          { overflow: auto; height: 100%; }
        .tableFixHead thead tr { position: sticky !important; top: 0; z-index: 1; background-color: #fff }

    </style>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__body">
            <div class="m-content p-0">
                <!--begin:: Widgets/Support Cases-->
                <div class="m-portlet  m-portlet--full-height">
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
                                                    {{ $ticketDashboad['total'] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-widget16__legends">
                                            <div class="m-widget16__legend">
                                                <span class="m-widget16__legend-bullet bg-chart2"></span>
                                                <span class="m-widget16__legend-text">
                                                   @lang('Mới')
                                                </span>
                                            </div>
                                            <div class="m-widget16__legend">
                                                <span class="m-widget16__legend-bullet bg-chart1"></span>
                                                <span class="m-widget16__legend-text">
                                                  @lang('Đang xử lý')
                                                </span>
                                            </div>
                                            <div class="m-widget16__legend">
                                                <span class="m-widget16__legend-bullet bg-chart3"></span>
                                                <span class="m-widget16__legend-text">
                                                    @lang('Quá hạn')
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 m-auto">
                                    <div class="m-widget16__body">
                                        <div class="m-widget16__item">
                                            <span class="m-widget16__date m--font-bolder">
                                                @lang('Ticket mới')
                                            </span>
                                            <span class="m-widget16__price m--align-right m-font-second">
                                                <a class="m-font-second"
                                                    href="{{ route('ticket') }}?ticket_status_id=1">{{ $ticketDashboad['newTicket'] }}</a>
                                            </span>
                                        </div>
                                        <!--end::widget item-->
                                        <!--begin::widget item-->
                                        <div class="m-widget16__item">
                                            <span class="m-widget16__date m--font-bolder">
                                                @lang('Ticket đang xử lý')
                                            </span>
                                            <span class="m-widget16__price m--align-right m-font-second">
                                                <a class="m-font-second"
                                                    href="{{ route('ticket') }}?ticket_status_id=2">{{ $ticketDashboad['inprocessTicket'] }}</a>
                                            </span>
                                        </div>
                                        <!--end::widget item-->
                                        <!--begin::widget item-->
                                        <div class="m-widget16__item">
                                            <span class="m-widget16__date m--font-bolder">
                                                @lang('Ticket quá hạn')
                                            </span>
                                            <span class="m-widget16__price m--align-right text-normal">
                                                <a class="m-font-second"
                                                    href="{{ route('ticket') }}?ticket_status_id=7">{{ $ticketDashboad['expiredTicket'] }}</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Support Stats-->
                <div class="row">
                    <div class="col-xl-4">
                        <!--begin:: Widgets/Latest Updates-->
                        <div class="m-portlet m-portlet--full-height m-portlet--fit ">
                            <div class="m-portlet__head ss--background">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            @lang('Cá nhân')
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-widget4 m-widget4--chart-bottom" style="min-height: 220px">
                                    <div class="m-widget4__item">
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__text">
                                                <a class="m-font-second"
                                                    href="{{ route('ticket.my_ticket',['tab' => 'my_ticket']) }}">@lang('Ticket của tôi')</a>
                                            </span>
                                        </div>
                                        <div class="m-widget4__ext">
                                            <span class="m-widget4__number">
                                                <a href="{{ route('ticket.my_ticket',['tab' => 'my_ticket']) }}"
                                                    class="m-font-second">{{ $ticketPersonal['myTicket'] }}</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="m-widget4__item">
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__text">
                                                <a class="m-font-second" href="{{ route('ticket.my_ticket',['tab' => 'my_created']) }}">@lang('Ticket tôi tạo')</a>
                                            </span>
                                        </div>
                                        <div class="m-widget4__ext">
                                            <span class="m-widget4__stats m-font-second">
                                                <span class="m-widget4__number">
                                                    <a href="{{ route('ticket.my_ticket',['tab' => 'my_created']) }}"
                                                        class="m-font-second">{{ $ticketPersonal['ticketCreateByMe'] }}</a>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="m-widget4__item m-widget4__item--last">
                                        <div class="m-widget4__info">
                                            <span class="m-widget4__text">
                                                <a class="m-font-second" href="{{ route('ticket') }}?ticket_not_done=1">@lang('Ticket chưa hoàn thành')</a>
                                            </span>
                                        </div>
                                        <div class="m-widget4__ext">
                                            <span class="m-widget4__stats m-font-second">
                                                <span class="m-widget4__number">
                                                    <a href="{{ route('ticket') }}?ticket_not_done=1"
                                                        class="m-font-second">{{ $ticketPersonal['inprocessTicket'] }}</a>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Latest Updates-->
                    </div>
                    <div class="col-xl-8">
                        <!--begin:: Widgets/Application Sales-->
                        <div class="m-portlet m-portlet--full-height ">
                            <div class="m-portlet__head ss--background">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            @lang('Ticket chưa hoàn thành')
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <!--begin::Widget 11-->
                                <div class="m-widget11">
                                    <div class="m-scrollable mCustomScrollbar _mCS_1 mCS-autoHide" data-scrollable="true"
                                        data-max-height="250"
                                        style="max-height: 250px; height: 250px; position: relative; overflow: visible;">
                                        <div id="mCSB_1"
                                            class="mCustomScrollBox mCS-minimal-dark mCSB_vertical mCSB_outside"
                                            style="max-height: none;" tabindex="0">
                                            <div id="mCSB_1_container"
                                                class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y tableFixHead"
                                                style="position:relative; top:0; left:0;" dir="ltr">
                                                <!--begin::Table-->
                                                
                                                <table class="table">
                                                    <!--begin::Thead-->
                                                    <thead>
                                                        <tr>
                                                            <td class="m-widget11__app fz-15">@lang('Tên Queue')</td>
                                                            <td class="text-center fz-15">@lang('Mới')</td>
                                                            <td class="text-center fz-15">@lang('Đang xử lý')</td>
                                                            <td class="text-center fz-15">@lang('Hoàn tất')</td>
                                                            <td class="text-center fz-15">@lang('Quá hạn')</td>
                                                        </tr>
                                                    </thead>
                                                    <!--end::Thead-->
                                                    <!--begin::Tbody-->
                                                    <tbody>
                                                        @php
                                                            // lấy danh sách chưa ,đang, đã, quá
                                                            $statusList = [1, 2, 3, 7];
                                                        @endphp
                                                        @foreach ($ticketInprocessList as $queueProcessId => $countStatus)
                                                                @php
                                                                    $count_ticket_inprocess = 0;
                                                                @endphp
                                                                @foreach ($statusList as $statusId)
                                                                    @php
                                                                        $count_ticket_inprocess += isset($countStatus[$statusId]['count']) ? $countStatus[$statusId]['count'] : 0;
                                                                    @endphp
                                                                @endforeach
                                                            <tr>
                                                                <td> 
                                                                    <span class="m-widget11__title w-5">
                                                                        {{ isset($listQueueName[$queueProcessId]) ? $listQueueName[$queueProcessId] : '' }} ({{ $count_ticket_inprocess }})
                                                                    </span>
                                                                </td>
                                                                @foreach ($statusList as $statusId)
                                                                    <td class="text-center">
                                                                        <span class="text-normal">
                                                                            <a class="m-font-second"
                                                                                href="{{ route('ticket') }}?ticket_status_id={{ $statusId }}&queue_process_id={{ $queueProcessId }}">
                                                                                {{ isset($countStatus[$statusId]['count']) ? $countStatus[$statusId]['count'] : 0 }}</a>
                                                                        </span>
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <!--end::Tbody-->
                                                </table>
                                                <!--end::Table-->
                                            </div>
                                        </div>
                                        <div id="mCSB_1_scrollbar_vertical"
                                            class="mCSB_scrollTools mCSB_1_scrollbar mCS-minimal-dark mCSB_scrollTools_vertical"
                                            style="display: none;">
                                            <div class="mCSB_draggerContainer">
                                                <div id="mCSB_1_dragger_vertical" class="mCSB_dragger"
                                                    style="position: absolute; min-height: 50px; height: 0px; top: 0px;">
                                                    <div class="mCSB_dragger_bar" style="line-height: 50px;"></div>
                                                </div>
                                                <div class="mCSB_draggerRail"></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <!--end::Widget 11-->
                            </div>
                        </div>
                        <!--end:: Widgets/Application Sales-->
                    </div>
                </div>

                <div class="m-portlet m-portlet--tabs m-portlet--head-solid-bg m-portlet--head-sm">
                    <div class="m-portlet__head ss--background">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">@lang('Ticket chưa phân công')
                                </h3>
                            </div>
                        </div>

                    </div>
                    <div class="m-portlet__body">
                        <div class="m-accordion m-accordion--default m-accordion--toggle-arrow" id="m_accordion_5"
                            role="tablist">
                            @foreach ($ticketUnAssignList as $key => $ticketUnAssign)
                                <div class="m-accordion__item m-accordion__item--default">
                                    <div class="m-accordion__item-head collapsed" srole="tab"
                                        id="m_accordion_5_item_{{ $key }}_head"
                                        data-toggle="collapse"
                                        href="#m_accordion_item_{{ $key }}_body"
                                        aria-expanded="false">
                                        <span class="m-accordion__item-icon"></span>
                                        <span class="m-accordion__item-title">
                                            {{ isset($listQueueName[$key]) ? $listQueueName[$key] : '' }}({{  (is_array($ticketUnAssign))?count($ticketUnAssign):0 }})</span>
                                        <span class="m-accordion__item-mode"></span>
                                    </div>
                                </div>
                                <div class="m-accordion__item-body collapse"
                                    id="m_accordion_item_{{ $key }}_body" role="tabpanel"
                                    aria-labelledby="m_accordion_item_{{ $key }}_head"
                                    data-parent="#m_accordion_5" style="">
                                    <div class="m-accordion__item-content">
                                        <table class="table table-striped m-table">
                                            <thead>
                                                <tr>
                                                    <th class="">@lang('#')</th>
                                                    <th class="">@lang('Mã Ticket')</th>
                                                    <th class="">@lang('Tiêu đề')</th>
                                                    <th class="">@lang('Loại yêu cầu')</th>
                                                    <th class="">@lang('Yêu cầu')</th>
                                                    <th class="">@lang('Thời gian phát sinh')</th>
                                                    <th class="">@lang('Thời gian bắt buộc hoàn thành')</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    
                                                    $count =1;
                                                @endphp
                                                @foreach ($ticketUnAssign as $ticketUnAssigns)
                                                <tr>
                                                    <th scope="row">{{ $count++ }}</th>
                                                    <td>
                                                        <span class="m--font-brand m--font-bolder"><a href="{{route('ticket.detail',$ticketUnAssigns['ticket_id'])}}">{{ $ticketUnAssigns['ticket_code'] }}</a></span>
                                                    </td>
                                                    <td>
                                                        {{ $ticketUnAssigns['title'] }}
                                                    </td>
                                                    <td>
                                                        {{ $ticketUnAssigns['issue_group_name'] }}
                                                    </td>
                                                    <td>
                                                        {{ $ticketUnAssigns['issue_name'] }}
                                                    </td>
                                                    <td>
                                                        {{ $ticketUnAssigns['date_issue'] != "" ? (\Carbon\Carbon::parse($ticketUnAssigns['date_issue'])->format('d/m/Y H:i')):'' }}
                                                    </td>
                                                    <td>
                                                        {{ $ticketUnAssigns['date_expected'] != "" ?(\Carbon\Carbon::parse($ticketUnAssigns['date_expected'])->format('d/m/Y H:i')):'' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('ticket.add', $ticketUnAssigns['ticket_id']) }}"
                                                            class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                            title="View "><i class="la la-edit"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/customize.css') }}">
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/ticket/ticket/dashboard.js?v=' . time()) }}" type="text/javascript">
    </script>
    {{-- <script src="{{ asset('static/backend/js/ticket/ticket/script.js?v=' . time()) }}" type="text/javascript">
    </script> --}}
    <script>
        var ticket_new = '{{ $ticketDashboad['newTicket'] }}';
        var ticket_processing = '{{ $ticketDashboad['inprocessTicket'] }}';
        var ticket_out_of_date = '{{ $ticketDashboad['expiredTicket'] }}';
        var total = '{{ $ticketDashboad['total'] }}';
        var value_new = '{{ $ticketDashboad['newTicketPercent'] }}';
        var value_processing = '{{ $ticketDashboad['inprocessTicketPercent'] }}';
        var value_out_of_date = '{{ $ticketDashboad['expiredTicketPercent'] }}';

        Dashboard.ticket_new = value_new;
        Dashboard.ticket_processing = value_processing;
        Dashboard.ticket_out_of_date = value_out_of_date;

        // Dashboard.start();
    </script>
@stop
