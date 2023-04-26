@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN HÀNG CẦN GIAO')</span>
@stop
@section('content')
    <style>
        .color-red {
            color:red
        }
        .tr_product .form-control , .length_input , .width_input , .height_input{
            padding:0;
            text-align:center;
        }
        span.select2{
            width: 100% !important;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">

                     </span>
                    <h2 class="m-portlet__head-text">
                        <img class="img-fluid" style="width: 30px;" src="{{asset('static/backend/images/doc.png')}}" >@lang('TẠO PHIẾU GIAO HÀNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
    </div>
    <form id="form-register">
        <div class="m-portlet__body">
            <div class="col-12">
                <div class="row">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-12 m-portlet m-portlet--head-sm">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/doc_icon.png')}}" >
                                            <h2 class="m-portlet__head-text">
                                                @lang('Thông tin đơn hàng')
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-between pt-3">
                                            <span>@lang('Mã đơn hàng'):</span>
                                            <span><strong>{{$item['order_code']}}</strong></span>
                                        </div>
                                        <div class="col-12 d-flex justify-content-between pt-3">
                                            <span>@lang('Người đặt'):</span>
                                            <span><strong>{{$item['full_name']}}</strong></span>
                                        </div>
                                        <div class="col-12 d-flex justify-content-between pt-3">
                                            <span>@lang('Tổng giá trị đơn hàng'):</span>
                                            <span><strong>{{number_format($item['amount'])}} VNĐ</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 m-portlet m-portlet--head-sm">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/doc_icon.png')}}" >
                                            <h2 class="m-portlet__head-text">
                                                @lang('Thông tin phiếu giao hàng')
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-12 form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Người nhận'):
                                            </label>
                                            <input type="text" class="form-control m-input"
                                                   id="contact_name" name="contact_name"
                                                   value="{{$deliveryCustomerAddress != null ? $deliveryCustomerAddress['customer_name'] : ( $item['customer_contact_code'] != null ? $item['contact_name'] : $item['customer_name'])}}"
                                                   placeholder="@lang('Nhập người nhận')...">
                                        </div>

                                        <div class=" col-12 form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Số điện thoại người nhận'):<b class="text-danger">*</b>
                                            </label>
                                            <input type="text" class="form-control m-input"
                                                   id="contact_phone" name="contact_phone"
                                                   value="{{$deliveryCustomerAddress != null ? $deliveryCustomerAddress['customer_phone'] : ( $item['customer_contact_code'] != null ? $item['contact_phone'] : $item['customer_phone'])}}"
                                                   placeholder="@lang('Nhập số điện thoại người nhận')...">
                                        </div>
                                        <div class="col-6 form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Tỉnh/thành phố'):<b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control select2-fix province_id" name="province_id" id="province_id" onchange="delivery.changeProvince()">
                                                <option value="">{{__('Chọn Tỉnh/Thành phố')}}</option>
                                                @foreach($province as $itemProvince)
                                                    <option value="{{$itemProvince['provinceid']}}" {{$deliveryCustomerAddress != null && $deliveryCustomerAddress['province_id'] == (int)$itemProvince['provinceid'] ? 'selected': ''}}>{{$itemProvince['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Quận/huyện'):<b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control select2-fix district_id" name="district_id" id="district_id" onchange="delivery.changeDistrict()">
                                                <option value="">{{__('Chọn Quận/Huyện')}}</option>
                                                @foreach($district as $itemDistrict)
                                                    <option value="{{$itemDistrict['districtid']}}" {{$deliveryCustomerAddress != null && $deliveryCustomerAddress['district_id'] == (int)$itemDistrict['districtid'] ? 'selected': ''}}>{{$itemDistrict['type'].' - '.$itemDistrict['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Phường/xã'):<b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control select2-fix ward_id" name="ward_id" >
                                                <option value="">{{__('Chọn Phường/Xã')}}</option>
                                                @foreach($ward as $itemWard)
                                                    <option value="{{$itemWard['ward_id']}}" {{$deliveryCustomerAddress != null && $deliveryCustomerAddress['ward_id'] == $itemWard['ward_id'] ? 'selected': ''}}>{{$itemWard['type'].' - '.$itemWard['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Địa chỉ người nhận'):<b class="text-danger">*</b>
                                            </label>
                                            <input type="text" class="form-control m-input"
                                                   id="contact_address" name="contact_address"
                                                   value="{{$deliveryCustomerAddress != null ? $deliveryCustomerAddress['address'] : ( $item['customer_contact_code'] != null ? $item['contact_address'] : $item['customer_address'] )}}"
                                                   placeholder="@lang('Nhập địa chỉ người nhận')...">
                                        </div>

                                        <div class="col-12 form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Ghi chú'):
                                            </label>
                                            <textarea class="form-control m-input" id="note" name="note" rows="5" cols="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8 pl-3">
                        <div class="row">
                            <div class="col-12 m-portlet m-portlet--head-sm ml-4">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/gift.png')}}" >
                                            <h2 class="m-portlet__head-text">
                                                @lang('Thông tin sản phẩm')
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped m-table m-table--head-bg-default" id="table_product">
                                                    <thead class="bg">
                                                    <tr>
                                                        <th class="tr_thead_list">@lang('Mã sản phẩm')</th>
                                                        <th class="tr_thead_list">@lang('Tên sản phẩm')</th>
                                                        <th class="tr_thead_list">@lang('Số lượng còn lại')</th>
{{--                                                        <th class="tr_thead_list">@lang('SKU')</th>--}}
                                                        <th class="tr_thead_list text-center">@lang('Số lượng giao')</th>
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
{{--                                                            <td>--}}
{{--                                                                <input class="form-control sku sku_{{$v['object_id']}}" onchange="createHistory.changeSKU('{{$v['object_id']}}')">--}}
{{--                                                                <span class="error_sku_{{$v['object_id']}} color_red"></span>--}}
{{--                                                            </td>--}}
                                                            <td class="text-center">
                                                                <div>
                                                                    <button type="button" class="d-inline btn-quantity hover-cursor" style="width:35px;height:35px" onclick="delivery.changeValue(`{{$item['delivery_id']}}`,`{{$v['object_id']}}`,'minus')"><i class="fas fa-minus"></i></button>
                                                                    <input class="form-control quantity w-25 d-inline" value="{{$v['quantity']}}" onchange="createHistory.changeQuantity('{{$item['delivery_id']}}')">
                                                                    <button type="button" class="d-inline btn-quantity hover-cursor" style="width:35px;height:35px" onclick="delivery.changeValue(`{{$item['delivery_id']}}`,`{{$v['object_id']}}`,'plus')"><i class="fas fa-plus"></i></button>
                                                                </div>
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
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="row">
                                                        <div class="col-4 d-flex align-items-center">
                                                            <label class="mb-0">
                                                                @lang('Trọng lượng'):<b class="text-danger staff_hide">*</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-4">
                                                            <input type="text" class="form-control" id="weight" name="weight" onchange="delivery.previewOrder()">
                                                        </div>
                                                        <div class="col-4">
                                                            <select class="form-control select2-fix" id="type_weight" name="type_weight" onchange="delivery.previewOrder()">
                                                                <option value="gam">gam</option>
                                                                <option value="kg">kg</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-7">
                                                    <div class="row">
                                                        <div class="col-3 d-flex align-items-center">
                                                            <label class="mb-0">
                                                                @lang('Kích thước'):<b class="text-danger staff_hide">*</b>
                                                            </label>
                                                        </div>
                                                        <div class="col-2">
                                                            <input type="text" class="form-control length_input" placeholder="{{__('Dài')}}" onchange="delivery.previewOrder()">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text" class="form-control width_input" placeholder="{{__('Rộng')}}" onchange="delivery.previewOrder()">
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="text" class="form-control height_input" placeholder="{{__('Cao')}}" onchange="delivery.previewOrder()">
                                                        </div>
                                                        <div class="col-1 d-flex align-items-center">
                                                            <label class="mb-0">cm</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 m-portlet m-portlet--head-sm ml-4">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <img class="img-fluid" style="width: 15px;margin-right: 10px;" src="{{asset('static/backend/images/car.png')}}" >
                                            <h2 class="m-portlet__head-text">
                                                @lang('Thông tin vận chuyển')
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="row">
                                                        <label class="col-3">{{__('Nơi lấy hàng')}}: </label>
                                                        <div class="col-9">
                                                            <select class="form-control select2-fix pick_up" style="width: 100%" id="pick_up" name="pick_up" onchange="delivery.changeAddress()">
                                                                @if(isset($optionPickupAddress) && count($optionPickupAddress) > 0)
                                                                    <option value=""></option>
                                                                    @foreach($optionPickupAddress as $v)
                                                                        <option value="{{$v['warehouse_id']}}" data-address="{{$v['address']}}" {{$item['branch_id'] == $v['branch_id'] ? 'selected' : ''}}>
                                                                            {{$v['name'] . ' - ' . $v['address']}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label>{{__('Địa chỉ lấy hàng')}}: </label>
                                                    <span>
                                                        <strong class="pick_up_address">
                                                            @if(isset($optionPickupAddress) && count($optionPickupAddress) > 0)
                                                                @foreach($optionPickupAddress as $v)
                                                                    @if($item['branch_id'] == $v['branch_id'])
                                                                        {{$v['address']}}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-3">
                                            <label class="mb-0">
                                                @lang('Cách thức nhận hàng'):<b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control select2-fix selectReceiptProduct" onchange="delivery.changeReceiptProduct()">
                                                <option value="delivery_unit">{{__('Đơn vị vận chuyển giao hàng')}}</option>
                                                <option value="staff">{{__('Nhân viên giao hàng')}}</option>
                                            </select>
                                        </div>
                                        <div class="col-12 block-receipt-product mt-3">
                                            <div class="row">
                                                <div class="col-12 mt-3 block-receipt-product-select">
{{--                                                    @include('delivery::delivery-history.append.block-receipt-product')--}}
                                                </div>
                                                <div class="col-12">
                                                    <label>{{__('Thời gian giao hàng dự kiến')}}: <span class="expected_delivery_time"></span></label>
                                                    <input type="hidden" id="time_ship" name="time_ship" value="">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-check-label m-checkbox m-checkbox--air">
                                                        <input type="checkbox" name="is_insurance" value="1" id="is_insurance" onchange="delivery.previewOrder()">
                                                        <span></span>
                                                        <div class="pt-1">{{ __('Bảo hiểm hàng hoá') }} <span class="color-red insurance">0</span><span class="color-red">đ</span>
                                                            <input type="hidden" id="insurance_amount" value="0">
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="row">
                                                        <label class="form-check-label m-checkbox m-checkbox--air col-3  d-flex align-items-center">
                                                            <strong>{{ __('Lưu ý') }}: </strong>
                                                        </label>
                                                        <div class="col-9">
                                                            <select class="form-control select2-fix required_note" onchange="delivery.previewOrder()">
                                                                <option value="KHONGCHOXEMHANG">{{__('Không cho xem hàng')}}</option>
                                                                <option value="CHOTHUHANG">{{__('Cho thử hàng')}}</option>
                                                                <option value="CHOXEMHANGKHONGTHU">{{__('Cho xem hàng không cho thử')}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
{{--                                                <div class="col-6 mb-3">--}}
{{--                                                    <label class="form-check-label m-checkbox m-checkbox--air">--}}
{{--                                                        <input type="checkbox" name="is_post_office" value="1" id="is_post_office" onchange="delivery.changePostOffice()">--}}
{{--                                                        <span></span>--}}
{{--                                                        <div class="pt-1">{{ __('Mang ra bưu cục') }} <span class="color-red">5,000đ</span></div>--}}
{{--                                                    </label>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-6 mb-3">--}}
{{--                                                    <div class="row">--}}
{{--                                                        <label class="form-check-label m-checkbox m-checkbox--air col-3 d-flex align-items-center">--}}
{{--                                                            <strong>{{ __('Bưu cục') }}: </strong>--}}
{{--                                                        </label>--}}
{{--                                                        <div class="col-9">--}}
{{--                                                            <select class="form-control select2-fix" >--}}
{{--                                                                <option value="">{{__('Bưu cục Huỳnh Tấn Phát')}}</option>--}}
{{--                                                            </select>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="col-6">--}}
{{--                                                    <div class="row">--}}
{{--                                                        <div class="col-8">--}}
{{--                                                            <input type="text" name="" class="form-control" placeholder="{{__('Nhập mã giảm giá')}}">--}}
{{--                                                        </div>--}}
{{--                                                        <div class="col-4">--}}
{{--                                                            <button type="button" name="" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">{{__('Áp dụng')}}</button>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-6 d-flex align-items-center">--}}
{{--                                                    <label>{{__('Phí giảm giá')}}: <span class="color-red">15,500đ</span></label>--}}
{{--                                                </div>--}}

                                            </div>
                                        </div>
                                        <div class="col-12 block-receipt-staff mt-3" style="display:none">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label>{{__('Thời gian giao hàng dự kiến')}}: <b class="text-danger">*</b></label>
                                                    <div class="m-input-icon m-input-icon--right">
                                                        <input type="text" class="form-control searchDateForm" id="time_ship_staff" placeholder="{{__('Chọn thời gian giao hàng dự kiến')}}">
                                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                                        <span><i class="la la-calendar"></i></span></span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label>{{__('Nhân viên')}}: <b class="text-danger">*</b></label>
                                                    <select class="form-control select2-fix" style="width: 100%" id="delivery_staff" name="delivery_staff">
                                                        <option value="">{{__('chọn nhân viên giao hàng')}}</option>
                                                        @foreach($optionCarrier as $v)
                                                            <option value="{{$v['user_carrier_id']}}">{{$v['full_name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <hr class="w-100">
                                                <div class="col-6">
                                                    <label class="form-check-label m-checkbox m-checkbox--air">
                                                        <input type="checkbox" name="is_cod_amount" value="1" id="is_cod_amount">
                                                        <span></span>
                                                        <div class="pt-1">{{ __('Thu hộ tiền (COD)') }} <span class="color-red text-cod">{{number_format(0)}}</span><span class="color-red">đ</span></div>
                                                    </label>
                                                </div>
                                                <div class="col-6 text-right block-receipt-product">
                                                    <label>{{__('Tổng phí trả ĐTGH')}}: <span class="color-red total_fee">0</span><span class="color-red">đ</span></label>
                                                    <input type="hidden" class="total_fee_input" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" class="form-control m-input d-none"
                                       id="amount" name="amount"
                                       value="{{$item['amount']}}" disabled>
                                <input type="hidden" id="order_id" value="{{$item['order_id']}}">

                                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit mb-3">
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
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="amount_cod" id="amount_cod" value="">
        <input type="hidden" class="form-control d-none"
               id="amount_hidden"
               value="{{(int)$item['amount']}}" disabled>
        <input type="hidden"  id="total_fee" value="{{($item['amount'] - $item['total']) <= 0 ? 0 : ($item['amount'] - $item['total'])}}">
    </form>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimalQuantity = parseInt('{{$decimalQuantity}}');
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script>

        $(document).ready(function(){
            new AutoNumeric.multiple('#weight', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: 0,
                eventIsCancelable: true,
                minimumValue: 0,
                maximumValue: 50000,
            });

            new AutoNumeric.multiple('.length_input,.height_input', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: 0,
                eventIsCancelable: true,
                minimumValue: 0,
                maximumValue: 100,
            });

            new AutoNumeric.multiple('.width_input', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: 0,
                eventIsCancelable: true,
                minimumValue: 0,
                maximumValue: 100,
            });

            $('.searchDateForm').datetimepicker({
                format: "dd/mm/yyyy",
                todayHighlight: true,
                autoclose: true,
                startView: 2,
                minView: 2,
                forceParse: 0,
                pickerPosition: 'bottom-left'
            });

        })
    </script>
    <script src="{{asset('static/backend/js/delivery/delivery/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/delivery/delivery-history/delivery.js?v='.time())}}" type="text/javascript"></script>
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
            delivery.priceCod();
        })
    </script>
@stop


