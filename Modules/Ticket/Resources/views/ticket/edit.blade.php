@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ TICKET')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phieu-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon"></span>
                    <h2 class="m-portlet__head-text">
                        @if ($item['ticket_id'])
                            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                            @lang('CHỈNH SỬA TICKET')
                        @else
                            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                            @lang('THÊM TICKET')
                        @endif

                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body pt-0">
                <div class="row">
                    <div class="col-lg-12 modal-header mb-3">
                        <h5>@lang('Thông tin chung')</h5>
                        <input type="hidden" name="ticket_id" value="{{ $item['ticket_id'] }}">
                        <input type="hidden" name="created_at" value="{{ $item['created_at'] }}">
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
                                                @php
                                                    if($item['localtion_id']){
                                                        echo ($item['localtion_id'] == $key ? ' selected': '');
                                                    }else {
                                                        echo ((79 == $key) ? ' selected': '');
                                                    }

                                                @endphp
                                        >{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Loại yêu cầu'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="ticket_type" class="form-control select2 select2-active" id="ticket_type">
                                    <option value="">@lang('Chọn loại yêu cầu')</option>
                                    @foreach (getTypeTicket() as $key => $value)
                                        @if (isset($contractPartnerId) && $contractPartnerId != '' && $key == 2)
                                            <option value="{{ $key }}" selected>{{ $value }}
                                            </option>
                                        @else
                                            <option value="{{ $key }}"
                                                    {{ $item['ticket_type'] == $key ? ' selected' : ($key == 1 ? ' selected' : '') }}>
                                                {{ $value }}
                                            </option>
                                        @endif
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
                                    <option value="">@lang('Chọn yêu cầu')</option>
                                    {{-- @foreach ($requests as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ $item['ticket_issue_id'] == $key ? ' selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mức độ ưu tiên'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="priority" class="form-control select2 select2-active" id="priority">
                                    <option value="">@lang('Chọn mức độ ưu tiên')</option>
                                    @foreach (getPriority() as $key => $value)
                                        <option value="{{ $key }}" @if ($item['priority'] != '')
                                            {{ $item['priority'] == $key ? ' selected' : '' }}
                                                @else
                                            {{ 'L' == $key ? ' selected' : '' }}
                                                @endif
                                        >
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Cấp độ yêu cầu'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="issule_level" class="form-control " id="issule_level" disabled>
                                    {{-- select2 select2-active --}}
                                    <option value=""></option>
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
                            <textarea class="form-control" id="description" name="description" rows="6" cols="5"
                                      contenteditable="true"
                                      placeholder="@lang('Nhập nội dung')...">{{$textOrderDetail != null ? $textOrderDetail : $item['description'] }}</textarea>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Khách hàng'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="customer_id" class="form-control select2 select2-active" id="customer_id">
                                    <option value="">@lang('Chọn khách hàng')</option>
                                    @foreach ($customer as $key => $value)
                                        @if ($value != null)
                                            <option value="{{ $key }}"
                                                    {{ $item['customer_id'] == $key ? ' selected' : '' }}
                                                    {{ isset($infoCustomer) && $infoCustomer['customer_id'] == $key ? ' selected' : '' }}
                                                    {{$infoOrder != null && $infoOrder['customer_id'] == $key ? 'selected': ''}}
                                            >
                                                {{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="customer_address" class="form-control m-input"
                                   id="customer_address"
                                   placeholder="{{ __('Địa chỉ') }}..."
                                   value="{{$infoOrder != null ? $infoOrder['address']: $item['customer_address']}}" {{$check_done_status}}>

                            <select name="customer_address_select" class="d-none">
                                @foreach ($getAdress as $key => $value)
                                    @if ($value != null)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên thông báo'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="staff_notification_id" class="form-control select2 select2-active"
                                        id="staff_notification_id">
                                    <option value="">@lang('Chọn nhân viên thông báo')</option>
                                    @foreach ($staff as $key => $value)
                                        <option value="{{ $key }}"
                                                @php
                                                    if($item['staff_notification_id']){
                                                        if($item['staff_notification_id'] == $key){
                                                            echo ' selected';
                                                        }
                                                    }else {
                                                        if($key == \Auth::id()){
                                                            echo ' selected';
                                                        }
                                                    }
                                                @endphp
                                        >
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
                                    @php
                                        if ($item['ticket_status_id'] == 1) {
                                            unset($ticketStatusList[3]);
                                            unset($ticketStatusList[4]);
                                            unset($ticketStatusList[6]);
                                            unset($ticketStatusList[7]);
                                        } elseif ($item['ticket_status_id'] == 2) {
                                            unset($ticketStatusList[1]);
                                            unset($ticketStatusList[4]);
                                            unset($ticketStatusList[6]);
                                            unset($ticketStatusList[7]);
                                        } elseif ($item['ticket_status_id'] == 3) {
                                            unset($ticketStatusList[1]);
                                            unset($ticketStatusList[2]);
                                            unset($ticketStatusList[5]);
                                            // unset($ticketStatusList[6]);
                                            unset($ticketStatusList[7]);
                                        } elseif ($item['ticket_status_id'] == 4) {
                                            unset($ticketStatusList[1]);
                                            unset($ticketStatusList[2]);
                                            unset($ticketStatusList[3]);
                                            unset($ticketStatusList[5]);
                                            unset($ticketStatusList[6]);
                                            unset($ticketStatusList[7]);
                                        } elseif ($item['ticket_status_id'] == 5) {
                                            unset($ticketStatusList[1]);
                                            unset($ticketStatusList[2]);
                                            unset($ticketStatusList[3]);
                                            unset($ticketStatusList[6]);
                                            unset($ticketStatusList[7]);
                                        }elseif ($item['ticket_status_id'] == 6) {
                                            unset($ticketStatusList[1]);
                                            unset($ticketStatusList[4]);
                                            unset($ticketStatusList[2]);
                                            unset($ticketStatusList[7]);
                                        }
                                    @endphp
                                    <select name="ticket_status_id" class="form-control select2 select2-active"
                                            id="ticket_status_id">
                                        <option value="">@lang('Chọn Trạng thái')</option>
                                        @foreach ($ticketStatusList as $key => $value)
                                            <option value="{{ $key }}"
                                                    {{ $item['ticket_status_id'] == $key ? ' selected' : '' }}>
                                                {{ $value }}</option>
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
                                <input type="text" class="form-control m-input" readonly=""
                                       placeholder="@lang('Thời gian phát sinh')" id="date_issue" name="date_issue"
                                       @if ($infoOrder != null)
                                       value="{{$infoOrder['created_at'] != null ? \Carbon\Carbon::parse($infoOrder['created_at'])->format('d/m/Y H:i') : '' }}"
                                       @else
                                       value="{{ $item['date_issue'] != '' ? \Carbon\Carbon::parse($item['date_issue'])->format('d/m/Y H:i') : '' }}"
                                        @endif
                                >
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
                                <input type="text" class="form-control m-input" autocomplete="off"
                                       placeholder="@lang('Thời gian dự kiến hoàn thành')" id="date_estimated"
                                       name="date_estimated"
                                       @if ($infoOrder != null)
                                       value="{{$infoOrder['date_finish'] != null ? \Carbon\Carbon::parse($infoOrder['date_finish'])->format('d/m/Y H:i') : '' }}"
                                       @else
                                       value="{{ $item['date_estimated'] != '' ? \Carbon\Carbon::parse($item['date_estimated'])->format('d/m/Y H:i') : '' }}"
                                       @endif
                                       >
                                <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                    class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                        @if ($item['ticket_id'])
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
                                    <input type="text" class="form-control m-input" readonly=""
                                           placeholder="@lang('Thời gian khách hàng yêu cầu')" id="date_request"
                                           name="date_request"
                                           value="{{ $item['date_request'] != '' ? \Carbon\Carbon::parse($item['date_request'])->format('d/m/Y H:i') : '' }}">
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
                                        <option value="{{ $key }}"
                                                {{ $item['queue_process_id'] == $key ? ' selected' : '' }}>
                                            {{ $value }}</option>
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
                                    {{-- @foreach ($staff as $key => $value)
                                        <option value="{{ $key }}" {{ $item['operate_by'] == $key ? ' selected' : '' }}>{{ $value }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên xử lý')
                            </label>
                            <div class="input-group">
                                <select name="processor[]" class="form-control select2 select2-active" id="processor"
                                        multiple data-placeholder="Chọn nhân viên xử lý"{{$check_done_status}}>
                                    <option value=""></option>
                                    {{-- @foreach ($staff as $key => $value)
                                        <option value="{{ $key }}" @if (isset($item->processor) && count($item->processor))
                                            @foreach ($item->processor as $processor_data)
                                                {{ $processor_data->process_by == $key ? ' selected' : '' }}
                                            @endforeach
                                    @endif
                                    >{{ $value }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="input-group m-input-group m-input-group--solid m--margin-top-10">
                                <label class="m-checkbox m-checkbox--state-success">
                                    <input type="checkbox" onclick="edit.chooseAllProcessor(this)">
                                        @lang('Tất cả')
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>
                                {{ __('Đính kèm') }}:
                            </label>
                            @if ($item['ticket_status_id'] != 3)
                                <div class="form-group m-form__group">
                                    <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                       onclick="ticket.modalFile()">
                                        <i class="fa fa-plus-circle"></i> @lang('Upload file')
                                    </a>
                                </div>
                            @endif
                            <div class="div_file_ticket">
                                @if (isset($item->file) && count($item->file) > 0)
                                    @foreach ($item->file as $v)
                                        @if ($v['group'] == 'ticket')
                                            <div class="form-group m-form__group div_file d-flex mt-3">
                                                <input type="hidden" name="file_ticket" value="{{ $v['path'] }}">
                                                <a target="_blank" href="{{ url($v['path']) }}" class="file_ticket">
                                                    {{ ltrim($v['path'], TICKET_UPLOADS_PATH) }}
                                                </a>
                                                <a style="color:black;" href="javascript:void(0)"
                                                   onclick="ticket.removeFile(this)">
                                                    <i class="la la-trash"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if ($item['ticket_id'] != '')
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                       role="tab" aria-controls="pills-home" aria-selected="true">
                                        @lang('Danh sách vật tư')
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                       href="#pills-profile" role="tab" aria-controls="pills-profile"
                                       aria-selected="false">
                                        @lang('Biên bản nghiệm thu')
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill"
                                       href="#pills-contact" role="tab" aria-controls="pills-contact"
                                       aria-selected="false">
                                        @lang('Hình ảnh ticket')
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-rating-tab" data-toggle="pill" href="#pills-rating"
                                       role="tab" aria-controls="pills-contact" aria-selected="false">
                                        @lang('Đánh giá ticket')
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                     aria-labelledby="pills-home-tab">
                                    @if (in_array($item['ticket_status_id'], [2, 3]))
                                        {{-- ?php $checkAcceptance = 0 ?>
                                @foreach ($listAcceptance as $itemsAcceptance)
                                    @if (in_array($itemsAcceptance['status'], ['approve', 'cancel']))
                                        ?php $checkAcceptance = 1 ?>
                                    @endif
                                @endforeach
                                if($checkAcceptance == 0) --}}
                                        @if ($item['ticket_status_id'] != 3 && $item->checkAcceptanceStatus() != 'approve')
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalAdd"
                                               class="btn  btn-sm m-btn--icon color">
                                                <span>
                                                    <i class="la la-plus"></i>
                                                    <span>
                                                        {{ __('Thêm vật tư') }}
                                                    </span>
                                                </span>
                                            </a>
                                        @endif
                                        <div class="ticket-material-table d-none">
                                            <div class="col-lg-12 modal-header mb-3">
                                                <h5>@lang('Danh sách phiếu đề xuất vật tư')</h5>
                                            </div>
                                            <div class="table-responsive pt-3">
                                                <table
                                                        class="table table-striped m-table ss--header-table ss--nowrap table-list-material">
                                                    <thead>
                                                    <tr>
                                                        <th class="ss--font-size-th">#</th>
                                                        <th class="ss--font-size-th">{{ __('Mã phiếu yêu cầu') }}
                                                        </th>
                                                        <th class="ss--font-size-th">{{ __('Nội dung đề xuất') }}
                                                        </th>
                                                        <th class="ss--font-size-th">{{ __('Người đề xuất') }}</th>
                                                        <th class="ss--font-size-th">{{ __('Ngày đề xuất') }}</th>
                                                        <th class="ss--font-size-th">{{ __('Người duyệt') }}</th>
                                                        <th class="ss--font-size-th">{{ __('Thời gian duyệt') }}
                                                        </th>
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
                                                <table
                                                        class="table table-striped m-table ss--header-table ss--nowrap table-list-material-detail  text-center">
                                                    <thead>
                                                    <tr>
                                                        <th class="ss--font-size-th">#</th>
                                                        <th class="ss--font-size-th">{{ __('Mã vật tư') }}</th>
                                                        <th class="ss--font-size-th text-left">
                                                            {{ __('Tên vật tư') }}</th>
                                                        <th class="ss--font-size-th">{{ __('Số lượng tạm ứng') }}
                                                        </th>
                                                        <th class="ss--font-size-th">{{ __('Số lượng duyệt') }}</th>
                                                        <th class="ss--font-size-th">{{ __('Số lượng thực tế') }}
                                                        </th>
                                                        <th class="ss--font-size-th">{{ __('Số lượng hoàn ứng') }}
                                                        </th>
                                                        {{-- <th class="ss--font-size-th">{{ __('Trạng thái') }}</th> --}}
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
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
                                                @foreach ($listIncurred as $key => $item_include)
                                                    <tr class="">
                                                        <td class="">{{ $key + 1 }}</td>
                                                        <td class="text-center">{{ $item_include->product_code }}</td>
                                                        <td class="text-center">{{ $item_include->product_name }}</td>
                                                        <td class="text-center">{{ $item_include->quantity }}</td>
                                                        <td class="text-center">{{ $item_include->unit_name }}</td>
                                                        <td class="text-center">{{ number_format($item_include->money, 0, '', '.') }}
                                                            VND
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                     aria-labelledby="pills-profile-tab">
                                    @if (count($listAcceptance) != 0)
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
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($listAcceptance as $key => $items)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td><a class="ss--text-black"
                                                               href="{{ route('ticket.acceptance.detail', ['id' => $items['ticket_acceptance_id']]) }}">{{ $items['ticket_acceptance_code'] }}</a>
                                                        </td>
                                                        <td>{{ $items['title'] }}</td>
                                                        <td>{{ $items['ticket_code'] }}</td>
                                                        <td>{{ $items['customer_name'] }}</td>
                                                        <td>{{ $items['created_name'] }}</td>
                                                        <td>{{ $items['sign_by'] }}</td>
                                                        <td>{{ $items['sign_date'] != '' && $items['sign_date'] != null && $items['sign_date'] != '000-00-00 00:00:00' ? \Carbon\Carbon::parse($items['sign_date'])->format('d/m/Y H:i') : '' }}
                                                        </td>
                                                        <td>{{ $items['status'] == 'new' ? 'Mới' : ($items['status'] == 'approve' ? 'Đã ký' : 'Huỷ') }}
                                                        </td>
                                                        <td>
                                                            @if (!in_array($items['status'], ['approve', 'cancel']))
                                                                <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                                   href="{{ route('ticket.acceptance.edit', ['ticketid' => $items['ticket_acceptance_id']]) }}">
                                                                    <i class="la la-edit"></i>
                                                                </a>
                                                            @else
                                                                <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                                   href="{{route('ticket.acceptance.detail',['ticketid' => $items['ticket_acceptance_id']])}}">
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
                                        @if (in_array($item['ticket_status_id'], [2]))
                                            @if ($item['ticket_id'] != '')
                                                <a href="{{ route('ticket.acceptance.add', ['ticketid' => $item['ticket_id']]) }}"
                                                   target="_blank" class="btn  btn-sm m-btn--icon color">
                                                    @else
                                                        <a href="{{ route('ticket.acceptance.add') }}" target="_blank"
                                                           class="btn  btn-sm m-btn--icon color">
                                                            @endif
                                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{ __('Thêm biên bản nghiệm thu') }}
                                                </span>
                                            </span>
                                                        </a>
                                            @endif
                                        @endif
                                </div>
                                <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                     aria-labelledby="pills-contact-tab">
                                    @if($item['ticket_status_id'] != 3 && $item['ticket_status_id'] != 1)
                                        <div class="form-group m-form__group ">
                                            <div class="row">
                                                <div class="col-lg-1  w-col-mb-100">
                                                    <a href="javascript:void(0)"
                                                       onclick="document.getElementById('getFile').click()"
                                                       {{--  --}} class="btn  btn-sm m-btn--icon color">
                                                    <span>
                                                        <i class="la la-plus"></i>
                                                        <span>
                                                            {{ __('Thêm ảnh') }}
                                                        </span>
                                                    </span>
                                                    </a>
                                                </div>
                                                <div class="col-lg-9  w-col-mb-100 div_avatar">
                                                    <input type="hidden" id="ticket_img" name="image"
                                                           value="{{ $item['image'] }}">
                                                    <input type="hidden" id="img_old" name="img_old"
                                                           value="{{ $item['image'] }}">
                                                    @if (isset($item['image']) && $item['image'] != '')
                                                        <div class="wrap-img avatar float-left">
                                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                                 src="{{ url($item['image']) }}"
                                                                 alt="{{ __('Hình ảnh') }}"
                                                                 width="100px" height="100px">
                                                            <span class="delete-img" style="display: block">
                                                            <a href="javascript:void(0)" onclick="edit.remove_avatar()">
                                                                <i class="la la-close"></i>
                                                            </a>
                                                        </span>
                                                        </div>
                                                    @else
                                                        <div class="wrap-img avatar float-left">
                                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                                 src="{{ asset('static/backend/images/service-card/default/hinhanh-default3.png') }}"
                                                                 alt="{{ __('Hình ảnh') }}" width="100px"
                                                                 height="100px">
                                                            <span class="delete-img">
                                                            <a href="javascript:void(0)" onclick="edit.remove_avatar()">
                                                                <i class="la la-close"></i>
                                                            </a>
                                                        </span>
                                                        </div>
                                                    @endif
                                                    <div
                                                            class="form-group m-form__group float-left m--margin-left-20 warning_img">
                                                        <label for="">{{ __('Định dạng') }}: <b
                                                                    class="image-info image-format"></b> </label>
                                                        <br>
                                                        <label for="">{{ __('Kích thước') }}: <b
                                                                    class="image-info image-size"></b>
                                                        </label>
                                                        <br>
                                                        <label for="">{{ __('Dung lượng') }}: <b
                                                                    class="image-info image-capacity"></b>
                                                        </label><br>
                                                        <label for="">{{ __('Cảnh báo') }}: <b
                                                                    class="image-info">{{ __('Tối đa 10MB (10240KB)') }}</b>
                                                        </label><br>
                                                        <span class="error_img" style="color:red;"></span>

                                                    </div>

                                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                                           data-msg-accept="{{ __('Hình ảnh không đúng định dạng') }}"
                                                           id="getFile" type="file" onchange="uploadImage(this);"
                                                           class="form-control" style="display:none">
                                                    <div class="show_image">
                                                        @if (isset($item->images) && count($item->images) > 0)
                                                            @foreach ($item->images as $v)
                                                                @if ($v['group'] == 'image')
                                                                    <input type="hidden" name="image[]"
                                                                           value="{{ $v->path }}">
                                                                    <a target="_blank" href="{{ $v->path }}"
                                                                       class="file_image">
                                                                        <img src="{{ $v->path }}" class="file_image"
                                                                             alt="" width="100px" height="100px">
                                                                    </a>
                                                                    <a style="color:black;" href="javascript:void(0)"
                                                                       onclick="ticket.removeFile(this)">
                                                                        <i class="la la-trash"></i>
                                                                    </a>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
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
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="pills-rating" role="tabpanel"
                                     aria-labelledby="pills-rating-tab">
                                    @if (in_array($item['ticket_status_id'], [3, 4]) && !isset($item->rating->ticket_rating_id))
                                        <a href="javascript:void(0)" id="add-rating"
                                           class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{ __('Thêm') }}
                                                </span>
                                            </span>
                                        </a>
                                    @elseif(isset($item->rating->ticket_rating_id))
                                        <div class="d-flex">
                                            <div class="col-md-6">
                                                <div class="form-group m-form__group">
                                                    <label for="">@lang('Chấm điểm')</label>
                                                    <div class="form-group form-control p-0 bg-disabled">
                                                        <div class="rate">
                                                            <input type="radio" id="star5" name="rate" value="5"
                                                                   disabled
                                                                    {{ isset($item->rating->point) && $item->rating->point == 5 ? 'checked' : '' }} />
                                                            <label for="star5" title="text">5 stars</label>
                                                            <input type="radio" id="star4" name="rate" value="4"
                                                                   disabled
                                                                    {{ isset($item->rating->point) && $item->rating->point == 4 ? 'checked' : '' }} />
                                                            <label for="star4" title="text">4 stars</label>
                                                            <input type="radio" id="star3" name="rate" value="3"
                                                                   disabled
                                                                    {{ isset($item->rating->point) && $item->rating->point == 3 ? 'checked' : '' }} />
                                                            <label for="star3" title="text">3 stars</label>
                                                            <input type="radio" id="star2" name="rate" value="2"
                                                                   disabled
                                                                    {{ isset($item->rating->point) && $item->rating->point == 2 ? 'checked' : '' }} />
                                                            <label for="star2" title="text">2 stars</label>
                                                            <input type="radio" id="star1" name="rate" value="1"
                                                                   disabled
                                                                    {{ isset($item->rating->point) && $item->rating->point == 1 ? 'checked' : '' }} />
                                                            <label for="star1" title="text">1 star</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black_title w-100">
                                                        @lang('Nội dung'):
                                                    </label>
                                                    <textarea class="form-control m-input" name="description" rows="6"
                                                              cols="5" placeholder="@lang('Nhập nội dung đánh giá')..."
                                                              disabled>{{ isset($item->rating->description) ? $item->rating->description : '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title w-100">
                                                        @lang('Người tạo'):
                                                    </label>
                                                    <input type="text" class="form-control"
                                                           value="{{ isset($item->rating->full_name_rating) ? $item->rating->full_name_rating : '' }}"
                                                           disabled>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black_title w-100">
                                                        @lang('Ngày tạo'):
                                                    </label>
                                                    <input type="text" class="form-control"
                                                           value="{{ \Carbon\Carbon::parse($item->rating->created_at)->format('d/m/Y H:i') }}"
                                                           disabled>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <a href="{{ route('ticket') }}"
                               class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>@lang('HỦY')</span>
                                </span>
                            </a>
                            <button type="button" onclick="edit.save({{ isset($id)?$id:'' }})"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>@lang('LƯU THÔNG TIN')</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <div id="my-modal"></div>
    @include('ticket::ticket.popup.modal-file')
    @include('ticket::ticket.popup.modal-image')
    @include('ticket::ticket.popup.rating')
    @include('ticket::ticket.popup.add-material')
    @include('ticket::ticket.popup.view-material')
    @include('ticket::ticket.popup.edit-material')
    @include('ticket::ticket.popup.replace_material')

    <input type="hidden" id="contract_id" name="contract_id" value="{{ $contractId }}">
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
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
@section('after_script')
    @include('ticket::language.lang')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script> --}}
    {{-- <script src="{{ asset('static/backend/js/ticket/ticket/dropzone.js?v=' . time()) }}" type="text/javascript"></script> --}}
    <script src="{{ asset('static/backend/js/ticket/ticket/script.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script>
        @if (isset($contractPartnerId) && $contractPartnerId != '')
        $('#customer_id').val({{ $contractPartnerId }});
        $('#customer_id').trigger('change');
        @endif
        @if (isset($item['ticket_status_id']) && $item['ticket_status_id'] == 3)
        $('#form-edit select').not('#ticket_status_id').prop('disabled', true);
        @endif
        edit._init();
    </script>
    <script type="text/template" id="tpl-file">
        <div class="form-group m-form__group div_file d-flex">
            <input type="hidden" name="file_ticket" value="{fileName}">
            <a target="_blank" href="{fileName}" class="file_ticket">
                {fileName}
            </a>
            <a style="color:black;"
               href="javascript:void(0)" onclick="ticket.removeFile(this)">
                <i class="la la-trash"></i>
            </a>
        </div>
    </script>
    <script type="text/template" id="tpl-image">
        <div class="form-group m-form__group div_file d-flex">
            <input type="hidden" name="image[]" value="{link_image}">
            <a target="_blank" href="{link_image}" class="file_image">
                <img src="{link_image}" class="file_image" alt="" width="100px" height="100px">
            </a>
            <a style="color:black;"
               href="javascript:void(0)" onclick="ticket.removeFile(this)">
                <i class="la la-trash"></i>
            </a>
        </div>
    </script>
    <script>
        ticket.dropzoneFile();
    </script>
    <script>
        // load ajax field
        $(document).ready(function () {
            let ticket_type_id = $('#form-edit #ticket_type').find('option:selected').val();
            if (!ticket_type_id) {
                return;
            } else {
                $.ajax({
                    url: laroute.route('ticket.get-request-by-issue-group-id'),
                    data: {
                        ticket_type_id: ticket_type_id,
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (response) {
                        if (response.status == 1) {
                            $('#ticket_issue_id').html(response.html);
                            ticket_issue_id();
                        }
                    }
                });
            }
        });

        function load_queue() {
            let ticket_id = $('#form-edit [name="ticket_id"]').val();
            if (!ticket_id) {
                return;
            }
            $.ajax({
                url: laroute.route('ticket.get-request-by-issue-group-id'),
                data: {
                    ticket_id: ticket_id,
                },
                method: "POST",
                dataType: "JSON",
                success: function (response) {
                    $('#ticket_issue_id').val(response.data.ticket_issue_id).trigger('change');
                    if (response.status == 1) {
                        if (response.data.operate_by) {
                            $('#operate_by').html(response.data.operate_by);
                        }
                        if (response.data.operate_by) {
                            $('#processor').html(response.data.processor);
                            $('#processor').prop('disabled', false);
                        }
                    }
                    if (response.ticket_status == 3) {
                        $('#processor').prop('disabled', true);
                        $('#ticket_issue_id').prop('disabled', true);
                        $('#title').prop('disabled', true);
                        $('#date_issue').prop('disabled', true);
                        $('#date_request').prop('disabled', true);
                        $('#description').prop('disabled', true);
                    }
                }
            });
        }

        function ticket_issue_id() {
            let ticket_type_id = $('#ticket_type').find('option:selected').val();
            if (!ticket_type_id) {
                $('#ticket_issue_id').prop('disabled', true);
                $('#issule_level').prop('disabled', true);
            } else {
                $.ajax({
                    url: laroute.route('ticket.get-request-by-issue-group-id'),
                    data: {
                        ticket_type_id: ticket_type_id,
                    },
                    method: "POST",
                    dataType: "JSON",
                    success: function (response) {
                        if (response.status == 1) {
                            $('#ticket_issue_id').prop('disabled', false);
                            $('#ticket_issue_id').html(response.html);
                            load_queue();
                        }
                    }
                });
            }
            let ticket_issue_id = $('#form-edit #ticket_issue_id').find('option:selected').val();
            if (!ticket_issue_id) {
                return;
            }
            $.ajax({
                url: laroute.route('ticket.get-request-by-issue-group-id'),
                data: {
                    ticket_issue_id: ticket_issue_id,
                },
                method: "POST",
                dataType: "JSON",
                success: function (response) {
                    if (response.status == 1) {
                        $('#issule_level').val(response.level).change();
                    }
                }
            });
        }
    </script>
@stop
