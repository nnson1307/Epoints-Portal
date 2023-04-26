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
                        @lang('CHỈNH SỬA HÌNH THỨC THANH TOÁN')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <form id="formEdit">
                <div class="row">
                    <div class="col-lg-6">
                        <input type="text" class="form-control m-input"
                               name="payment_method_id" value="{{$item["payment_method_id"]}}" hidden>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mã hình thức thanh toán'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input"
                                       name="payment_method_code" value="{{$item["payment_method_code"]}}" disabled>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên hình thức thanh toán (Tiếng Việt)'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input"
                                       name="payment_method_name_vi" value="{{$item["payment_method_name_vi"]}}" {{$item['is_system'] == 1 ? 'disabled': ''}}>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên hình thức thanh toán (Tiếng Anh)'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input"
                                       name="payment_method_name_en" value="{{$item["payment_method_name_en"]}}" {{$item['is_system'] == 1 ? 'disabled': ''}}>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Loại hình thức thanh toán'):
                            </label>
                            <div class="input-group">
                                <select class="form-control m-input select2" name="payment_method_type">
                                    <option value="auto" {{$item["payment_method_type"] == "auto" ? "selected" : ""}}>{{__('Tự động')}}</option>
                                    <option value="manual" {{$item["payment_method_type"] == "manual" ? "selected" : ""}}>{{__('Thủ công')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ghi chú'):
                            </label>
                            <div class="input-group">
                                <textarea class="form-control" rows="3" id="note" name="note">{{$item['note']}}</textarea>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'):
                            </label>
                            <div class="input-group">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        @if($item["is_active"] == 1)
                                            <input type="checkbox" checked="" class="manager-btn" name="is_active"
                                                   id="is_active" {{$item['is_system'] == 1 ? 'disabled': ''}}>
                                        @else
                                            <input type="checkbox" class="manager-btn" name="is_active" id="is_active" {{$item['is_system'] == 1 ? 'disabled': ''}}>
                                        @endif
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        @if($item['payment_method_code'] == 'VNPAY')
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    <p><b>Hướng dẫn cấu hình:</b></p>
                                    <p><b>Để sử sụng VNPAY bạn cần:</b></p>
                                    <p>
                                        <a href="https://doitac.vnpay.vn/register-qr" target="_blank"
                                           class="m-link">
                                        <span>
                                            <span>1. @lang('Đăng ký dịch vụ với VNPAY')</span>
                                        </span>
                                        </a>
                                        <br>
                                        2. Sau khi hoàn tất các bước đăng ký tại VNPay, bạn sẽ có thông số Terminal ID, Secret Key<br>
                                        3. Nhập các thông số vào ô tương ứng bên dưới</p>
                                </label>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Url khởi tạo giao dịch'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           name="url" value="{{$item["url"]}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Terminal ID'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           name="terminal_id" value="{{$item["terminal_id"]}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Secret Key'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           name="secret_key" value="{{$item["secret_key"]}}"></div>
                            </div>

                        @elseif($item['payment_method_code'] == 'MOMO')
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    <p><b>Hướng dẫn cấu hình:</b></p>
                                    <p><b>Để sử sụng MOMO bạn cần:</b></p>
                                    <p>
                                        <a href="https://business.momo.vn/signup" target="_blank"
                                           class="m-link">
                                        <span>
                                            <span>1. @lang('Đăng ký dịch vụ với MOMO')</span>
                                        </span>
                                        </a>
                                        <br>
                                        2. Sau khi hoàn tất các bước đăng ký tại Momo, bạn sẽ có thông số Partner Code, Access Key, Secret Key<br>
                                        3. Nhập các thông số vào ô tương ứng bên dưới</p>
                                </label>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Partner Code'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           name="terminal_id" value="{{$item["terminal_id"]}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Access Key'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           name="access_key" value="{{$item["access_key"]}}">
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Secret Key'): <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input"
                                           name="secret_key" value="{{$item["secret_key"]}}"></div>
                            </div>
                        @endif
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
                    <button type="button" onclick="paymentMethod.save()"
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
    <script src="{{asset('static/backend/js/admin/service/dropzone.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/payment/payment-method/script.js')}}"
            type="text/javascript"></script>
@endsection
