@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ TICKET')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phieu-custom.css') }}">
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phu-custom.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <style>
        .main-timeline {
            /*overflow: hidden;*/
            position: relative;
            padding: 60px 0;
        }

        .main-timeline:before {
            content: "";
            width: 1px;
            height: 100%;
            background: #cfcdcd;
            position: absolute;
            top: 0;
            left: 0%;
        }

        .main-timeline .timeline {
            width: 100%;
            clear: both;
            position: relative;
        }

        .main-timeline .timeline:before,
        .main-timeline .timeline:after {
            content: "";
            display: block;
            clear: both;
        }

        .main-timeline .timeline:first-child:before,
        .main-timeline .timeline:last-child:before {
            content: "";
            width: 11px;
            height: 11px;
            background: #cfcdcd;
            box-sizing: content-box;
            border: 5px solid #fff;
            box-shadow: 0 0 0 2px #cfcdcd;
            position: absolute;
            top: -54px;
            right: -11px;
            transform: rotate(45deg);
        }

        .main-timeline .timeline:last-child:before {
            top: auto;
            bottom: -54px;
        }

        .main-timeline .timeline:last-child:nth-child(even):before {
            right: auto;
            left: -11px;
        }

        .main-timeline .timeline-icon {
            width: 100px;
            height: 24px;
            background: #fff;
            position: absolute;
            top: 0;
            right: -13px;
            z-index: 1;
        }

        .main-timeline .timeline:hover .timeline-icon:before {
            background: #4fc4ca;
        }

        .main-timeline .timeline-content {
            width: 85%;
            padding: 18px 30px;
            background: #fff;
            text-align: right;
            float: left;
            border: 1px solid transparent;
            position: relative;
            transition: all 0.3s ease 0s;
        }

        .main-timeline .timeline-content:before {
            content: "";
            display: block;
            width: 14px;
            height: 14px;
            background: #fff;
            border: 1px solid #cfcdcd;
            position: absolute;
            top: 21px;
            right: -7.3px;
            transform: rotate(45deg);
            transition: all 0.2s ease 0s;
        }

        .main-timeline .timeline:hover .timeline-content:before {
            background: #4fc4ca;
            border-color: #4fc4ca;
        }

        .main-timeline .timeline-content:after {
            content: "";
            width: 11%;
            height: 1px;
            background: #cfcdcd;
            position: absolute;
            top: 28px;
            right: -14%;
        }

        .main-timeline .date {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #4fc4ca;
            margin: 0 0 8px;
            transition: all 0.3s ease 0s;
        }

        .main-timeline .timeline:hover .date {
            color: #444;
        }

        .main-timeline .title {
            font-size: 18px;
            color: #444;
            margin-top: 0;
            transition: all 0.3s ease 0s;
        }

        .main-timeline .timeline:hover .title {
            color: #4fc4ca;
        }

        .main-timeline .description {
            font-size: 16px;
            color: #777;
            line-height: 28px;
            margin-top: 8px;
        }

        .main-timeline .timeline:nth-child(n),
        .main-timeline .timeline:nth-child(n) .timeline-content,
        .main-timeline .timeline:nth-child(2n),
        .main-timeline .timeline:nth-child(2n) .timeline-content {
            float: right;
            text-align: left;
        }

        .main-timeline .timeline:nth-child(n) .timeline-icon, .main-timeline .timeline:nth-child(2n) .timeline-icon {
            right: 0;
            left: -12px;
        }

        .main-timeline .timeline:first-child:before {
            right: 99%;
        }

        .main-timeline .timeline:nth-child(n) .timeline-content:before {
            left: -7.3px;
        }

        .main-timeline .timeline:nth-child(2n) .timeline-content:before {
            left: -7.3px;
        }

        .main-timeline .timeline:nth-child(n) .timeline-content:after {
            left: -14%;
        }

        .main-timeline .timeline:nth-child(2n) .timeline-content:after {
            left: -14%;
        }

        .timeline-content p {
            display: inline;
        }

        .listProductMaterialIncurredPopup td, .listProductMaterialIncurred td {
            vertical-align: inherit !important;
        }

        #appendModelAdd .select2 {
            width: 100% !important;
        }

        .delete-file {
            border-radius: 50%;
            border: 0;
        }

        .delete-file:hover {
            cursor: pointer;
            background: red;
            color: #fff;
        }

        .listProductMaterialIncurredDetail .blockListIncurred td:last-child {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">

                    </span>
                    <h2 class="m-portlet__head-text">
                        <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                        @lang('XEM CHI TIẾT TICKET')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--@if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))--}}
                <a href="javascript:void(0)" onclick="WorkChild.showPopup('', 'ticket', '{{$item['ticket_id']}}')"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> @lang('THÊM CÔNG VIỆC')</span>
                    </span>
                </a>
                {{--@endif--}}
            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body pt-0">
                <div class="row">
                    <div class="col-lg-12 modal-header mb-3">
                        <h5>@lang('Thông tin chung')</h5>
                        <input type="hidden" name="ticket_id" value="{{ $item['ticket_id'] }}">
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tỉnh/Thành phố'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="localtion_id" class="form-control select2 select2-active"
                                        id="localtion_id">
                                    <option value="">@lang('Chọn tỉnh/thành phố')</option>
                                    @foreach ($optionProvince as $key => $value)
                                        <option value="{{ $key }}"
                                                {{ $item['localtion_id'] == $key ? ' selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Yêu cầu'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="ticket_issue_id" class="form-control select2 select2-active"
                                        id="ticket_issue_id">
                                    <option value="">@lang('Yêu cầu')</option>
                                    @foreach ($requests as $key => $value)
                                        <option value="{{ $key }}"
                                                {{ $item['ticket_issue_id'] == $key ? ' selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mức độ ưu tiên'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="priority" class="form-control select2 select2-active" id="priority">
                                    <option value="">@lang('Chọn mức độ ưu tiên')</option>
                                    @foreach (getPriority() as $key => $value)
                                        <option value="{{ $key }}"
                                                {{ $item['priority'] == $key ? ' selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Loại ticket'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="ticket_type" class="form-control select2 select2-active" id="ticket_type">
                                    <option value="">@lang('Chọn loại ticket')</option>
                                    @foreach (getTypeTicket() as $key => $value)
                                        <option value="{{ $key }}"
                                                {{ $item['ticket_type'] == $key ? ' selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Cấp độ sự cố'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="issule_level" class="form-control select2 select2-active"
                                        id="issule_level">
                                    <option value="">@lang('Chọn cấp độ sự cố')</option>
                                    @foreach (levelIssue() as $key => $value)
                                        <option value="{{ $key }}"
                                                {{ $item['issule_level'] == $key ? ' selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 modal-header mb-3">
                        <h5>@lang('Thông chi tiết')</h5>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tiêu đề'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" id="title" name="title"
                                   value="{{ $item['title'] }}" placeholder="@lang('Nhập tiêu đề')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nội dung'):
                            </label>
                            <textarea class="form-control m-input" id="description" name="description" rows="6" cols="5"
                                      placeholder="@lang('Nhập nội dung')...">{{ $item['description'] }}</textarea>
                        </div>
                        <div class="row">
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Khách hàng'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <a class="form-control m-input"
                                       href="{{route('admin.customer.detail', $item['customer_id'])}}" target="_blank">
                                        {{$item['customer_name']}}
                                    </a>
                                </div>
                            </div>
                            <div class="form-group m-form__group col-lg-6">
                                <label class="black_title">
                                    @lang('Số điện thoại'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" value="{{ $item['customer_phone'] }}">
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="customer_address" class="form-control m-input"
                                   id="customer_address"
                                   placeholder="{{ __('Địa chỉ') }}..." value="{{ $item['customer_address'] }}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên thông báo'):
                            </label>
                            <div class="input-group">
                                <select name="staff_notification_id" class="form-control select2 select2-active"
                                        id="staff_notification_id">
                                    <option value="">@lang('Chọn nhân viên thông báo')</option>
                                    @foreach ($staff as $key => $value)
                                        <option value="{{ $key }}"
                                                {{ $item['staff_notification_id'] == $key ? ' selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($item['ticket_status_id'])
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Trạng thái'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select name="ticket_status_id" class="form-control select2 select2-active"
                                            id="ticket_status_id" disabled>
                                        <option value="">@lang('Chọn Trạng thái')</option>
                                        @foreach ($ticketStatusList as $key => $value)
                                            <option value="{{ $key }}" {{ $item['ticket_status_id'] == $key ? ' selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian phát sinh'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input date-timepicker" disabled
                                       placeholder="@lang('Thời gian phát sinh')" id="date_issue" name="date_issue"
                                       value="{{ $item['date_issue'] != '' ? \Carbon\Carbon::parse($item['date_issue'])->format('d/m/Y H:i') : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian dự kiến hoàn thành')
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" disabled
                                       placeholder="@lang('Thời gian dự kiến hoàn thành')" id="date_estimated"
                                       name="date_estimated"
                                       value="{{ $item['date_expected'] != '' ? \Carbon\Carbon::parse($item['date_estimated'])->format('d/m/Y H:i') : '' }}">
                                <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                    class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian bắt buộc hoàn thành')
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" disabled
                                       placeholder="@lang('Thời gian bắt buộc hoàn thành')" id="date_expected"
                                       name="date_expected"
                                       value="{{ $item['date_expected'] != '' ? \Carbon\Carbon::parse($item['date_expected'])->format('d/m/Y H:i') : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian khách hàng yêu cầu'):
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input date-timepicker" disabled
                                       placeholder="@lang('Thời gian khách hàng yêu cầu')" id="date_expected"
                                       name="date_expected"
                                       value="{{ $item['date_expected'] != '' ? \Carbon\Carbon::parse($item['date_expected'])->format('d/m/Y H:i') : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        @if (in_array($item['ticket_status_id'], [4]))
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Thời gian hoàn thành thực tế'):
                                </label>
                                <div class="input-group date">
                                    <input type="text" class="form-control m-input date-timepicker" disabled
                                           placeholder="@lang('Thời gian hoàn thành thực tế')" id="date_finished"
                                           name="date_finished"
                                           value="{{ $item['date_finished'] != '' ? \Carbon\Carbon::parse($item['date_finished'])->format('d/m/Y H:i') : '' }}">
                                    <div class="input-group-append">
                                    <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Queue xử lý'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="queue_process_id" class="form-control select2 select2-active"
                                        id="queue_process_id">
                                    <option value="">@lang('Chọn queue xử lý')</option>
                                    @foreach ($queue as $key => $value)
                                        <option value="{{ $key }}" {{ $item['queue_process_id'] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên chủ trì'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="operate_by" class="form-control select2 select2-active" id="operate_by">
                                    <option value="">@lang('Chọn nhân viên chủ trì')</option>
                                    @foreach ($staff as $key => $value)
                                        <option value="{{ $key }}" {{ $item['operate_by'] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên xử lý'):
                            </label>
                            <div class="input-group">
                                <select name="processor" class="form-control select2 select2-active" id="processor"
                                        multiple data-placeholder="Chọn nhân viên xử lý">
                                    <option value=""></option>
                                    @foreach ($staff as $key => $value)
                                        <option value="{{ $key }}" @if (isset($item->processor) && count($item->processor))
                                            @foreach ($item->processor as $processor_data)
                                                {{ $processor_data->process_by == $key ? 'selected' : '' }}
                                                    @endforeach
                                                @endif
                                        >{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {{-- <label>
                                {{ __('Đính kèm') }}:
                            </label>
                            <div class="form-group m-form__group">
                                <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                    onclick="ticket.modalFile()">
                                    <i class="fa fa-plus-circle"></i> @lang('File kèm theo')
                                </a>
                            </div> --}}
                            <div class="div_file_ticket">
                                @if (isset($item->file) && count($item->file) > 0)
                                    @foreach ($item->file as $v)
                                        @if ($v['group'] == 'ticket')
                                            <div class="form-group m-form__group div_file row">
                                                <input type="hidden" name="file_ticket" value="{{ $v['path'] }}">
                                                <a target="_blank" href="{{ url($v['path']) }}"
                                                   class="file_ticket">
                                                    {{ ltrim($v['path'],TICKET_UPLOADS_PATH) }}
                                                </a>
                                                {{--<a style="color:black;" href="javascript:void(0)"--}}
                                                {{--onclick="ticket.removeFile(this)">--}}
                                                {{--<i class="la la-trash"></i>--}}
                                                {{--</a>--}}
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="m-portlet__body">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab"
                           href="#m_tabs_3_1">@lang('Danh sách vật tư')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_tabs_3_2">@lang('Biên bản nghiệm thu')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_tabs_3_3">@lang('Hình ảnh ticket')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_tabs_3_4">@lang('Đánh giá ticket')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_tabs_3_5">@lang('Vị trí')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_tabs_3_6">@lang('Lịch sử')</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active show" id="m_tabs_3_1" role="tabpanel">
                        <div class="col-lg-12 modal-header mb-3">
                            <h5>@lang('Danh sách phiếu đề xuất vật tư')</h5>
                        </div>
                        <div class="table-responsive pt-3">
                            <table class="table table-striped m-table ss--header-table ss--nowrap table-list-material">
                                <thead>
                                <tr>
                                    <th class="ss--font-size-th">#</th>
                                    <th class="ss--font-size-th">{{ __('Mã phiếu yêu cầu') }}</th>
                                    <th class="ss--font-size-th">{{ __('Nội dung đề xuất') }}</th>
                                    <th class="ss--font-size-th">{{ __('Người đề xuất') }}</th>
                                    <th class="ss--font-size-th">{{ __('Ngày đề xuất') }}</th>
                                    <th class="ss--font-size-th">{{ __('Người duyệt') }}</th>
                                    <th class="ss--font-size-th">{{ __('Thời gian duyệt') }}</th>
                                    <th class="ss--font-size-th">{{ __('Trạng thái') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12 modal-header mb-3">
                            <h5>@lang('Danh sách vật tư đã đề xuất')</h5>
                        </div>
                        <div class="table-responsive pt-3">
                            <table class="table table-striped m-table ss--header-table ss--nowrap table-list-material-detail">
                                <thead>
                                <tr>
                                    <th class="ss--font-size-th">#</th>
                                    <th class="ss--font-size-th">{{ __('Mã vật tư') }}</th>
                                    <th class="ss--font-size-th text-left">{{ __('Tên vật tư') }}</th>
                                    <th class="ss--font-size-th">{{ __('Số lượng tạm ứng') }}</th>
                                    <th class="ss--font-size-th">{{ __('Số lượng duyệt') }}</th>
                                    <th class="ss--font-size-th">{{ __('Số lượng thực tế') }}</th>
                                    <th class="ss--font-size-th">{{ __('Số lượng hoàn ứng') }}</th>
                                    {{-- <th class="ss--font-size-th">{{ __('Trạng thái') }}</th> --}}
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        @if (count($listIncurred))
                            <div class="col-12 pt-3">
                                <h5>{{__('ticket::acceptance.list_incurred')}}</h5>
                                <table class="table table-striped m-table m-table--head-bg-default mt-2"
                                       id="table-config">
                                    <thead class="bg">
                                    <tr>
                                        <th class="ss--font-size-th">#</th>
                                        <th class="ss--font-size-th text-center">{{__('ticket::acceptance.product_incurred_code')}}</th>
                                        <th class="ss--font-size-th text-center">{{__('ticket::acceptance.product_incurred_name')}}</th>
                                        <th class="ss--font-size-th text-center">{{__('ticket::acceptance.quantity')}}</th>
                                        <th class="ss--font-size-th text-center">{{__('ticket::acceptance.unit')}}</th>
                                        <th class="ss--font-size-th text-center">{{__('ticket::acceptance.price')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="listProductMaterialIncurred listProductMaterialIncurredDetail">
                                    @foreach ($listIncurred as $key => $item_incurred)
                                        <tr class="">
                                            <td class="">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $item_incurred->product_code }}</td>
                                            <td class="text-center">{{ $item_incurred->product_name }}</td>
                                            <td class="text-center">{{ $item_incurred->quantity }}</td>
                                            <td class="text-center">{{ $item_incurred->unit_name }}</td>
                                            <td class="text-center">{{ number_format($item_incurred->money, 0, '', '.') }}
                                                VND
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @endif
                    </div>
                    <div class="tab-pane" id="m_tabs_3_2" role="tabpanel">
                        @if(count($listAcceptance) != 0)
                            <div class="table-responsive pt-3">
                                <table class="table table-striped m-table ss--header-table ss--nowrap">
                                    <thead>
                                    <tr>
                                        <th class="ss--font-size-th">#</th>
                                        <th class="ss--font-size-th">{{ __('Mã biên bản') }}</th>
                                        <th class="ss--font-size-th">{{ __('Tên biên bản') }}</th>
                                        <th class="ss--font-size-th">{{ __('Mã ticket') }}</th>
                                        <th class="ss--font-size-th">{{ __('Khách hàng') }}</th>
                                        <th class="ss--font-size-th">{{ __('Người tạo') }}</th>
                                        <th class="ss--font-size-th">{{ __('Người ký') }}</th>
                                        <th class="ss--font-size-th">{{ __('Ngày ký') }}</th>
                                        <th class="ss--font-size-th">{{ __('Trạng thái') }}</th>
                                        <th class="ss--font-size-th"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($listAcceptance as $key => $itemList)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td><a class="ss--text-black"
                                                   href="{{route('ticket.acceptance.detail',['id' => $itemList['ticket_acceptance_id']])}}">{{$itemList['ticket_acceptance_code']}}</a>
                                            </td>
                                            <td>{{$itemList['title']}}</td>
                                            <td>{{$itemList['ticket_code']}}</td>
                                            <td>{{$itemList['customer_name']}}</td>
                                            <td>{{$itemList['created_name']}}</td>
                                            <td>{{$itemList['sign_by']}}</td>
                                            <td>{{$itemList['sign_date'] != '' && $itemList['sign_date'] != null && $itemList['sign_date'] != '000-00-00 00:00:00' ? \Carbon\Carbon::parse($itemList['sign_date'])->format('d/m/Y H:i') : ''}}</td>
                                            <td>{{$itemList['status'] == 'new' ? 'Mới' : ($itemList['status'] == 'approve' ? 'Đã ký' : 'Huỷ')}}</td>
                                            <td>
                                                @if(!in_array($itemList['status'], ['approve', 'cancel']))
                                                    <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                       href="{{route('ticket.acceptance.edit',['ticketid' => $itemList['ticket_acceptance_id']])}}">
                                                        <i class="la la-edit"></i>
                                                    </a>
                                                @else
                                                    <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                       href="{{route('ticket.acceptance.detail',['ticketid' => $itemList['ticket_acceptance_id']])}}">
                                                        <i class="la la-eye"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            {{-- <a href="{{route('ticket.acceptance.add',['ticketid' => $item['ticket_id']])}}" target="_blank" class="btn  btn-sm m-btn--icon color">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    {{ __('Thêm biên bản nghiệm thu') }}
                                </span>
                            </span>
                            </a> --}}
                        @endif
                    </div>
                    <div class="tab-pane" id="m_tabs_3_3" role="tabpanel">
                        <div class="form-group m-form__group ">
                            <div class="row">
                                <div class="col-lg-12  w-col-mb-100 div_avatar">
                                    <div class="show_image">
                                        @if (isset($item->images) && count($item->images) > 0)
                                            @foreach ($item->images as $v)
                                                @if ($v['group'] == 'image')
                                                    <input type="hidden" name="image[]"
                                                           value="{{$v->path}}">
                                                    <a target="_blank" href="{{$v->path}}"
                                                       class="file_image">
                                                        <img src="{{$v->path}}" class="file_image"
                                                             alt=""
                                                             width="100px" height="100px">
                                                    </a>
                                                    {{--<a style="color:black;"--}}
                                                    {{--href="javascript:void(0)"--}}
                                                    {{--onclick="ticket.removeFile(this)">--}}
                                                    {{--<i class="la la-trash"></i>--}}
                                                    {{--</a>--}}
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="m_tabs_3_4" role="tabpanel">
                        @if(isset($item->rating->ticket_rating_id))
                            <div class="d-flex">
                                <div class="col-md-6">
                                    <div class="form-group m-form__group">
                                        <label for="">@lang('Chấm điểm')</label>
                                        <div class="form-group form-control p-0 bg-disabled">
                                            <div class="rate">
                                                <input type="radio" id="star5" name="rate" value="5"
                                                       disabled {{ ( isset($item->rating->point) && $item->rating->point == 5 )?'checked':'' }} />
                                                <label for="star5" title="text">5 stars</label>
                                                <input type="radio" id="star4" name="rate" value="4"
                                                       disabled {{ ( isset($item->rating->point) && $item->rating->point == 4 )?'checked':'' }} />
                                                <label for="star4" title="text">4 stars</label>
                                                <input type="radio" id="star3" name="rate" value="3"
                                                       disabled {{ ( isset($item->rating->point) && $item->rating->point == 3 )?'checked':'' }} />
                                                <label for="star3" title="text">3 stars</label>
                                                <input type="radio" id="star2" name="rate" value="2"
                                                       disabled {{ ( isset($item->rating->point) && $item->rating->point == 2 )?'checked':'' }} />
                                                <label for="star2" title="text">2 stars</label>
                                                <input type="radio" id="star1" name="rate" value="1"
                                                       disabled {{ ( isset($item->rating->point) && $item->rating->point == 1 )?'checked':'' }} />
                                                <label for="star1" title="text">1 star</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title w-100">
                                            @lang('Nội dung'):
                                        </label>
                                        <textarea class="form-control m-input" name="description" rows="6"
                                                  cols="5"
                                                  placeholder="@lang('Nhập nội dung đánh giá')..."
                                                  disabled>{{ isset($item->rating->description)?$item->rating->description:'' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title w-100">
                                            @lang('Người tạo'):
                                        </label>
                                        <input type="text" class="form-control"
                                               value="{{isset($item->rating->full_name_rating)?$item->rating->full_name_rating:''}}"
                                               disabled>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black_title w-100">
                                            @lang('Ngày tạo'):
                                        </label>
                                        <input type="text" class="form-control"
                                               value="{{\Carbon\Carbon::parse($item->rating->created_at)->format('d/m/Y H:i')}}"
                                               disabled>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane" id="m_tabs_3_5" role="tabpanel"></div>
                    <div class="tab-pane" id="m_tabs_3_6" role="tabpanel">
                        <div class="row ml-3">
                            @if(($item->history) != null)
                                <div class="main-timeline w-100">
                                    @php
                                        $created_at = '';
                                        $date = '';
                                    @endphp
                                    @foreach($item->history as $history)
                                        @php
                                            $date = \Carbon\Carbon::parse($history->created_at)->format('d-m-Y');
                                        @endphp
                                        @if($created_at != $date)
                                            <div class="timeline">
                                                <div class="timeline-icon">{{$date}}</div>
                                                @endif
                                                <div class="timeline-content">
                                                    @if(app()->getLocale() == 'en')
                                                        {!! $history->note_en !!}
                                                    @else
                                                        {!! $history->note_vi !!}
                                                    @endif
                                                </div>
                                                @php
                                                    $created_at = $date;
                                                @endphp

                                                @if($created_at != $date)
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer w-100">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{ route('ticket') }}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>@lang('HỦY')</span>
                                </span>
                        </a>
                        {{-- <button type="button" onclick="edit.save({{ $item['ticket_id'] }})"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                            </span>
                        </button> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="my-modal"></div>
    @include('ticket::ticket.popup.modal-file')
    @include('ticket::ticket.popup.rating')
    @include('ticket::ticket.popup.add-material')
    @include('ticket::ticket.popup.view-material')
    @include('ticket::ticket.popup.edit-material')
    @include('ticket::ticket.popup.replace_material')
    <div class="d-none">
        <div id="input-counter">
            <div class="number">
                <span class="minus">-</span>
                <input type="number" class="number-input" name="{product_id}" value="{value}" min="1" max="{max}"/>
                <span class="plus">+</span>
            </div>
        </div>
        <div id="select-status">
            <select name="{product_id}" class="form-control mw-100px">
                @foreach ($statusMaterialItem as $key => $val)
                    <option value="{{$key}}">{{$val}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
@endsection
@section('after_script')
    @include('ticket::language.lang')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script> --}}
    {{-- <script src="{{ asset('static/backend/js/ticket/ticket/dropzone.js?v=' . time()) }}" type="text/javascript"></script> --}}
    <script src="{{ asset('static/backend/js/ticket/ticket/script.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-work/managerWork/list.js?v=' . time()) }}"
            type="text/javascript"></script>
    <script>
        edit._init();
        var check_detail = 1;
    </script>
    <script type="text/template" id="tpl-file">
        <div class="form-group m-form__group div_file row">
            <input type="hidden" name="file_ticket" value="{fileName}">
            <a target="_blank" href="{{url(TEMP_PATH)}}/{fileName}" class="file_ticket">
                {fileName}
            </a>
            <a style="color:black;"
               href="javascript:void(0)" onclick="ticket.removeFile(this)">
                <i class="la la-trash"></i>
            </a>
        </div>
    </script>

    <script>
        ticket.dropzoneFile();

        $(document).ready(function () {
            var obj;
            $('#form-edit select,#form-edit input,#form-edit textarea,button').prop('disabled', 'disabled');
        });

        detail.loadLocation('{{$item->ticket_id}}')
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{config()->get('config.google_api_key')}}&v=weekly" defer></script>
@stop
