@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-order.png') }}" alt="" style="height: 20px;">
        @lang('QUẢN LÝ TICKET')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm my-ticket">
        <nav class="mb-0 pt-4">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link{{ !isset($params['tab']) || $params['tab'] !== 'my_created' ? ' active' :'' }}" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab"
                    aria-controls="nav-profile" aria-selected="false">
                    <h5>@lang('Ticket của tôi')</h5> 
                </a>
                <a class="nav-item nav-link{{ isset($params['tab']) && $params['tab'] == 'my_created' ? ' active' :'' }}" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                    aria-controls="nav-home" aria-selected="true">
                    <h5>@lang('Ticket tôi tạo')</h5>
                </a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade{{ isset($params['tab']) && $params['tab'] == 'my_created' ? ' show active' :'' }}" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="m-portlet__body pt-0">
                    <div id="autotable">
                        <form class="frmFilter bg">
                            <div class="row padding_row">
                                <div class="col-lg-3">
                                        <input type="text" class="form-control" name="search" placeholder="@lang("Nhập thông tin tìm kiếm")">
                                </div>
                                <div class="col-lg-3">
                                    <select name="ticket_type" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn loại yêu cầu')</option>
                                        @foreach (getTypeTicket() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="ticket_issue_id" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn yêu cầu')</option>
                                        @foreach ($requests as $value)
                                            <option value="{{ $value['ticket_issue_id'] }}">{{ $value['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="issule_level" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn cấp độ yêu cầu')</option>
                                        @foreach (levelIssue() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row padding_row">
                                <div class="col-lg-3">
                                    <select name="staff_notification_id" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn nhân viên thông báo')</option>
                                        @foreach ($staff as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="ticket_status_id" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn trạng thái')</option>
                                        @foreach ($ticketStatusList as $value)
                                            <option value="{{ $value['ticket_status_id'] }}">
                                                {{ $value['status_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                            style="background-color: #fff" id="date_issue" name="date_issue"
                                            autocomplete="off" placeholder="@lang('Thời gian phát sinh')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                            style="background-color: #fff" id="date_estimated" name="date_estimated"
                                            autocomplete="off" placeholder="@lang('Thời gian bắt buộc hoàn thành')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="padding_row row pb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <select name="queue_process_id" class="form-control select2 select2-active" id="">
                                                <option value="">@lang('Chọn queue xử lý')</option>
                                                @foreach ($queue as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <select name="handle_by" class="form-control select2 select2-active" id="">
                                                <option value="">@lang('Chọn người xử lý')</option>
                                                @foreach ($staff as $key => $value)
                                                    <option value="{{ $key }}">
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <select name="priority" class="form-control select2 select2-active" id="">
                                                <option value="">@lang('Độ ưu tiên')</option>
                                                @foreach (getPriority() as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex">
                                                <button class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                                    {{ __('XÓA BỘ LỌC') }}
                                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                                </button>
                                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <input type="hidden" name="created_by" value="{{\Auth::id()}}"> --}}
                        </form>
                        <div class="table-content m--padding-top-30">
                            {!! $listCreated !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade{{ !isset($params['tab']) || $params['tab'] == 'my_ticket' ? ' show active' :'' }}" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="m-portlet__body pt-0">
                    <div id="autotables">
                        <form class="frmFilter bg">
                            <div class="row padding_row">
                                <div class="col-lg-3">
                                        <input type="text" class="form-control" name="search" placeholder="@lang("Nhập thông tin tìm kiếm")">
                                </div>
                                <div class="col-lg-3">
                                    <select name="ticket_type" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn loại yêu cầu')</option>
                                        @foreach (getTypeTicket() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="ticket_issue_id" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn yêu cầu')</option>
                                        @foreach ($requests as $value)
                                            <option value="{{ $value['ticket_issue_id'] }}">{{ $value['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="issule_level" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn cấp độ yêu cầu')</option>
                                        @foreach (levelIssue() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row padding_row">
                                <div class="col-lg-3">
                                    <select name="staff_notification_id" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn nhân viên thông báo')</option>
                                        @foreach ($staff as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="ticket_status_id" class="form-control select2 select2-active" id="">
                                        <option value="">@lang('Chọn trạng thái')</option>
                                        @foreach ($ticketStatusList as $value)
                                            <option value="{{ $value['ticket_status_id'] }}">
                                                {{ $value['status_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                            style="background-color: #fff" id="date_issue" name="date_issue"
                                            autocomplete="off" placeholder="@lang('Thời gian phát sinh')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                            style="background-color: #fff" id="date_estimated" name="date_estimated"
                                            autocomplete="off" placeholder="@lang('Thời gian bắt buộc hoàn thành')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="padding_row row pb-3">
                                <div class="col-lg-12">
                                    <div class="row">
                                        {{--<div class="col-lg-3">--}}
                                            {{--<select name="queue_process_id" class="form-control select2 select2-active" id="">--}}
                                                {{--<option value="">@lang('Chọn queue xử lý')</option>--}}
                                                {{--@foreach ($queue as $key => $value)--}}
                                                    {{--<option value="{{ $key }}">--}}
                                                        {{--{{ $value }}</option>--}}
                                                {{--@endforeach--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-lg-3">--}}
                                            {{--<select name="handle_by" class="form-control select2 select2-active" id="">--}}
                                                {{--<option value="">@lang('Chọn người xử lý')</option>--}}
                                                {{--@foreach ($staff as $key => $value)--}}
                                                    {{--<option value="{{ $key }}">--}}
                                                        {{--{{ $value }}</option>--}}
                                                {{--@endforeach--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
                                        {{-- <div class="col-lg-3">
                                            <select name="priority" class="form-control select2 select2-active" id="">
                                                <option value="">@lang('Độ ưu tiên')</option>
                                                @foreach (getPriority() as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col-lg-3">
                                            <select name="sort_priority" class="form-control select2 select2-active" id="">
                                                <option value="">@lang('Chọn độ ưu tiên')</option>
                                                <option value="DESC">@lang('Từ thấp đến cao')</option>
                                                <option value="ASC">@lang('Từ cao đến thấp')</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="d-flex">
                                                <button class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                                    {{ __('XÓA BỘ LỌC') }}
                                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                                </button>
                                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="processor_by" value="{{\Auth::id()}}">
                        </form>
                        <div class="table-content m--padding-top-30">
                            {!! $listAssign !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/customize.css') }}">
@stop
@section('after_script')
    @include('ticket::language.lang')
    <script src="{{ asset('static/backend/js/ticket/ticket/my-ticket.js?v=' . time()) }}" type="text/javascript"></script>
@stop
