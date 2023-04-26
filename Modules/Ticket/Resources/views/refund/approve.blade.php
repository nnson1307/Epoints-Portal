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
                        @lang('XÉT DUYỆT PHIẾU HOÀN ỨNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
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
                <div class="col-12">
                    <!--begin::Portlet-->
                    <h4 class="fz-1_5rem mb-4">{{ __('THÔNG TIN CHUNG') }}</h4>
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
                <div class="modal-footer col-12">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <a href="{{ route('ticket.refund') }}"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--md">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>@lang('HỦY')</span>
                                </span>
                            </a>
                            <button type="button" onclick="Refund.reupload({{$item->ticket_refund_id}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>@lang('CHỜ HỒ SƠ')</span>
                                </span>
                            </button>
                            <button type="button" onclick="Refund.approve({{$item->ticket_refund_id}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>@lang('DUYỆT')</span>
                                </span>
                            </button>
                            <button type="button" onclick="Refund.approve_success({{$item->ticket_refund_id}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>@lang('DUYỆT & HOÀN TẤT')</span>
                                </span>
                            </button>
                            <button type="button" onclick="Refund.cancle({{$item->ticket_refund_id}})"
                                class="btn btn-danger son-mb  m-btn m-btn--icon m-btn--md m--margin-left-10">
                                <span>
                                    <i class="la la-close"></i>
                                    <span>@lang('TỪ CHỐI')</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Portlet-->
        </div>
    </div>
    <form class="modal fade" id="approve-popup-item" role="dialog" action="" method="GET"></form>
    {{-- @include('ticket::refund.popup.approve_popup') --}}
@endsection
@section('after_script')
    @include('ticket::language.lang')
    <script src="{{ asset('static/backend/js/ticket/refund/add-refund.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
    countValue();
    var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
@stop
