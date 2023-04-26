@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> @lang("QUẢN LÝ ĐƠN HÀNG ")</span>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css?v='.time())}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
{{--    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">--}}
    <link rel="stylesheet" href="{{asset('static/backend/css/pos-order.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/todh.css')}}">
    <style>
        /* span.select2{
            width: 100% !important;
        } */
        #popup-list-serial .modal-dialog {
            max-width: 80%;
        }
        .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-aside-right, .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-wrapper {
            margin-bottom: 0px !important;
        }
        .m-body .m-content {
            padding-bottom: 0px;
        }
        .m-portlet .m-portlet__body {
            padding-bottom: 0px !important;
        }
        .td_vtc{
            vertical-align: middle !important;
        }

        .table-selected.active {
            background : #36a3f7;
            color : #fff;
        }

        .form-search .fa-search {
            left: 160px;
        }

        .quantity-popup {
            height : 31px;
        }

        .form-search {
            position : relative;
        }

        .form-search .fa-search {
            position: absolute;
            top: 0px;
            left: 10px;
            color: #9ca3af;
            bottom: 0;
            margin: auto;
            height: 0;
            line-height: 0;
        }

        .background-style-1 {
            border-style: dotted;
            background-color: #FFCC99;
            color : #000 !important;
        }

        .background-style-1 span {
            color : #000 !important;
        }
        .info-table {
            aspect-ratio: 3 / 2;
            height: 150px !important;
            width : 100% !important;
        }
        .btn-fix {
            padding: 15px !important;
        }
        .tag__header a {
            color : #000;
        }

        .scroll-topping {
            height: 64vh;
            overflow-y: scroll;
        }

        .ul_category {
            /*white-space: nowrap;*/
            display: inherit;
            width: 100%;
            overflow-x: auto;
        }
        .ul_category li {
            display: inline-block;
        }
        .table-name {
            padding : 0;
            color : #000;
        }
        a:hover {
            text-decoration: unset !important;
        }
        .m-portlet__head-tools {
            width : 60%;
        }
        .item , .item a {
            display : inline-block;
        }

        #lstItemOrder {
            height: 340px;
            overflow-y: auto;
        }
        .list-order-select {
            width: 80%;
        }

        .list-order-select > .tag__header{
            width: 95%;
            overflow-x: auto;
            white-space: nowrap;
        }

        .demo-index {
            overflow-y : auto;
            max-height : 55vh;
        }
    </style>
@endsection
@section('content')
    @include('admin::orders.modal-discount')
    @include('admin::orders.modal-discount-bill')
    @include('fnb::orders.receipt')
    @include('admin::orders.modal-enter-phone-number')
    @include('admin::orders.modal-enter-email')
    <div class="m-portlet m-portlet--head-sm" id="m-order-add" style="margin-bottom: 0px;">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THANH TOÁN ĐƠN HÀNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="d-flex justify-content-between w-100">
                    <div class="list-order-select">
                        {!! $listOrderTable !!}
                    </div>

                    {{--                @if(in_array('fnb.orders.add-orders',session('routeList')))--}}
                    <div>
                        <a
                            href="{{route('fnb.orders.add-orders')}}"
                            class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> {{__('THÊM ĐƠN HÀNG')}} POS</span>
                            </span>
                        </a>
                        <a href="{{route('fnb.orders.add-orders')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                color_button btn_add_mobile"
                           style="display: none">
                            <i class="fa fa-plus-circle" style="color: #fff"></i>
                        </a>
{{--                        <a href="javascript:void(0)" onclick="order.fullScreen()"--}}
{{--                           class="btn-full-screen btn m-btn btn-sm m-btn--icon m-btn--pill btn_add_pc">--}}
{{--                            <span> {{__('Toàn màn hình')}} <img src="{{asset('static/backend/images/fnb/icon-full-screen.png')}}"></span>--}}
{{--                        </a>--}}
                    </div>
                    {{--                @endif--}}
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-5">
                    @if($is_edit_full == 1)
                    <div class="form-group m-form__group row">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm tab-list m--margin-bottom-10 ul_type"
                                role="tablist" style="margin-bottom: 0px !important;">
{{--                                <li class="nav-item m-tabs__item type">--}}

{{--                                    <a class="nav-link m-tabs__link" data-toggle="tab"--}}
{{--                                       href="javascript:void(0)" onclick="order.chooseType('area')" role="tab"--}}
{{--                                       data-name="area">--}}
{{--                                        {{__('Khu vực - bàn')}}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <li class="nav-item m-tabs__item type">
                                    <a class="nav-link m-tabs__link  active show" data-toggle="tab" href="javascript:void(0)"
                                       onclick="order.chooseType('product')" role="tab" data-name="product">
                                        {{__('Sản phẩm')}}
                                    </a>
                                </li>

                                @if(count($data) > 0)
                                    <li class="nav-item m-tabs__item type">
                                        <a href="javascript:void(0)" onclick="order.chooseType('member_card')"
                                           class="nav-link m-tabs__link tab_member_card" data-toggle="tab"
                                           role="tab"
                                           aria-selected="false" data-name="member_card">
                                            <i class="la la-shopping-cart"></i>@lang("Thẻ dịch vụ đã mua")
                                        </a>
                                    </li>
                                @endif
                            </ul>
                    </div>
                    <div class="m-separator m-separator-update m-separator--dashed"></div>
                    <div class="row">
                        <div class="col-lg-12 tab_category_order tab_category_order_new" id="tab_category">
                            <div class="owl-carousel ul_category nav nav-pills nav-pills--success" id="ul_category" >

                            </div>
{{--                            <img src="{{asset('static/backend/images/fnb/icon-next.png')}}" onclick="order.nextSlider()">--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="m-input-icon m-input-icon--left">
                                    <span class="">
                                            <input id="search" name="search" autocomplete="off" type="text" style="display:none"
                                                   class="form-control m-input--pill m-input" value="" onkeyup="search('search',event)"
                                                   placeholder="{{__('Nhập thông tin tìm kiếm')}}">

                                            <input id="search_product" name="search_product" onkeyup="search('search_product',event)"  autocomplete="off" type="text"
                                                   class="form-control m-input--pill m-input" value=""
                                                   placeholder="{{__('Nhập thông tin tìm kiếm')}}">
                                    </span>
                            <span class="m-input-icon__icon m-input-icon__icon--left"><span><i
                                            class="la la-search"></i></span></span>
                        </div>
                       
                    </div>
                    <div id="list-product">
                        <div class="demo-index pt-3 row">
                                    
                        </div>
                        <input type="hidden" value="" id="category_id_hidden">
                    </div>
                    @endif
                </div>

                <div class="col-lg-7">
                        <input type="hidden" name="order_code" id="order_code" value="{{$item['order_code']}}">
                        <input type="hidden" name="order_id" id="order_id" value="{{$item['order_id']}}">
                        <input type="hidden" name="customer_id" id="customer_id" value="{{$item['customer_id']}}">
                        <input type="hidden" id="customer_id_modal" value="{{$item['customer_id']}}">
                        <input type="hidden" name="money" id="money" value="{{isset($money) ? $money : 0}}">
                    <div class="row bdb" style="padding: 0px !important;">
                        <div class="m-section__content col-lg-12">
                            <div class="row bdb" id="lstItemOrder">
                                <div class="table-responsive">
                                    <table class="table m-ttable-striped able m-table--head-bg-default" id="table_add">
                                        <thead class="bg">
                                        <tr>
{{--                                            <td class="tr_thead_od">#</td>--}}
                                            <td width="5%"></td>
                                            <td width="55%" class="tr_thead_od">{{__('Tên')}}</td>
{{--                                            <td class="tr_thead_od">{{__('Giá')}}</td>--}}
                                            <td width="20%" class="tr_thead_quan width-110-od">{{__('Số lượng')}}</td>
                                            <td width="10%"  class="tr_thead_od text-center">{{__('Giảm')}}</td>
                                            <td width="10%" class="tr_thead_od text-center">{{__('Thành tiền')}}</td>
{{--                                            <td></td>--}}
                                        </tr>
                                        </thead>
                                        <tbody class="tr_thead_od">
                                        <?php $tmpPosition = 0 ?>
                                        @foreach($order_detail as $key=>$item1)
                                            <tr class="tr_table tr_table_{{$item1['object_id']}} tr_table_{{$item1['object_id']}}_{{$item1['order_detail_id']}} {{in_array($item1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']) ? 'promotion_gift' : ''}}">
{{--                                                <td class="td_vtc" style="width: 30px;"></td>--}}
                                                <td>
                                                    <div onclick="order.selectTopping('{{$item1['object_id']}}','{{$item1['order_detail_id']}}')" class="hover-cursor">
{{--                                                        <img src="{{asset('static/backend/images/menu_add.png')}}" id="{{$item1['object_id']}}_{{$item1['object_id']}}" class="pr-1" ><label for="{{$item1['object_id']}}_{{$item1['object_id']}}">{{__('Món thêm')}}</label>--}}
                                                        <img src="{{asset('static/backend/images/menu_add.png')}}" id="{{$item1['object_id']}}_{{$item1['object_id']}}" class="pr-1" >
                                                    </div>
                                                </td>
                                                <td class="td_vtc" style="color: #000; font-weight: 500; min-width: 100px;">
                                                    <span><b>{{$item1['object_name']}}</b></span>
                                                    <div class="block">
                                                        <span>
                                                            @foreach($item1['name_attribute'] as $keyAttribute => $valueAttribute)
                                                                @if($keyAttribute == 0)
                                                                    {{$valueAttribute['product_attribute_group_name'].' '.$valueAttribute['product_attribute_label']}}
                                                                @else
                                                                    ,{{$valueAttribute['product_attribute_group_name'].' '.$valueAttribute['product_attribute_label']}}
                                                                @endif
                                                            @endforeach
                                                        </span><br>
                                                        @if(isset($order_detail_child[$item1['order_detail_id']]))
                                                            <span><b>Topping</b> :
                                                                @foreach($order_detail_child[$item1['order_detail_id']] as $keyValue => $value)
                                                                    @if($keyValue == 0)
                                                                        {{$value['object_name']}}
                                                                    @else
                                                                        ,{{$value['object_name']}}
                                                                    @endif
                                                                @endforeach
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <input type="hidden" name="key_string" value="{{$item1['order_detail_id']}}">
                                                    <input type="hidden" name="product_id" value="{{$item1['product_id']}}">
                                                    <input type="hidden" name="id_detail" id="id_detail"
                                                           value="{{$item1['order_detail_id']}}">
                                                    <input type="hidden" name="id" value="{{$item1['object_id']}}">
                                                    <input type="hidden" name="name" value="{{$item1['object_name']}}">
                                                    <input type="hidden" name="object_type"
                                                           value="{{$item1['object_type']}}">
                                                    <input type="hidden" name="object_code" class="object_code_{{$item1['object_id']}}"
                                                           value="{{$item1['object_code']}}">
                                                </td>
                                                <td class="td_vtc td_vtc_{{$item1['order_detail_id']}} text-center"  style="width: 115px;">
                                                    <span class="text_price">{{number_format($item1['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')</span><br>
                                                    <input type="hidden" name="price" class="value_price" value="{{$item1['price']}}">
                                                    @if(in_array($item1['object_type'], ['product', 'service', 'service_card']))
                                                        <input style="text-align: center;height: 31px;font-size: 13px;" type="text" name="quantity"
                                                               {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                               class="quantity form-control btn-ct"
                                                               value="{{$item1['quantity']}}"
                                                               data-id="{{$key+1}}" {{$customPrice == 1 || $item1['is_change_price'] == 1 ? 'disabled' : ''}}>
                                                        <input type="hidden" name="quantity_hidden"
                                                               value="{{$item1['quantity']}}">
                                                    @elseif($item1['object_type'] == 'member_card')
                                                        <input style="text-align: center;height: 31px;font-size: 13px;" type="text" name="quantity"

                                                               {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                               class="quantity_card form-control btn-ct-input"
                                                               value="{{$item1['quantity']}}" disabled>
                                                        <input type="hidden" name="quantity_hidden"
                                                               value="{{$item1['max_quantity_card']['number_using']-$item1['max_quantity_card']['count_using']}}">
                                                    @elseif(in_array($item1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                                        <input style="text-align: center;height: 31px;font-size: 13px;" type="text" name="quantity"
                                                               class="form-control btn-ct"
                                                               {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                               value="{{$item1['quantity']}}" data-id="{{$key+1}}"
                                                               disabled>
                                                        <input type="hidden" name="quantity_hidden"
                                                               value="{{$item1['quantity']}}">
                                                    @endif
                                                </td>
                                                <td class="discount-tr-{{$item1['object_type']}}-{{$key+1}} td_vtc" style="text-align: center; width: 130px;">
                                                    @if($is_edit_full == 1)
                                                        @if($item1['object_type'] !='member_card' && $customPrice == 0 && $item1['is_change_price'] == 0)
                                                            @if($item1['discount']>0)
                                                                @if($item1['object_type']=='service')
                                                                    <a class="abc" href="javascript:void(0)"
                                                                       onclick="list.close_amount('{{$item1['object_id']}}','1','{{$key+1}}')">
                                                                        <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                    </a>
                                                                @elseif($item1['object_type']=='service_card')
                                                                    <a class="abc" href="javascript:void(0)"
                                                                       onclick="list.close_amount('{{$item1['object_id']}}','2','{{$key+1}}')">
                                                                        <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                    </a>
                                                                @elseif($item1['object_type'] =='product')
                                                                    <a class="abc" href="javascript:void(0)"
                                                                       onclick="list.close_amount('{{$item1['object_id']}}','3','{{$key+1}}')">
                                                                        <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                @if($item1['object_type']=='service')
                                                                    <a href="javascript:void(0)" class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"  onclick="list.modal_discount('{{$item1['amount']}}','{{$item1['object_id']}}','1','{{$key+1}}')">
                                                                        <i class="la la-plus icon-sz"></i>
                                                                    </a>
                                                                @elseif($item1['object_type']=='service_card')
                                                                    <a href="javascript:void(0)" class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" onclick="list.modal_discount('{{$item1['amount']}}','{{$item1['object_id']}}','2','{{$key+1}}')">
                                                                        <i class="la la-plus icon-sz"></i>
                                                                    </a>
                                                                @elseif($item1['object_type'] =='product')
                                                                    <a href="javascript:void(0)" class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"  onclick="list.modal_discount('{{$item1['amount']}}','{{$item1['object_id']}}','3','{{$key+1}}')">
                                                                        <i class="la la-plus icon-sz"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            {{--                                                    @else--}}
                                                            {{--                                                        0đ--}}
                                                        @endif
                                                    @endif
                                                    @if($item1['discount']>0)
                                                        {{number_format($item1['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                    @endif
                                                    <input type="hidden" name="discount" value="{{$item1['discount']}}">
                                                    <input type="hidden" name="voucher_code"
                                                           value="{{$item1['voucher_code']}}">

                                                </td>
                                                <td class=" td_vtc" style="text-align: center; width: 130px;">
                                                    <span class="amount-tr">
                                                        @if (isset($customPrice) && $customPrice == 1 || $item1['is_change_price'] && in_array($item1['object_type'], ['service', 'product', 'service_card']))
                                                            <input name="amount" style="text-align: center;"
                                                                   class="form-control amount" id="amount_{{$key+1}}"
                                                                   value="{{number_format($item1['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                        @else
                                                            {{number_format($item1['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                            <input type="hidden" name="amount" value="{{$item1['amount']}}">
                                                        @endif
                                                    </span>


                                                    <input type="hidden" name="is_change_price"
                                                           value="{{$item1['is_change_price']}}">
                                                    <input type="hidden" name="is_check_promotion"
                                                           value="{{$item1['is_check_promotion']}}">
                                                    @if($is_edit_full == 1)
                                                        @if(in_array($item1['object_type'], ['product', 'service', 'service_card']))
                                                            <a class="remove"
                                                               {{$is_edit_full == 0 ? 'hidden' : ''}} href="javascript:void(0)"
                                                               style="color: #a1a1a1"><i class="la la-trash"></i></a>
                                                        @elseif($item1['object_type'] == 'member_card')
                                                            <a class="remove_card"
                                                               {{$is_edit_full == 0 ? 'hidden' : ''}} href="javascript:void(0)"
                                                               style="color: #a1a1a1"><i class="la la-trash"></i></a>
                                                        @elseif(in_array($item1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                                            <a href="javascript:void(0)"
                                                               {{$is_edit_full == 0 ? 'hidden' : ''}} onclick="order.removeGift(this)"
                                                               style="color: #a1a1a1"><i class="la la-trash"></i></a>
                                                        @endif
                                                    @endif

                                                </td>
                                                <input type="hidden" id="numberRow_{{$key}}" class="numberRow" value="{{$key}}">
                                                <?php $tmpPosition = $tmpPosition + 1 ?>
                                            </tr>
                                            @if($item1['inventory_management'] == 'serial')
                                                <tr class="tr_table_child_{{$item1['order_detail_id']}}">
                                                    <td>
                                                        <select class="form-control input_child input_child_{{$item1['order_detail_id']}}" onfocusin="order.changeSelectSearch(`{{$item1['object_id']}}`,'{{$item1['object_code']}}',`{{$item1['order_detail_id']}}`)" onkeydown="order.enterSerial(event,`{{$item1['object_id']}}`,`{{$item1['order_detail_id']}}`)">
                                                            <option value="">{{__('Nhập số serial và enter')}}</option>
                                                        </select>
                                                    </td>
                                                    <td colspan="6" class="block_tr_child_{{$item1['order_detail_id']}}">
                                                        @if(isset($listSerialOrder[$item1['order_detail_id']]))
                                                            @foreach($listSerialOrder[$item1['order_detail_id']] as $keySerial => $itemSerial)
                                                                @if($keySerial < 4)
                                                                    <span class="badge badge-pill badge-secondary" >{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="order.removeSerial('{{$session}}','{{$itemSerial['object_id']}}','{{$itemSerial['object_code']}}','{{$itemSerial['order_detail_id']}}','{{$itemSerial['serial']}}')"></i></span>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($listSerialOrder[$item1['order_detail_id']]) && count($listSerialOrder[$item1['order_detail_id']]) > 4)
                                                            <a href="javascript:void(0)" onclick="order.showPopupSerial('{{$session}}','{{$item1['object_id']}}','{{$item1['object_code']}}','{{$item1['order_detail_id']}}')">{{__('Xem thêm')}}</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <span class="error-table" style="color: #ff0000"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m--margin-left-5 m--margin-right-5" style="padding-top: 15px !important; padding-bottom: 0px !important;">
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
{{--                                        @if($is_update_order == 1)--}}
{{--                                            <button type="submit" id="btn_add" onclick="order.submitOrder()"--}}
{{--                                                    class="btn btn-fix btn-success btn-lg m-btn son-mb m-btn m-btn--icon btn-save-info-update--}}
{{--                                                    m-btn--wide m-btn--md btn-add">--}}
{{--                                                <img src="{{asset('static/backend/images/fnb/save-order.png')}}"> {{__('Lưu đơn hàng')}}--}}
{{--                                            </button>--}}
{{--                                        @endif--}}
                                        @if ($item['customer_id'] != 1)
                                            <button type="submit" id="btn_add" onclick="delivery.showPopup()"
                                                    class="btn btn-fix btn-success btn-lg m-btn son-mb m-btn m-btn--icon btn-shipping-address btn-save-info-update
                                                    m-btn--wide m-btn--md btn-add">
                                                <img src="{{asset('static/backend/images/fnb/shipping.png')}}"> {{__('Giao hàng')}}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-lg-12 customer">
                                            @if ($item['customer_id'] != 1)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="block-customer">
                                                                <div class="image-customer">
                                                                    <img src="{{$item['customer_avatar'] != null ? $item['customer_avatar'] : asset('static/backend/images/image-user.png')}}" style="width: 52px;height: 52px">
                                                                </div>
                                                                <div class="info-customer">
                                                                    <span class="m-widget4__title m-font-uppercase"><b>{{$item['full_name']}}</b></span><br>
                                                                    <span class="m-widget4__title m-font-uppercase">{{isset($item['phone']) ? ' - '.$item['phone'] : ''}}</span><br>
                                                                    <span class="m-widget4__title ">{{number_format($item['point_rank'])}} {{__('điểm')}}</span><br>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-8 mt-4">
                                                            <p>{{__('Mã khách hàng')}} : <strong>{{$item['customer_code']}}</strong></p>
                                                            <p>{{__('Công nợ')}} : <strong>{{number_format($debt)}}đ</strong></p>
                                                            <div class="block-address receipt_info_check_block">
                                                                <p>{{__('Tên người nhận:')}} : <strong>{{$detailAddress != null ? $detailAddress['customer_name'] : ''}}</strong></p>
                                                                <p>{{__('SĐT người nhận:')}} : <strong>{{$detailAddress != null ? $detailAddress['customer_phone'] : ''}}</strong></p>
                                                                <p>{{__('Địa chỉ nhận hàng:')}} : <strong>{{$detailAddress != null ? $detailAddress['address'].','.$detailAddress['ward_name'].','.$detailAddress['district_name'].','.$detailAddress['province_name'] : ''}}</strong></p>
                                                                <p>{{__('Thời gian mong muốn nhận hàng:')}} <strong>{{$item['time_address'] != '' && $item['time_address'] != '0000-00-00' ? ($item['type_time'] == 'before' ? __('Trước') : ($item['type_time'] == 'in' ? __('Trong') : __('Sau'))).' '.\Carbon\Carbon::parse($item['time_address'])->format('d/m/Y') : ''}}</strong></p>
                                                                <hr class="w-100">
                                                                <div class="row">
                                                                    @if ($itemFee != null)
                                                                        <div class="col-6">
                                                                            <div class="form-group btn-fee-check">
                                                                                <span>
                                                                                    <input type="radio" id="delivery_type_0" name="type_shipping"
                                                                                           value="1">
                                                                                    <label for="delivery_type_0"
                                                                                           class="text-center w-100 delivery_type delivery_type_0"
                                                                                           data-delivery-cost-id="{{$itemFee['delivery_cost_id']}}"
                                                                                           data-type-shipping="0"
                                                                                           data-fee="{{(int)$itemFee['delivery_cost']}}"
                                                                                           onclick="delivery.changeDeliveryStyle(this)">
                                                                                        <div>
                                                                                            <label style="margin-bottom: 0px;"><img
                                                                                                        class="img-fluid color-blue-check"
                                                                                                        src="{{asset('static/backend/images/car.png')}}"> <strong>{{__('Tiết kiệm')}}</strong></label><br>
                                                                                        <label class="description-check"
                                                                                               style="margin-bottom: 0px;">{{__('Giao hàng thường')}}</label><br>
                                                                                        <label class="color-blue-check"
                                                                                               style="margin-bottom: 0px;"><strong>{{number_format($itemFee['delivery_cost'])}}đ</strong></label><br>
                                                                                        </div>
                                                                                    </label>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        @if (isset($itemFee['is_delivery_fast']) && $itemFee['is_delivery_fast'] == 1)
                                                                            <div class="col-6">
                                                                                <div class="form-group btn-fee-check">
                                                                                    <span>
                                                                                        <input type="radio" id="delivery_type_1" name="type_shipping"
                                                                                               value="1">
                                                                                        <label for="delivery_type_1"
                                                                                               class="text-center w-100 delivery_type delivery_type_1"
                                                                                               data-delivery-cost-id="{{$itemFee['delivery_cost_id']}}"
                                                                                               data-type-shipping="1"
                                                                                               data-fee="{{(int)$itemFee['delivery_fast_cost']}}"
                                                                                               onclick="delivery.changeDeliveryStyle(this)">
                                                                                            <div>
                                                                                                <label style="margin-bottom: 0px;"><img
                                                                                                            class="img-fluid color-blue-check"
                                                                                                            src="{{asset('static/backend/images/clock.png')}}"> <strong>{{__('Hoả tốc')}}</strong></label><br>
                                                                                                <label class="description-check"
                                                                                                       style="margin-bottom: 0px;">{{__('Giao hàng nhanh chóng')}}</label><br>
                                                                                                <label class="color-blue-check"
                                                                                                       style="margin-bottom: 0px;"><strong>{{number_format($itemFee['delivery_fast_cost'])}}đ</strong></label><br>
                                                                                            </div>
                                                                                        </label>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="block-customer">
                                                                <div class="image-customer">
                                                                    <img src="{{asset('static/backend/images/image-user.png')}}"
                                                                         style="width: 52px;height: 52px">
                                                                </div>
                                                                <div class="info-customer">
                                                                    <span class="m-widget4__title m-font-uppercase"><b>{{$item['full_name']}}</b></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8">

                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 bdr">

                            <div class="form-group row m--margin-right-5">
                                <div class="m-list-timeline__items">
                                    <div class="m-list-timeline__item sz_bill d-none">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Nhân viên phục vụ')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest staff_name" style="width: 150px;">
                                            {{isset($staff['staff_id']) ? $staff['staff_name'] : ''}}
                                        </span>
                                        <input type="hidden" id="staff_id" name="staff_id"
                                               class="form-control staff_id" value="{{isset($staff['staff_id']) ? $staff['staff_id'] : ''}}">
                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Tổng tiền')}}:</span>
                                        <span class="m-list-timeline__time m--font-boldest append_bill total_bill" style="width: 150px;">
                                                {{number_format($item['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                            </span>
                                            <input type="hidden" name="total_bill" id="total_bill"
                                            class="form-control total_bill"
                                            value="{{$item['total']}}">
                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">@lang("Chiết khấu thành viên")
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest" style="width: 150px;">
                                            <span class="span_member_level_discount">0</span>@lang('đ')
                                        </span>
                                        <input type="hidden" name="member_level_discount" id="member_level_discount"
                                               class="form-control" value="0">
    
                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text m--font-boldest sz_word">@lang("Giảm giá")
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest discount_bill" style="width: 150px;">
                                           @if($item['discount']>0)
                                                @if($is_edit_full == 1)
                                                    <a class="tag_a" href="javascript:void(0)"
                                                       onclick="list.close_discount_bill({{$item['total']}})">
                                                    <i class="la la-close cl_amount_bill"></i>
                                                </a>
                                                @endif
                                            @else
                                                @if($is_edit_full == 1)
                                                    <a href="javascript:void(0)" {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                    onclick="list.modal_discount_bill({{$item['total']}})"
                                                       class="tag_a">
                                                <i class="fa fa-plus-circle icon-sz m--margin-right-5"
                                                   style="color: #4fc4cb;"></i>
                                                </a>
                                                @endif
                                            @endif
                                            @if($item['discount']>0)
                                                {{number_format($item['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                            @else
                                                0 @lang('đ')
                                            @endif
    
                                            <input type="hidden" id="discount_bill" name="discount_bill"
                                                   value="{{$item['discount']}}">
                                        <input type="hidden" id="voucher_code_bill" name="voucher_code_bill"
                                               value="{{$item['voucher_code']}}">
                                    </span>
                                    </div>
    
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text m--font-boldest sz_word">{{__('Phí vận chuyển')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest" style="width: 150px;">
                                            <a href="javascript:void(0)"
                                               class="tag_a">
                                            </a>
                                            <span class="delivery_fee_text">{{number_format($item['tranport_charge'])}}</span> @lang('đ')
                                            <input type="hidden" id="delivery_fee" name="delivery_fee" value="{{$item['tranport_charge']}}">
                                            <input type="hidden" id="delivery_type" name="delivery_type" value="{{$item['type_shipping']}}">
                                            <input type="hidden" id="delivery_cost_id" name="delivery_cost_id" value="{{$item['delivery_cost_id']}}">
                                        </span>
                                    </div>
    
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text m--font-boldest sz_word">@lang("Thành tiền")
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest amount_bill" style="color: red; width: 150px;">
                                             {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                            <input type="hidden" name="amount_bill_input"
                                                   class="form-control amount_bill_input"
                                                   value="{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="text-right w-100">
                            <div class="move-table d-inline-block">
                                <button type="button" class="btn btn-info" onclick="order.moveTable('{{$item['order_id']}}')">
                                    <i class="fa flaticon-refresh"></i>
                                    {{ __('Chuyển bàn') }}
                                </button>
                            </div>
                            <div class="detached-table d-inline-block" onclick="order.splitTable('{{$item['order_id']}}')">
                                <button type="button" class="btn btn-info">
                                    <i class="fa fa-angle-double-left"></i>
                                    <i class="fa fa-angle-double-right"></i>
                                    {{ __('Tách bàn') }}
                                </button>
                            </div>
                            <div class="merge-table d-inline-block" onclick="order.mergeTable('{{$item['order_id']}}')">
                                <button type="button" class="btn btn-info">
                                    <i class="fa fa-angle-double-right"></i>
                                    <i class="fa fa-angle-double-left"></i>
                                    {{ __('Gộp bàn') }}
                                </button>
                            </div>
                            <div class="merge-bill d-inline-block" onclick="order.mergeBill('{{$item['order_id']}}')">
                                <button type="button" class="btn btn-info">
                                    <i class="fa 	fa-money-bill-wave"></i>
                                    {{ __('Gộp Bill') }}
                                </button>
                            </div>
{{--                            <div class="waiter d-inline-block">--}}
{{--                                <button type="button" class="btn btn-info" onclick="order.chooseWaiter()">--}}
{{--                                    <i class="fa fa-user-plus"></i>--}}
{{--                                    {{ __('Nhân viên phục vụ') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <a href="{{route('fnb.orders')}}"
                                       class="btn btn-fix btn-metal btn-lg m-btn m-btn--icon m-btn--wide m-btn--md wd_type">
                                        {{__('HỦY')}}
                                    </a>
                                </div>

                            </div>
                        </div>
                        <div class="form-group col-lg-3">
                            @if($is_update_order == 1)
                                <button type="button"  onclick="order.submitOrder()"
                                        class="btn btn-fix btn-success btn-lg m-btn son-mb m-btn m-btn--icon wd_type
                                    m-btn--wide m-btn--md btn-add">
                                    {{__('LƯU ĐƠN HÀNG')}}
                                </button>
                            @endif
                        </div>
                        <div class="form-group col-lg-3">
                            <button type="button"  onclick="print_bill.print('{{$item['order_id']}}')" style="background:#FEAA2B;border-color : #FEAA2B"
                                    class="btn btn-fix btn-success btn-lg m-btn son-mb m-btn m-btn--icon wd_type
                                m-btn--wide m-btn--md btn-add">
                                {{__('IN HÓA ĐƠN')}}
                            </button>
                        </div>
                        @if($is_payment_order == 1)
                            <div class="form-group col-lg-3">
                                <button type="button" onclick="processPayment.editPaymentAction('{{$paymentType}}')" id="btn_order"
                                        class="btn btn-fix btn-metal btn-lg m-btn son-mb m-btn m-btn--icon wd_type m-btn--wide m-btn--md" style="background-color: #FF794E">
                                    {{__('THANH TOÁN')}}
                                </button>
                            </div>
                        @endif
{{--                        @if($is_update_order == 1)--}}
{{--                            <div class="form-group col-lg-3">--}}
{{--                                <button type="submit" id="btn_add" onclick="save.submit_edit('{{$item['order_id']}}')"--}}
{{--                                        class="btn btn-fix btn-success btn-lg m-btn son-mb m-btn m-btn--icon wd_type--}}
{{--                                m-btn--wide m-btn--md btn-add">--}}
{{--                                    {{__('LƯU THÔNG TIN')}}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-print-card" style="display: none;"></div>

    <div class="modal fade" role="dialog" id="modal-print" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title title_index">
                        @lang("DANH SÁCH THẺ IN")
                    </h4>

                </div>
                <div class="modal-body body-card">
                    <div class="m-scrollable m-scroller ps ps--active-y"
                         data-scrollable="true"
                         data-height="380" data-mobile-height="300"
                         style="height: 500px; overflow: hidden;">
                        <div class="list-card load_ajax">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('fnb.orders')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10 m--margin-bottom-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang("THOÁT")</span>
						</span>
                        </a>

                        <button type="button" onclick="order.print_all()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md  m--margin-left-10 m--margin-bottom-10">
							<span>
							<i class="la la-print"></i>
							<span>@lang("IN TẤT CẢ")</span>
							</span>
                        </button>
                        <button type="button" onclick="ORDERGENERAL.sendAllCodeCard()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add m--margin-left-10 m--margin-bottom-10 btn-send-sms">
							<span>
							<i class="la la-mobile-phone"></i>
							<span>@lang("SMS TẤT CẢ")</span>
							</span>
                        </button>
                        {{--<button type="button"--}}
                        {{--class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add m--margin-left-10 btn-send-sms m--margin-bottom-10">--}}
                        {{--<span>--}}
                        {{--<i class="la la-compress"></i>--}}
                        {{--<span>CẢ HAI</span>--}}
                        {{--</span>--}}
                        {{--</button>--}}
                        <button type="button" onclick="order.send_mail()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add m--margin-left-10 m--margin-bottom-10">
							<span>
                                <i class="la la-envelope-o"></i>
							<span>@lang("GỬI EMAIL")</span>
							</span>
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <input type="hidden" name="pt-discount" class="form-control pt-discount"
           value="{{$item['member_level_discount'] != null ? $item['member_level_discount'] : 0}}">
    <form id="form-order-ss" target="_blank" action="{{route('fnb.orders.print-bill')}}" method="GET">
        <input type="hidden" name="ptintorderid" id="orderiddd" value="">
    </form>
    <input type="hidden" id="table_id" name="table_id" value="{{$item['fnb_table_id']}}">
    <input type="hidden" value="{{$orderIdsss}}" class="hiddenOrderIdss">
    <input type="hidden" id="order_source_id" value="{{$item['order_source_id']}}">
    <input type="hidden" id="custom_price" name="custom_price" value="{{$customPrice}}">
    <input type="hidden" id="session" value="{{$session}}">
    <div id="showPopup"></div>

    <input type="hidden" id="type_time_hidden" name="type_time_hidden" value="{{$item['type_time']}}">
    <input type="hidden" id="time_address_hidden" name="time_address_hidden" value="{{$item['time_address'] != '' && $item['time_address'] != '0000-00-00' ? $item['time_address'] : ''}}">
    <input type="hidden" id="customer_contact_id_hidden" name="customer_contact_id_hidden" value="{{$item['customer_contact_id']}}">
    <div class="popupShow"></div>
    <div class="append-popup"></div>
@endsection
@section('after_script')
    @if ($item['tranport_charge'] != 0 && $item['type_shipping'] == 0)
        <script>
            $(document).ready(function(){
                $('.delivery_type_0').trigger('click');
            })
        </script>
    @endif
    @if ($item['tranport_charge'] != 0 && $item['type_shipping'] == 1)
        <script>
            $(document).ready(function(){
                $('.delivery_type_1').trigger('click');
            })
        </script>
    @endif
    <script>
        var customPrice = {{$customPrice}};
        let numberRow = {{$tmpPosition}};
        let stt_tr = $('#table_add tbody tr').length;
        let number_using_voucher = 0;
        var noImage = '{{asset('/uploads/admin/icon/person.png')}}';
        var decimalsQuantity = parseInt({{$decimalQuantity}});
        $(document).ready(function () {
            $('body').addClass('m-brand--minimize m-aside-left--minimize');
            $('.input_child').focus();
            if (decimalsQuantity == 0){
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 0,
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
            } else {
                $(".quantity").TouchSpin({
                    initval: 1,
                    min: 0,
                    decimals: decimalsQuantity,
                    forcestepdivisibility: 'none',
                    buttondown_class: "btn btn-metal btn-sm",
                    buttonup_class: "btn btn-metal btn-sm"
                });
            }

        });
    </script>
    <script src="{{asset('static/backend/js/admin/order/html2canvas.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/jquery.printPage.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="list-tpl">
        <div class="info-box col-lg-3 m--margin-bottom-10"
             onclick="order.append_table({id},'{price_hidden}','{type}','{name}','{code}', '{is_surcharge}')">
            <div class="info-box-content ss--text-center">
                <span class="info-box-number ss--text-center">{price}@lang('đ')</span>
                <div class="info-box-number ss--text-center">
                    <img src="{img}" class="ss--image-pos">
                    <input type="hidden" class="type_hidden" name="type_hidden" value="{type}">
                </div>
                <span class="info-box-text ss--text-center">{name}</span>
            </div>
        </div>
    </script>
    <script type="text/template" id="table-tpl">
        <tr class="tr_table tr_table_{id} tr_table_{id}_{key_string}">
{{--            <td class="td_vtc" style="width: 30px;"></td>--}}
            <td>
                <div onclick="order.selectTopping('{id}','{key_string}')" class="hover-cursor">
                    {{--                    <img src="{{asset('static/backend/images/menu_add.png')}}" id="{code}_{id}" class="pr-1" ><label for="{code}_{id}">{{__('Món thêm')}}</label>--}}
                    <img src="{{asset('static/backend/images/menu_add.png')}}" id="{code}_{id}" class="pr-1" >
                </div>
            </td>
            <td class="td_vtc" style="color: #000; font-weight: 500;">
                <span><b>{name}</b></span>
                <div class="block"></div>
                <input type="hidden" name="key_string" value="{key_string}">
                <input type="hidden" name="product_id" value="{product_id}">
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" value="{code}">
            </td>

{{--            <td class="td_vtc" style="width: 110px;">--}}
{{--                <span class="text_price">{price}{{__('đ')}}</span>--}}
{{--                <input type="hidden" name="price" class="value_price" value="{price_hidden}">--}}
{{--            </td>--}}
            <td class="td_vtc text-center" style="width: 130px;">
                <span class="text_price">{price}{{__('đ')}}</span><br>
                <input type="hidden" name="price" class="value_price" value="{price_hidden}">
                <input style="text-align: center; height: 31px;font-size: 13px;" type="text" name="quantity"
                       class="quantity quantity_{id} form-control btn-ct-input" data-id="{stt}" value="1"
                       {{$customPrice == 1 ? 'disabled' : ''}} {isSurcharge}>
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center; width: 130px;">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
                @if (!isset($customPrice) || $customPrice == 0)
                    <a href="javascript:void(0)" id="discount_{stt}" class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}','{stt}')">
                        <i class="la la-plus icon-sz"></i>
                    </a>
                @endif
            </td>
            <td class=" td_vtc" style="text-align: center; width: 130px;">
                <span class="amount-tr">
                    @if (isset($customPrice) && $customPrice == 1)
                        <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{stt}"
                               value="{amount_hidden}">
                    @else
                        <div id="amount_surcharge_{stt}">
                        <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{stt}"
                               value="{amount_hidden}">
                    </div>
                        <div id="amount_not_surcharge_{stt}">
                        {amount}{{__('đ')}}
                        <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                               id="amount_{stt}" value="{amount_hidden}">
                    </div>
                    @endif
                </span>

                <input type="hidden" name="is_change_price" value="{is_change_price}">
                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
                <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
{{--            <td class="td_vtc" style="width: 50px;">--}}
{{--                <input type="hidden" name="is_change_price" value="{is_change_price}">--}}
{{--                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">--}}
{{--                <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i--}}
{{--                            class='la la-trash'></i></a>--}}
{{--            </td>--}}

            <input type="hidden" id="numberRow" value="{numberRow}">
        </tr>

    </script>

    <script type="text/template" id="table-product-tpl">
        <tr class="table_add">
            <td class="td_vtc" style="width: 30px;"></td>
            <td class="td_vtc" style="color: #000; font-weight: 500;">
                {name}
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" class="object_code_{id}" value="{code}">
            </td>
            <td class="td_vtc" style="width: 110px;">
                {price}đ
                <input type="hidden" name="price" value="{price_hidden}">
            </td>
            <td class="td_vtc" style="width:115px;">
                <input style="text-align: center;height: 30px; font-size: 13px;" type="text" name="quantity"
                       class="quantity quantity_add quantity_{id} form-control btn-ct-input" data-id="{stt}" value="1"
                       {{$customPrice == 1 ? 'disabled' : ''}} {isSurcharge}>
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center;width: 130px;">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
                @if (!isset($customPrice) || $customPrice == 0)
                    <a href="javascript:void(0)" id="discount_{stt}" 
                    class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" 
                    onclick="list.modal_discount_add('{amount_hidden}','{id}','{id_type}','{stt}')">
                        <i class="la la-plus icon-sz"></i>
                    </a>
                @endif
            </td>
            <td class="amount-tr td_vtc" style="text-align: center; width: 130px;">
                @if (isset($customPrice) && $customPrice == 1)
                    <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{stt}"
                           value="{amount_hidden}">
                @else
                    <div id="amount_surcharge_{stt}">
                        <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{stt}"
                               value="{amount_hidden}">
                    </div>
                    <div id="amount_not_surcharge_{stt}">
                        {amount}{{__('đ')}}
                        <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                               id="amount_{stt}" value="{amount_hidden}">
                    </div>
                @endif
            </td>
            <td style="width: 200px;">
                <select class="form-control staff" name="staff_id" style="width: 200px;" multiple="multiple">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
            <td class="td_vtc" style="width: 50px;">
                <input type="hidden" name="is_change_price" value="{is_change_price}">
                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
                <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
            <input type="hidden" id="numberRow_{numberRow}" class="numberRow" value="{numberRow}">
        </tr>
    </script>

    <script type="text/template" id="table-tpl-serial">
        <tr class="table_add">
            <td class="td_vtc" style="width: 30px;"></td>
            <td class="td_vtc" style="color: #000; font-weight: 500;">
                {name}
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" class="object_code_{id}" value="{code}">
            </td>
            <td class="td_vtc" style="width: 110px;">
                {price}đ
                <input type="hidden" name="price" value="{price_hidden}">
            </td>
            <td class="td_vtc" style="width:115px;">
                <input style="text-align: center;" type="text" name="quantity"
                       class="quantity quantity_add quantity_{id} form-control btn-ct-input" data-id="{stt}" value="1"
                       {{$customPrice == 1 ? 'disabled' : ''}} {isSurcharge}>
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center;width: 130px;">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
                @if (!isset($customPrice) || $customPrice == 0)
                    <a href="javascript:void(0)" id="discount_{stt}" 
                    class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" 
                    onclick="list.modal_discount_add('{amount_hidden}','{id}','{id_type}','{stt}')">
                        <i class="la la-plus icon-sz"></i>
                    </a>
                @endif
            </td>
            <td class="amount-tr td_vtc" style="text-align: center; width: 130px; vertical-align: middle;">
                @if (isset($customPrice) && $customPrice == 1)
                    <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{stt}"
                           value="{amount_hidden}">
                @else
                    <div id="amount_surcharge_{stt}">
                        <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{stt}"
                               value="{amount_hidden}">
                    </div>
                    <div id="amount_not_surcharge_{stt}">
                        {amount}{{__('đ')}}
                        <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                               id="amount_{stt}" value="{amount_hidden}">
                    </div>
                @endif
            </td>
            <td style="width: 150px; vertical-align: middle;">
                <select class="form-control staff" name="staff_id" style="width: 150px;" multiple="multiple">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
            <td class="td_vtc" style="width: 50px;">

                <input type="hidden" name="is_change_price" value="{is_change_price}">
                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
                <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
            <input type="hidden" id="numberRow_{numberRow}" class="numberRow" value="{numberRow}">
        </tr>
        <tr class="tr_table_child_{numberRow}">
            <td colspan="2">
                <select class="form-control input_child_{numberRow}" onkeydown="order.enterSerial(event,'{id}',{numberRow})">
                    <option value="">{{__('Nhập số serial và enter')}}</option>
                </select>
            </td>
            <td colspan="5" class="block_tr_child_{numberRow}"></td>
        </tr>
    </script>

    <script type="text/template" id="bill-tpl">
        <span class="total_bill">
            {total_bill_label}đ
        </span>
        <input type="hidden" id="total_bill" name="total_bill" class="form-control total_bill"
                        value="{total_bill}">
    </script>
    <script type="text/template" id="type-receipt-tpl">
        <div class="row">
            <label class="col-lg-6 font-13">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group-sm col-lg-6">
                <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
                       placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="{name_cash}" id="{id_cash}" value="0">
                <div class="input-group-append"><span class="input-group-text salary-unit-name" id="basic-addon1"> @lang("VNĐ")</span>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="active-tpl">
        <div class="m-checkbox-list">
            <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success sz_dt">
                <input type="checkbox" name="check_active" id="check_active" value="0"> @lang("Kích hoạt thẻ dịch vụ")
                <span></span>
            </label>
        </div>
    </script>
    <script type="text/template" id="price-card-cus-tpl">
        <label>@lang("Tiền giảm từ thẻ dịch vụ"):</label>
        <div class="input-group m-input-group">
            <input class="form-control m-input" placeholder="{{__('Nhập giá tiền')}}" aria-describedby="basic-addon1"
                   name="service_cash" id="service_cash" disabled="disabled" value="{price_card}">
        </div>

    </script>
    <script type="text/template" id="table-card-tpl">
        <tr class="tr_table">
            <td style="width: 30px;"></td>
            <td class="td_vtc" style="color: #000; font-weight: 500; min-width: 100px;">
                {name}
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" value="{code}">
            </td>
            <td class="td_vtc" style="width: 110px;">
                {price}{{__('đ')}} <input type="hidden" name="price" value="{price_hidden}">
            </td>
            <td class="td_vtc"  style="width:130px;">
                <input style="text-align: center; height: 31px;font-size: 13px;" type="text" name="quantity"
                       class="quantity_c form-control btn-ct-input">
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center; width: 130px;">
                0{{__('đ')}}
                <input type="hidden" name="discount" class="form-control discount" value="0">
                {{--                <input type="hidden" name="discount_causes" value="0">--}}
                <input type="hidden" name="voucher_code" value="">
                <a class="{class} m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary"
                   href="javascript:void(0)" onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}')">
                    <i class="la la-plus"></i>
                </a>
            </td>
            <td class="amount-tr td_vtc" style="text-align: center; width: 130px;">
                {amount}{{__('đ')}}
                <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                       value="{amount_hidden}">
            </td>
            <td style="width: 150px; vertical-align: middle;">
                <select class="form-control staff" name="staff_id"  multiple="multiple">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
            <td class="td_vtc" style="width: 50px;">
                <input type="hidden" name="is_change_price" value="0">
                <input type="hidden" name="is_check_promotion" value="0">
                <input type="hidden" id="numberRow_{numberRow}" class="numberRow" value="{numberRow}">
                <a class='remove_card' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="list-card-tpl">
        <div class="info-box col-lg-3 m--margin-bottom-10"
             onclick="order.append_table_card({id_card},'0','member_card','{card_name}','{quantity_app}','{card_code}',this)">
            <div class="info-box-content ss--text-center">
                <div class="m-widget4__item card_check_{id_card}">
                    <span class="m-widget4__sub m--font-bolder m--font-success quantity">
                        {quantity}(@lang("lần"))
                    </span>
                    <div class="m-widget4__img m-widget4__img--pic">
                        <img src="{img}" class="ss--image-pos">
                    </div>
                    <div class="m-widget4__info">
                        <span class="m-widget4__title"> {card_name} </span>
                    </div>
                    <input type="hidden" class="card_hide" value="{card_code}">
                    <input type="hidden" class="quantity_hide" name="quantity_hide" value="{quantity_app}">
                    <input type="hidden" class="quantity_card" value="{quantity_app}">
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="button-discount-tpl">
        <div class="m-form__actions m--align-right w-100">
            <button data-dismiss="modal"
                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
            </button>
            <button type="button" onclick="list.discount('{id}','{id_type}','{stt}')"
                    class="btn btn-primary color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
            </button>
        </div>
    </script>
    <script type="text/template" id="button-discount-add-tpl">
        <div class="m-form__actions m--align-right w=100">
            <button data-dismiss="modal"
                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
            </button>
            <button type="button" onclick="list.discount_add('{id}','{id_type}','{stt}')"
                    class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
            </button>
        </div>
    </script>
    <script type="text/template" id="button-discount-bill-tpl">
        <div class="m-form__actions m--align-right w-100">
            <button data-dismiss="modal"
                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
            </button>
            <button type="button" onclick="list.modal_discount_bill_click()"
                    class="btn btn-primary color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
            </button>
        </div>
    </script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/fnb/order/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/fnb/order/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/fnb/order/delivery.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/fnb/order/print-bill.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/send-sms-code-service-card.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/set-interval.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/fnb/order/receipt-online/vnpay.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/fnb/order/process-payment/process-payment.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $(document).ready(function (){
            order.chooseType('product');
            // order.fullScreen();

        })
    </script>
    <script type="text/template" id="list-promotion-tpl">
        <div class="info-box col-lg-3 m--margin-bottom-10"
             onclick="order.append_table({id},'{price_hidden}','{type}','{name}','{code}', '{is_surcharge}')">
            <div class="info-box-content ss--text-center">
                <span class="info-box-number ss--text-center"
                      style="text-decoration: line-through;">{price}{{__('đ')}}</span>
                <span class="info-box-number ss--text-center">{price_hidden}{{__('đ')}}</span>
                <div class="info-box-number ss--text-center">
                    <img src="{img}" class="ss--image-pos">
                    <input type="hidden" class="type_hidden" name="type_hidden" value="{type}">
                </div>
                <span class="info-box-text ss--text-center">{name}</span>
            </div>
        </div>
    </script>
    <script type="text/template" id="table-gift-tpl">
        <tr class="table_add promotion_gift">
            <td></td>
            <td class="td_vtc">
                {name}
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" value="{code}">
            </td>
            <td class="td_vtc">
                {price} {{__('đ')}}
                <input type="hidden" name="price" value="{price_hidden}">
            </td>
            <td class="td_vtc">
                <input style="text-align: center;" type="text" name="quantity" class="form-control btn-ct-input"
                       data-id="{stt}" value="{quantity}" disabled>
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
            </td>
            <td class="amount-tr td_vtc" style="text-align: center">
                {amount} @lang('đ')
                <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                       value="{amount_hidden}">
            </td>
            <td>
                <select class="form-control staff" name="staff_id" style="width:80%;" disabled multiple="multiple">
                    <option></option>
                    @foreach($staff_technician as $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
            <td class="td_vtc">
                <input type="hidden" name="is_change_price" value="0">
                <input type="hidden" name="is_check_promotion" value="0">
                <a class='remove' href="javascript:void(0)" onclick="order.removeGift(this)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="payment_method_tpl">
        <div class="row mt-3 method payment_method_{id}">
            <label class="col-lg-6 font-13">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
                       placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="payment_method" id="payment_method_{id}" value="0">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
                </div>
                <div class="input-group-append">
                    <button type="button" style="display: {style-display}" onclick="vnpay.createQrCode(this)" class="btn btn-primary m-btn m-btn--custom color_button">
                        @lang('TẠO QR')
                    </button>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="quick_appointment_tpl">
        <div class="form-group m-form__group row">
            <div class="form-group col-lg-6">
                <label class="black-title">{{__('Ngày hẹn')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group date_edit">
                    <div class="m-input-icon m-input-icon--right">
                        <input class="form-control m-input" name="date" id="date"
                               readonly placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                               onchange="customerAppointment.changeNumberTime()">
                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                        class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <span class="error_date_appointment" style="color: #ff0000"></span>
            </div>
            <div class="form-group col-lg-6">
                <label class="black-title">{{__('Giờ hẹn')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group m-input-group time_edit">
                    <input class="form-control" id="time" name="time" placeholder="{{__('Chọn giờ hẹn')}}"
                           onchange="customerAppointment.changeNumberTime()">
                </div>
                <span class="error_time_appointment" style="color: #ff0000"></span>
            </div>
        </div>
        <div class="m-section__content">
            <div class="m-scrollable m-scroller" data-scrollable="true"
                 style="height: 200px; overflow: auto;">
                <div class="table-responsive">
                    <table class="table m-table m-table--head-separator-metal" id="table_quantity">
                        <thead>
                        <tr>
                            <th class="th_modal_app" style="width: 10%">{{__('HÌNH THỨC')}}</th>
                            <th class="th_modal_app" style="width: 50%">{{__('DỊCH VỤ')}}</th>
                            <th class="th_modal_app"
                                style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('NHÂN VIÊN')}}</th>
                            <th class="th_modal_app"
                                style="width: 20%; {{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">{{__('PHÒNG')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="tr_quantity tr_service">
                            <td>
                                @lang('Dịch vụ')
                                <input type="hidden" name="customer_order" id="customer_order_1"
                                       value="1">
                                <input type="hidden" name="object_type" id="object_type"
                                       value="service">
                            </td>
                            <td>
                                <select class="form-control service_id" name="service_id"
                                        id="service_id_1"
                                        style="width: 100%" multiple="multiple">
                                    <option></option>
                                    @foreach($optionService as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                <select class="form-control staff_id" name="staff_id" id="staff_id_1"
                                        title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%">
                                    <option></option>
                                    @foreach($optionStaff as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                <select class="form-control room_id" name="room_id" id="room_id_1"
                                        title="{{__('Chọn phòng')}}" style="width: 100%">
                                    <option></option>
                                    @foreach($optionRoom as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr class="tr_quantity tr_card">
                            <td>
                                {{__('Thẻ liệu trình')}}
                                <input type="hidden" name="customer_order" id="customer_order_2" value="2">
                                <input type="hidden" name="object_type" id="object_type" value="member_card">
                            </td>
                            <td>
                                <select class="form-control customer_svc" name="service_id" id="service_id_2"
                                        style="width: 100%"
                                        multiple="multiple">
                                    <option></option>
                                </select>
                            </td>
                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                <select class="form-control staff_id" name="staff_id" id="staff_id_2"
                                        title="{{__('Chọn nhân viên phục vụ')}}"
                                        style="width: 100%">
                                    <option></option>
                                    @foreach($optionStaff as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                                <select class="form-control room_id" name="room_id" id="room_id_2"
                                        title="{{__('Chọn phòng')}}" style="width: 100%">
                                    <option></option>
                                    @foreach($optionRoom as $k => $v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <input type="hidden" id="time_type" value="R">
        <input type="hidden" id="type_number" name="type_number" value="1">
        <div class="m-separator m-separator--dashed m--margin-top-5"></div>
    </script>
    <script type="text/template" id="tab-category-tpl">
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link {active}" data-toggle="tab" href="javascript:void(0)"--}}
{{--               onclick="order.loadProduct('{category_id}')" data-name="{category_id}"--}}
{{--               data-name="all" style="text-transform: capitalize;">--}}
{{--                {category_name}--}}
{{--            </a>--}}
{{--        </li>--}}
        <div class="item">
            <a class="nav-link {active}" data-toggle="tab" href="javascript:void(0)"
               onclick="order.loadProduct('{category_id}')" data-name="{category_id}"
               data-name="all" style="text-transform: capitalize;">
                {category_name}
            </a>
        </div>
    </script>


@stop
