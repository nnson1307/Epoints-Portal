@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN VỊ THANH TOÁN')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA ĐƠN VỊ THANH TOÁN')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <form id="formEdit">
                <input type="text" class="form-control m-input"
                       name="payment_unit_id" value="{{$item["payment_unit_id"]}}" hidden>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tên đơn vị thanh toán'): <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control m-input"
                               name="name" value="{{$item["name"]}}">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Trạng thái'):
                    </label>
                    <div class="input-group">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                @if($item["is_actived"] == 1)
                                    <input type="checkbox" checked="" class="manager-btn" name="is_actived"
                                           id="is_actived">
                                @else
                                    <input type="checkbox" class="manager-btn" name="is_actived" id="is_actived">
                                @endif
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('payment-unit')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="paymentUnit.save()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/payment/payment-unit/script.js?v='.time())}}"
            type="text/javascript"></script>
@endsection
