@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN HÀNG CẦN GIAO')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="la la-edit"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA ĐƠN HÀNG CẦN GIAO')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mã đơn hàng'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$item['order_code']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Người đặt'):
                            </label>
                            <input type="text" class="form-control m-input" value="{{$item['full_name']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_name" name="contact_name" value="{{$item['contact_name']}}"
                                   placeholder="@lang('Nhập người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số điện thoại người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_phone" name="contact_phone" value="{{$item['contact_phone']}}"
                                   placeholder="@lang('Nhập số điện thoại người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_address" name="contact_address" value="{{$item['contact_address']}}"
                                   placeholder="@lang('Nhập địa chỉ người nhận')...">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số lần giao dự kiến'):<b class="text-danger">*</b>
                            </label>
                            <input type="number" class="form-control m-input"
                                   id="total_transport_estimate" name="total_transport_estimate"
                                   value="{{$item['total_transport_estimate']}}"
                                   placeholder="@lang('Nhập số lần giao dự kiến')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'):<b class="text-danger">*</b>
                            </label>
                            <select class="form-control" style="width: 100%" id="delivery_status"
                                    name="delivery_status" disabled>
                                <option></option>
                                @if($item['delivery_status'] != 'delivered' && $item['delivery_status'] != 'cancel')
                                    <option value="packing" {{$item['delivery_status'] == 'packing' ? 'selected' : ''}}>
                                        @lang('Đóng gói')
                                    </option>
                                    <option value="preparing" {{$item['delivery_status'] == 'preparing' ? 'selected' : ''}}>
                                        @lang('Chuẩn bị')
                                    </option>
                                    <option value="delivering" {{$item['delivery_status'] == 'delivering' ? 'selected' : ''}}>
                                        @lang('Đang giao')
                                    </option>
                                @endif
                                @if($item['delivery_status'] != 'cancel')
                                    <option value="delivered" {{$item['delivery_status'] == 'delivered' ? 'selected' : ''}}>
                                        @lang('Đã giao')
                                    </option>
                                @endif
                                <option value="cancel" {{$item['delivery_status'] == 'cancel' ? 'selected' : ''}}>
                                    @lang('Hủy')
                                </option>
                            </select>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                @lang('Xác nhận'):
                            </label>
                            <div class="row">
                                <div class="col-lg-2">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_actived" name="is_actived" type="checkbox"
                                                    {{$item['is_actived'] == 1 ? 'checked':''}} {{$item['is_actived'] == 1 ? 'disabled':''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="col-lg-10 m--margin-top-5">
                                    <i>@lang('Chọn để xác nhận')</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('delivery')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="edit.save({{$item['delivery_id']}})"
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
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
        var decimalQuantity = parseInt('{{$decimalQuantity}}');
    </script>
    <script src="{{asset('static/backend/js/delivery/delivery/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        edit._init();
    </script>
@stop


