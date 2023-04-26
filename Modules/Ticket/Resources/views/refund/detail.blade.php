@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ HOÀN ỨNG VẬT TƯ')</span>
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
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-eye"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHI TIẾT PHIẾU HOÀN ỨNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('ticket.refund') }}"
                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                    <span>
                        <i class="la la-arrow-left"></i>
                        <span>@lang('HỦY')</span>
                    </span>
                </a>
                @if ($item->approve_id == \Auth::id() && in_array($item->status, ['W', 'A']))
                    @if ($item->status == 'W')
                        <a href="{{ route('ticket.refund.approve-view', $item->ticket_refund_id) }}"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('DUYỆT')</span>
                            </span>
                        </a>
                        <button type="button" onclick="Refund.cancle({{ $item->ticket_refund_id }})"
                            class="btn btn-danger son-mb  m-btn m-btn--icon m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-close"></i>
                                <span>@lang('TỪ CHỐI')</span>
                            </span>
                        </button>
                    @endif
                    @if ($item->status == 'A')
                        <button type="button" onclick="Refund.approve_success({{ $item->ticket_refund_id }})"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('HOÀN TẤT')</span>
                            </span>
                        </button>
                    @endif
                @endif
                @if ($item->created_by == \Auth::id() && in_array($item->status, ['D', 'WF']))
                    <a href="{{ route('ticket.refund.add-view', $item->ticket_refund_id) }}"
                        class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-edit"></i>
                            <span>@lang('CHỈNH SỬA')</span>
                        </span>
                    </a>
                    <button type="button" onclick="Refund.create({{ $item->ticket_refund_id }})"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-check"></i>
                            <span>@lang('GỬI DUYỆT')</span>
                        </span>
                    </button>
                    @if ($item->status == 'D')
                        <a href="{{ route('ticket.refund.remove', $item->ticket_refund_id) }}"
                            class="float-right btn btn-danger color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-edit"></i>
                                <span>@lang('XÓA')</span>
                            </span>
                        </a>
                    @endif

                @endif

            </div>
        </div>
        <div class="m-portlet__body">
            @include('ticket::refund.content.header_status')
        </div>
    </div>
    <div class="row">
        @include('ticket::refund.content.menu_left')
        <div class="col-lg-9">
            <form class="row" id="form-refund">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#profile" role="tab" data-toggle="tab">
                            {{ __('Thông tin chung') }}
                        </a>
                    </li>
                    @if($receive_data)
                    <li class="nav-item">
                        <a class="nav-link" href="#buzz" role="tab"
                            data-toggle="tab">{{ __('Phiếu nhập') }}</a>
                    </li>
                    @endif
                    @if($payment_data)
                    <li class="nav-item">
                        <a class="nav-link" href="#references" role="tab"
                            data-toggle="tab">{{ __('Phiếu chi') }}</a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content col-12">
                    <div role="tabpanel" class="tab-pane fade show active" id="profile">
                        <div class="col-12">
                            <!--begin::Portlet-->
                            {{-- <h4 class="fz-1_5rem mb-4">{{ __('THÔNG TIN CHUNG') }}</h4> --}}
                            <div id="ticket_refund_list">
                                {!! $html !!}
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="modal-title color-primary m--font-bold font-primary fw-500">
                                        {{ __('Tổng số lượng vật tư hoàn ứng') }}: <span class="total_quantity_all">0</span>
                                    </h5>
                                </div>
                                <div>
                                    <h5 class="modal-title color-primary m--font-bold font-primary fw-500">
                                        {{ __('Tổng tiền') }}: <span class="total_money_all">0</span>
                                    </h5>
                                </div>
        
                            </div>
                            <!--end::Portlet-->
                        </div>
                    </div>
                    @if ($receive_data)
                    <div role="tabpanel" class="tab-pane fade" id="buzz">
                        <div class="table-responsive">
                            <table class="table table-striped m-table s--header-table ss--nowrap text-center">
                                <thead class="bg">
                                    <tr>
                                        <th class="ss--font-size-th">{{ __('Mã phiếu nhập kho') }}</th>
                                        <th class="ss--font-size-th">{{ __('Số lượng nhập kho') }}</th>
                                        <th class="ss--font-size-th">{{ __('Thời gian tạo') }}</th>
                                        <th class="ss--font-size-th">{{ __('Người tạo') }}</th>
                                        <th class="ss--font-size-th">{{ __('Trạng thái') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="white-space: initial;">{{ $receive_data->pi_code }}</td>
                                        <td>{{ $receive_data->sum_quantity }}</td>
                                        <td>{{ \Carbon\Carbon::parse($receive_data->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $receive_data->created_by_full_name }}</td>
                                        <td>
                                            @if($receive_data->status=='new')
                                                <span>{{__('Mới')}}</span>
                                            @elseif($receive_data->status=='success')
                                                <span>{{__('Hoàn thành')}}</span>
                                            @elseif($receive_data->status=='draft')
                                                <span>{{__('Lưu nháp')}}</span>
                                            @elseif($receive_data->status=='cancel')
                                                <span>{{__('Hủy')}}</span>
                                            @elseif($receive_data->status=='inprogress')
                                                <span>{{__('Đang xử lý')}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    @if ($payment_data)
                    <div role="tabpanel" class="tab-pane fade" id="references">
                        <div class="table-responsive">
                            <table class="table table-striped m-table s--header-table ss--nowrap text-center">
                                <thead class="bg">
                                    <tr>
                                        <th class="ss--font-size-th">{{ __('Mã phiếu chi') }}</th>
                                        <th class="ss--font-size-th">{{ __('Loại người nhận') }}</th>
                                        <th class="ss--font-size-th">{{ __('Tổng tiền') }}</th>
                                        <th class="ss--font-size-th">{{ __('Chi nhánh') }}</th>
                                        <th class="ss--font-size-th">{{ __('Thời gian tạo') }}</th>
                                        <th class="ss--font-size-th">{{ __('Người tạo') }}</th>
                                        <th class="ss--font-size-th">{{ __('Trạng thái') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="white-space: initial;">{{ $payment_data->payment_code }}</td>
                                        <td>{{ $payment_data->object_accounting_type_name_vi }}</td>
                                        <td>{{ number_format($payment_data->total_amount, 0, '', '.') }} VND</td>
                                        <td>{{ $payment_data->branch_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment_data->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ isset($payment_data->staff_created->full_name)?$payment_data->staff_created->full_name:'' }}</td>
                                        <td>
                                            @switch($payment_data->status)
                                            @case('new') {{__('Mới')}} @break;
                                            @case('approved') {{__('Đã xác nhận')}} @break;
                                            @case('paid') {{__('Đã chi')}} @break;
                                            @case('unpaid') {{__('Đã huỷ chi')}} @break;
                                            @endswitch
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
                
            </form>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
@section('after_script')
    @include('ticket::language.lang')
    <script src="{{ asset('static/backend/js/ticket/refund/add-refund.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script>
        countValue();
    </script>
@endsection
