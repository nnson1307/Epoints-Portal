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
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang('TẠO PHIẾU HOÀN ỨNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
        </div>
    </div>
    <form class="row" id="form-refund">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                @if ($ticketRefundList)
                    <div class="m-portlet__body">
                        <h4>{{ _('Chọn ticket cần hoàn ứng') }}:</h4>
                        <div class="table-responsive">
                            <table
                                class="table table-striped m-table s--header-table ss--nowrap text-center ticket-refund-list">
                                <thead>
                                    <tr>
                                        <th class="ss--font-size-th">
                                            <label class="m-checkbox m-checkbox--air">
                                                <input class="check-page" type="checkbox" id="checkAll">
                                                <span></span>
                                            </label>
                                        </th>
                                        <th class="ss--font-size-th">{{ __('Mã ticket') }}</th>
                                        <th class="ss--font-size-th">{{ __('Thời gian tạo') }}</th>
                                        <th class="ss--font-size-th">{{ __('Người tạo') }}</th>
                                        <th class="ss--font-size-th">{{ __('Số lượng vật tư hoàn ứng') }}</th>
                                        <th class="ss--font-size-th">{{ __('Số tiền hoàn ứng') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ticketRefundList as $ticket)
                                        <tr>
                                            <td>
                                                <label class="m-checkbox m-checkbox--air">
                                                    <input class="check-page" type="checkbox"
                                                        name="ticket_id[{{ $ticket->ticket_id }}]"
                                                        value="{{ $ticket->ticket_id }}" {{ isset($ticketRefundMapList[$ticket->ticket_id])?' checked' :'' }}>
                                                    <span></span>
                                                </label>
                                            </td>
                                            <td><a href="{{ route('ticket.detail',$ticket->ticket_id) }}">{{ $ticket->ticket_code }}</a></td>
                                            <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>{{ $ticket->created_by_full_name }}</td>
                                            <td>{{ $ticket->sum_quantity_return }}</td>
                                            <td>{{ number_format($ticket->sum_price, 0, '', '.') }} VND</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h5 class="modal-title color-primary m--font-bold font-primary fw-500">
                            {{ __('Tổng ticket đã chọn') }}: <span
                                class="count-ticket-choose">0</span>/{{ count($ticketRefundList) }}
                        </h5>
                    </div>
                @endif
            </div>
            <!--end::Portlet-->
            <!--begin::Portlet-->
            <h4 class="fz-1_5rem mb-4"><i class="fa fa-th-large mr-3 fz-1_5rem"
                    aria-hidden="true"></i>{{ __('Danh sách hoàn ứng') }}:</h4>
            <div id="ticket_refund_list">{!! $html !!}</div>
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
        <div class="modal-footer col-12">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{ route('ticket.refund') }}"
                        class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>@lang('HỦY')</span>
                        </span>
                    </a>
                    <button type="button" onclick="Refund.save({{$item->ticket_refund_id}})"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-check"></i>
                            <span>@lang('LƯU NHÁP')</span>
                        </span>
                    </button>
                    <button type="button" onclick="Refund.create({{$item->ticket_refund_id}})"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-check"></i>
                            <span>@lang('TẠO PHIẾU')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
    @include('ticket::refund.popup.modal-file')
@endsection
@section('after_script')
    @include('ticket::language.lang')
    <script type="text/template" id="tpl-file">
        <div class="form-group m-form__group div_file d-flex">
            <input type="hidden" name="{ticket_id}[]" value="{fileName}">
            <a target="_blank" href="{fileName}" class="file_name">
                {fileNameCustom}
            </a>
            <a style="color:black;"
                href="javascript:void(0)" onclick="Refund.removeFile(this)">
                <i class="la la-trash"></i>
            </a>
        </div>
    </script>
    <script src="{{ asset('static/backend/js/ticket/refund/add-refund.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script>
        Refund.dropzoneFile();
        countValue();
    </script>
@stop