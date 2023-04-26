@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ HÌNH THỨC THANH TOÁN')</span>
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
                        @lang('THÊM HÌNH THỨC THANH TOÁN')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <form id="formCreate">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Mã hình thức thanh toán'): <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control m-input"
                               name="payment_method_code">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tên hình thức thanh toán (Tiếng Việt)'): <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control m-input"
                               name="payment_method_name_vi">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tên hình thức thanh toán (Tiếng Anh)'): <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control m-input"
                               name="payment_method_name_en">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Loại hình thức thanh toán'):
                    </label>
                    <div class="input-group">
                        <select class="form-control m-input select2" name="payment_method_type">
                            <option value="auto">{{__('Tự động')}}</option>
                            <option value="manual">{{__('Thủ công')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Ghi chú'):
                    </label>
                    <div class="input-group">
                        <textarea class="form-control" rows="3" id="note" name="note"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('payment-method')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="paymentMethod.add()"
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
    <script src="{{asset('static/backend/js/payment/payment-method/script.js?v='.time())}}" type="text/javascript"></script>
@endsection
