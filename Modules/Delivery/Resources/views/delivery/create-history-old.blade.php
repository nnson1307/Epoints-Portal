@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN HÀNG CẦN GIAO')</span>
@stop
@section('content')
    <style>
        .color-red {
            color:red
        }
    </style>
    <form id="form-register">
        <div class="m-portlet m-portlet--head-sm">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                         <span class="m-portlet__head-icon">
                             <i class="fa fa-plus-circle"></i>
                         </span>
                        <h2 class="m-portlet__head-text">
                            @lang('TẠO PHIẾU GIAO HÀNG')
                        </h2>
                    </div>
                </div>
                <div class="m-portlet__head-tools">

                </div>
            </div>

            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <input type="hidden" id="order_id" value="{{$item['order_id']}}">
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
                                   id="contact_name" name="contact_name"
                                   value="{{$item['customer_contact_code'] != null ? $item['contact_name'] : $item['customer_name']}}"
                                   placeholder="@lang('Nhập người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số điện thoại người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_phone" name="contact_phone"
                                   value="{{$item['customer_contact_code'] != null ? $item['contact_phone'] : $item['customer_phone']}}"
                                   placeholder="@lang('Nhập số điện thoại người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Địa chỉ người nhận'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="contact_address" name="contact_address"
                                   value="{{$item['customer_contact_code'] != null ? $item['contact_address'] : $item['customer_address']}}"
                                   placeholder="@lang('Nhập địa chỉ người nhận')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian giao hàng dự kiến'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly="" placeholder="@lang('Thời gian giao hàng dự kiến')" id="time_ship" name="time_ship">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số tiền cần thu'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="amount" name="amount" placeholder="@lang('Nhập số tiền cần thu')..."
                                   value="{{$item['amount']}}" disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Hình thức giao'):
                            </label>
                            <select class="form-control" style="width: 100%" id="transport_id" name="transport_id">
                                <option></option>
                                @foreach($optionTransport as $v)
                                    <option value="{{$v['transport_id']}}">{{$v['transport_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Mã đơn vị vận chuyển'):
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="transport_code" name="transport_code" placeholder="@lang('Nhập mã đơn vị vận chuyển')...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên'):
{{--                                <b class="text-danger">*</b>--}}
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="delivery_staff" name="delivery_staff">
                                    <option></option>
                                    @foreach($optionCarrier as $v)
                                        <option value="{{$v['user_carrier_id']}}">{{$v['full_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nơi lấy hàng'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control select2-fix" style="width: 100%" id="pick_up" name="pick_up">
                                    @if(isset($optionPickupAddress) && count($optionPickupAddress) > 0)
                                        <option value=""></option>
                                        @foreach($optionPickupAddress as $v)
                                            <option value="{{$v['warehouse_id']}}" {{$item['branch_id'] == $v['branch_id'] ? 'selected' : ''}}>
                                                {{$v['name'] . ' - ' . $v['address']}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ghi chú'):
                            </label>
                            <textarea class="form-control m-input" id="note" name="note" rows="5" cols="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default" id="table_product">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">@lang('Mã sản phẩm')</th>
                                <th class="tr_thead_list">@lang('Tên sản phẩm')</th>
                                <th class="tr_thead_list">@lang('Số lượng còn lại')</th>
                                <th class="tr_thead_list">@lang('SKU')</th>
                                <th class="tr_thead_list">@lang('Số lượng giao')</th>
                                <th class="tr_thead_list">@lang('Ghi chú')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dataProduct as $v)
                                <tr class="tr_product tr_{{$v['object_id']}}">
                                    <td>
                                        <span>{{$v['object_code']}}</span>
                                        <input type="hidden" class="product_id" value="{{$v['object_id']}}">
                                    </td>
                                    <td>
                                        <span>{{$v['object_name']}}</span>
                                    </td>
                                    <td>
                                        <span>{{$v['quantity']}}</span>
                                        <input type="hidden" class="quantity_old" value="{{$v['quantity']}}">
                                    </td>
                                    <td>
                                        <input class="form-control sku sku_{{$v['object_id']}}" onchange="createHistory.changeSKU('{{$v['object_id']}}')">
                                        <span class="error_sku_{{$v['object_id']}} color_red"></span>
                                    </td>
                                    <td>
                                        <input class="form-control quantity" value="{{$v['quantity']}}" onchange="createHistory.changeQuantity('{{$item['delivery_id']}}')">
                                        <span class="error_quantity_{{$v['object_id']}} color_red"></span>
                                    </td>
                                    <td>
                                        <input class="form-control note">
                                        <input type="hidden" class="form-control object_code" value="{{$v['object_code']}}">
                                        <input type="hidden" class="form-control object_name" value="{{$v['object_name']}}">
                                        <input type="hidden" class="form-control price" value="{{$v['price']}}">
                                        <input type="hidden" class="form-control object_type" value="{{$v['object_type']}}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span class="error-table" style="color: #ff0000"></span>
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
                        <button type="button" onclick="createHistory.save({{$item['delivery_id']}})"
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
    </form>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/delivery/delivery/script.js')}}" type="text/javascript"></script>
    <script type="text/template" id="product-tpl">
        <tr class="tr_product tr_{product_id}">
            <td>
                <span>{product_code}</span>
                <input type="hidden" class="product_id" value="{product_id}">
            </td>
            <td>
                <span>{product_name}</span>
            </td>
            <td>
                <span>{quantity_old}</span>
                <input type="hidden" class="quantity_old" value="{quantity_old}">
            </td>
            <td>
                <input class="form-control quantity" value="{quantity_old}" onchange="createHistory.changeQuantity()">
                <span class="error_quantity_{product_id} color_red"></span>
            </td>
            <td>
                <input class="form-control note">
            </td>
        </tr>
    </script>
    <script>
        createHistory._init();
    </script>
    <script>
        $(document).ready(function(){
            $('.select2-fix').select2();
        })
    </script>
@stop


