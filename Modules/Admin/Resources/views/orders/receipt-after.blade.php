@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> @lang("QUẢN LÝ ĐƠN HÀNG ")</span>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/pos-order.css')}}">
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

        .td_vtc {
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')
    @include('admin::orders.modal-discount')
    @include('admin::orders.modal-discount-bill')
    @include('admin::orders.receipt')
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
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-5">
                    @if($is_edit_full == 1)
                        <div class="form-group m-form__group row">
                            <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm tab-list m--margin-bottom-10 ul_type"
                                role="tablist" style="margin-bottom: 0px !important;">

                                @if(count($getTab) > 0)
                                    @foreach($getTab as $k => $v)
                                        <li class="nav-item m-tabs__item type">
                                            <a class="nav-link m-tabs__link {{$k == 0 ? 'active show': ''}}" data-toggle="tab"
                                               href="javascript:void(0)" onclick="order.chooseType('{{$v['code']}}')" role="tab"
                                               data-name="{{$v['code']}}">
                                                {{$v['tab_name']}}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif

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
                        <div class="m-separator m-separator--dashed"></div>
                        <div class="row">
                            <div class="col-lg-12" id="tab_category">
                                <ul class="nav nav-pills nav-pills--success ul_category" role="tablist">

                                </ul>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="m-input-icon m-input-icon--left">
                            <span class="">
                                    <input id="search" name="search" autocomplete="off" type="text"
                                           onkeyup="order.searchItem('search',event)"
                                           class="form-control m-input--pill m-input" value=""
                                           placeholder="@lang("Nhập thông tin tìm kiếm")">
                            </span>
                                <span class="m-input-icon__icon m-input-icon__icon--left"><span><i
                                                class="la la-search"></i></span></span>
                            </div>

                        </div>
                        <div id="list-product">
                            <div class="demo-index m-scrollable m-scroller ps ps--active-y" data-scrollbar-shown="true"
                                 data-scrollable="true" data-height="300" style="overflow:hidden; height: 300px">

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
                    <div class="col-lg-8">
                        @if ($item['customer_id'] != 1)
                            <div class="form-group row customer">
                                <div class="col-lg-3">
                                    @if($item['customer_avatar']!="")
                                        <img src="{{asset($item['customer_avatar'])}}" height="52px" width="52px">
                                    @else
                                        <img src="{{asset('static/backend/images/image-user.png')}}"
                                             style="width: 52px;height: 52px">
                                    @endif
                                </div>
                                <div class="col-lg-9">
                                     <span class="m-widget4__title m-font-uppercase">
                                         {{$item['full_name']}}
                                         <span class="m-badge m-badge--success vanglai" data-toggle="m-tooltip"
                                               data-placement="top" title=""
                                               data-original-title="{{__('Thành viên')}}"></span>
                                     </span>
                                    <br>
                                    <span class="m-widget4__title m-font-uppercase">
                                        <i class="flaticon-support m--margin-right-5"></i>
                                            {{$item['phone']}}
                                    </span>
                                    <br>
                                    <span class="m-widget4__title">
                                        @lang("Hạng")
                                            : {{$item['member_level_name'] != null ? $item['member_level_name'] : __('Thành Viên')}}
                                    </span><br>
                                    <span class="m-widget4__title">
                                        {{__('Công nợ')}}
                                            : {{number_format($debt, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <img src="{{asset('static/backend/images/image-user.png')}}"
                                     style="width: 52px;height: 52px">
                                <span class="m-widget4__title m-font-uppercase">{{$item['full_name']}}
                                        <span class="m-badge m-badge--success vanglai"
                                              data-toggle="m-tooltip" data-placement="top" title=""
                                              data-original-title="{{__('Khách mới')}}">
                                        </span>
                                    </span>
                            </div>
                        @endif
                    </div>
                    <div class="row bdb" style="padding: 0px !important;">
                        <div class="m-section__content col-lg-12">
                            <div class="row bdb" id="lstItemOrder">
                                <div class="table-responsive">
                                    <table class="table m-table m-table--head-bg-default" id="table_add">
                                        <thead class="bg">
                                        <tr>
                                            <td class="tr_thead_od m--font-bolder m--font-transform-u">#</td>
                                            <td class="tr_thead_od m--font-bolder m--font-transform-u">{{__('Tên')}}</td>
                                            <td class="tr_thead_od m--font-bolder m--font-transform-u">{{__('Giá')}}</td>
                                            <td class="tr_thead_quan m--font-bolder m--font-transform-u">{{__('Số lượng')}}
                                            </td>
                                            <td class="tr_thead_od text-center m--font-bolder m--font-transform-u">
                                                {{__('Giảm')}}
                                            </td>
                                            <td class="tr_thead_od text-center m--font-bolder m--font-transform-u">{{__('Thành tiền')}}
                                            </td>
                                            <td></td>
                                        </tr>
                                        </thead>
                                        <tbody class="tr_thead_od">
                                        <?php $tmpPosition = 0 ?>
                                        @foreach($order_detail as $key => $item1)
                                                <?php $tmpPosition = (int)$item1['order_detail_id'] + 1 ?>
                                            <tr class="tr_table {{in_array($item1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']) ? 'promotion_gift' : ''}}">
                                                <td class="td_vtc stt_length" style="width: 30px;">
                                                    {{$key+1}}
                                                </td>
                                                <td class="td_vtc" style="color: #000; font-weight: 500;width: 100px;">
                                                    {{$item1['object_name']}}
                                                    <input type="hidden" name="id_detail" id="id_detail"
                                                           value="{{$item1['order_detail_id']}}">
                                                    <input type="hidden" name="number_tr" value="{{$key+1}}">
                                                    <input type="hidden" name="id" value="{{$item1['object_id']}}">
                                                    <input type="hidden" name="name" value="{{$item1['object_name']}}">
                                                    <input type="hidden" name="object_type"
                                                           value="{{$item1['object_type']}}">
                                                    <input type="hidden" name="object_code"
                                                           class="object_code_{{$item1['object_id']}}"
                                                           value="{{$item1['object_code']}}">
                                                </td>
                                                <td class="td_vtc" style="width: 130px;">
                                                    <input class="form-control price" name="price" value="{{number_format($item1['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}" id="price_{{$key + 1}}">
                                                </td>
                                                <td class="td_vtc td_vtc_{{$key+1}}" style="width: 90px;">
                                                    @if(in_array($item1['object_type'], ['product', 'service', 'service_card']))
                                                        <input style="text-align: center;height: 31px;font-size: 13px;"
                                                               type="text" name="quantity"
                                                               {{$is_edit_full == 0 ? 'disabled' : ''}} class="quantity form-control btn-ct"
                                                               value="{{$item1['quantity']}}"
                                                               data-id="{{$key+1}}" {{$customPrice == 1 || $item1['is_change_price'] == 1 ? 'disabled' : ''}}>
                                                        <input type="hidden" name="quantity_hidden"
                                                               value="{{$item1['quantity']}}">
                                                    @elseif($item1['object_type'] == 'member_card')
                                                        <input style="text-align: center;height: 31px;font-size: 13px;"
                                                               type="text" name="quantity"
                                                               {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                               class="quantity_card form-control btn-ct-input"
                                                               value="{{$item1['quantity']}}" disabled>
                                                        <input type="hidden" name="quantity_hidden"
                                                               value="{{$item1['max_quantity_card']['number_using'] - $item1['max_quantity_card']['count_using']}}">
                                                    @elseif(in_array($item1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                                        <input style="text-align: center;height: 31px;font-size: 13px;"
                                                               type="text" name="quantity"
                                                               class="form-control btn-ct"
                                                               {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                               value="{{$item1['quantity']}}" data-id="{{$key+1}}"
                                                               disabled>
                                                        <input type="hidden" name="quantity_hidden"
                                                               value="{{$item1['quantity']}}">
                                                    @endif
                                                </td>
                                                <td class="discount-tr-{{$item1['object_type']}}-{{$key+1}} td_vtc"
                                                    style="text-align: center; width: 130px;">
                                                    @if($is_edit_full == 1)
                                                        @if($item1['object_type'] !='member_card' && $customPrice == 0 && $item1['is_change_price'] == 0)
                                                            @if($item1['discount']>0)
                                                                @if($item1['object_type']=='service')
                                                                    <a class="abc" href="javascript:void(0)"
                                                                       onclick="order.close_amount('{{$item1['object_id']}}','1','{{$key+1}}')">
                                                                        <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                    </a>
                                                                @elseif($item1['object_type']=='service_card')
                                                                    <a class="abc" href="javascript:void(0)"
                                                                       onclick="order.close_amount('{{$item1['object_id']}}','2','{{$key+1}}')">
                                                                        <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                    </a>
                                                                @elseif($item1['object_type'] =='product')
                                                                    <a class="abc" href="javascript:void(0)"
                                                                       onclick="order.close_amount('{{$item1['object_id']}}','3','{{$key+1}}')">
                                                                        <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                @if($item1['object_type']=='service')
                                                                    <a href="javascript:void(0)"
                                                                       class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                                                                       onclick="order.modal_discount('{{$item1['amount']}}','{{$item1['object_id']}}','1','{{$key+1}}')">
                                                                        <i class="la la-plus icon-sz"></i>
                                                                    </a>
                                                                @elseif($item1['object_type']=='service_card')
                                                                    <a href="javascript:void(0)"
                                                                       class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                                                                       onclick="order.modal_discount('{{$item1['amount']}}','{{$item1['object_id']}}','2','{{$key+1}}')">
                                                                        <i class="la la-plus icon-sz"></i>
                                                                    </a>
                                                                @elseif($item1['object_type'] =='product')
                                                                    <a href="javascript:void(0)"
                                                                       class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                                                                       onclick="order.modal_discount('{{$item1['amount']}}','{{$item1['object_id']}}','3','{{$key+1}}')">
                                                                        <i class="la la-plus icon-sz"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                    @if($item1['discount']>0)
                                                        {{number_format($item1['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                    @endif
                                                    <input type="hidden" name="discount" value="{{$item1['discount']}}">
                                                    <input type="hidden" name="voucher_code"
                                                           value="{{$item1['voucher_code']}}">

                                                </td>
                                                <td class="amount-tr td_vtc" style="text-align: center; width: 130px;">
                                                    @if (isset($customPrice) && $customPrice == 1 || $item1['is_change_price'] && in_array($item1['object_type'], ['service', 'product', 'service_card']))
                                                        <input name="amount" style="text-align: center;"
                                                               class="form-control amount" id="amount_{{$key+1}}"
                                                               value="{{number_format($item1['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                    @else
                                                        {{number_format($item1['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                        <input type="hidden" name="amount" value="{{$item1['amount']}}">
                                                    @endif

                                                </td>

                                                <td class="td_vtc" style="width: 50px;">
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
                                                <input type="hidden" id="numberRow_{{$item1['order_detail_id']}}"
                                                       class="numberRow" value="{{$item1['order_detail_id']}}">
                                                <input type="hidden" name="note" id="note_{{$key+1}}"
                                                       value="{{$item1['note']}}">
                                            </tr>

                                            <tr class="tr_note_child_{{ $key+1 }}" {{in_array($item1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']) ? 'promotion_note_child_gift' : ''}}>
                                                <td></td>
                                                <td colspan="{{$item1['inventory_management'] == 'serial' ? 1 : 3}}">
                                                    @if($item1['note'] != '')
                                                        <span id="note_text_{{ $key+1 }}">{{ $item1['note'] }}</span>
                                                    @else
                                                        <span id="note_text_{{ $key+1 }}"> @lang('Ghi chú/Dịch vụ thêm') ...</span>
                                                    @endif
                                                    <a href="javascript:void(0)"
                                                       onclick="order.showPopupAttach('{{ $key+1 }}', '{{ $item1['object_type'] }}', '{{ $item1['object_id'] }}', '{{ $item1['object_name'] }}', '{{ $item1['price'] }}')"
                                                       class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                                        <i class="la la-pencil"></i>
                                                    </a>
                                                </td>
                                                <td colspan="{{$item1['inventory_management'] == 'serial' ? 3 : 1}}">
                                                    @if($item1['inventory_management'] == 'serial')
                                                        <select class="form-control input_child input_child_{{$item1['order_detail_id']}}"
                                                                onfocusin="order.changeSelectSearch(`{{$item1['object_id']}}`,'{{$item1['object_code']}}',`{{$item1['order_detail_id']}}`)"
                                                                onkeydown="order.enterSerial(event,`{{$item1['object_id']}}`,`{{$item1['order_detail_id']}}`)">
                                                            <option value="">{{__('Nhập số serial và enter')}}</option>
                                                        </select>
                                                    @endif
                                                </td>
                                                <td colspan="2" class="td_staff">
                                                    <select class="form-control staff staff_{{$key+1}}" name="staff_id"
                                                            multiple="multiple" style="width: 80%"
                                                            {{ $is_edit_staff == 0 ? 'disabled' : '' }}
                                                            {{in_array($item1['object_type'], ['product_gift', 'service_gift', 'service_card_gift']) ? 'disabled':''}}>
                                                        <option></option>
                                                        @foreach($staff_technician as $k => $v)
                                                            <option value="{{$k}}" {{in_array($k, $item1['staff_id'])  ? 'selected' : '' }}>{{$v}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>

                                            @if(count($item1['attach']) > 0)
                                                @foreach($item1['attach'] as $v)
                                                    <tr class="tr_child_{{$key+1}}">
                                                        <td></td>
                                                        <td>
                                                            {{$v['object_name']}}
                                                            <input type="hidden" name="id_detail" id="id_detail"
                                                                   value="{{$v['order_detail_id']}}">
                                                            <input type="hidden" class="object_type" name="object_type"
                                                                   value="{{$v['object_type']}}">
                                                            <input type="hidden" class="object_id" name="object_id"
                                                                   value="{{$v['object_id']}}">
                                                            <input type="hidden" class="object_code" name="object_code"
                                                                   value="{{$v['object_code']}}">
                                                            <input type="hidden" class="object_name" name="object_name"
                                                                   value="{{$v['object_name']}}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control price_attach" name="price_attach"
                                                                   value="{{$v['price']}}"
                                                                   onchange="order.changePriceAttach()">
                                                        </td>
                                                        <td class="td_vtc"
                                                            style="width: 80px !important;text-align: center;">
                                                            {{$v['quantity']}}
                                                            <input type="hidden" class="quantity_child" name="quantity"
                                                                   value="{{$v['quantity']}}">
                                                        </td>
                                                        <td colspan="4"></td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            {{--                                            @if($item1['inventory_management'] == 'serial')--}}
                                            {{--                                                <tr class="tr_table_child_{{$item1['order_detail_id']}}">--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        <select class="form-control input_child input_child_{{$item1['order_detail_id']}}"--}}
                                            {{--                                                                onfocusin="order.changeSelectSearch(`{{$item1['object_id']}}`,'{{$item1['object_code']}}',`{{$item1['order_detail_id']}}`)"--}}
                                            {{--                                                                onkeydown="order.enterSerial(event,`{{$item1['object_id']}}`,`{{$item1['order_detail_id']}}`)">--}}
                                            {{--                                                            <option value="">{{__('Nhập số serial và enter')}}</option>--}}
                                            {{--                                                        </select>--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td colspan="6"--}}
                                            {{--                                                        class="block_tr_child_{{$item1['order_detail_id']}}">--}}
                                            {{--                                                        @if(isset($listSerialOrder[$item1['order_detail_id']]))--}}
                                            {{--                                                            @foreach($listSerialOrder[$item1['order_detail_id']] as $keySerial => $itemSerial)--}}
                                            {{--                                                                @if($keySerial < 4)--}}
                                            {{--                                                                    <span class="badge badge-pill badge-secondary">{{$itemSerial['serial']}} <i--}}
                                            {{--                                                                                class="fas fa-times pl-2 pr-2"--}}
                                            {{--                                                                                onclick="order.removeSerial('{{$session}}','{{$itemSerial['object_id']}}','{{$itemSerial['object_code']}}','{{$itemSerial['order_detail_id']}}','{{$itemSerial['serial']}}')"></i></span>--}}
                                            {{--                                                                @endif--}}
                                            {{--                                                            @endforeach--}}
                                            {{--                                                        @endif--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                    <td>--}}
                                            {{--                                                        @if(isset($listSerialOrder[$item1['order_detail_id']]) && count($listSerialOrder[$item1['order_detail_id']]) > 4)--}}
                                            {{--                                                            <a href="javascript:void(0)"--}}
                                            {{--                                                               onclick="order.showPopupSerial('{{$session}}','{{$item1['object_id']}}','{{$item1['object_code']}}','{{$item1['order_detail_id']}}')">{{__('Xem thêm')}}</a>--}}
                                            {{--                                                        @endif--}}
                                            {{--                                                    </td>--}}
                                            {{--                                                </tr>--}}
                                            {{--                                            @endif--}}
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <span class="error-table" style="color: #ff0000"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m--margin-left-5 m--margin-right-5"
                         style="padding-top: 15px !important; padding-bottom: 0px !important;">
                        <div class="col-lg-6 bdr">
                            <div class="form-group row m--margin-right-5">
                                <div class="m-list-timeline__items">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Tổng tiền')}}:</span>
                                        <span class="m-list-timeline__time m--font-boldest append_bill total_bill"
                                              style="width: 150px;">
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
                                        <span class="m-list-timeline__time m--font-boldest discount_bill"
                                              style="width: 150px;">
                                           @if($item['discount']>0)
                                                @if($is_edit_full == 1)
                                                    <a class="tag_a" href="javascript:void(0)"
                                                       onclick="order.close_discount_bill({{$item['total']}})">
                                                    <i class="la la-close cl_amount_bill"></i>
                                                </a>
                                                @endif
                                            @else
                                                @if($is_edit_full == 1)
                                                    <a href="javascript:void(0)"
                                                       {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                       onclick="order.modal_discount_bill({{$item['total']}})"
                                                       class="tag_a">
                                                <i class="fa fa-plus-circle icon-sz m--margin-right-5"
                                                   style="color: #0067AC;"></i>
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
                                            <input type="hidden" id="delivery_fee" name="delivery_fee"
                                                   value="{{$item['tranport_charge']}}">
                                            <input type="hidden" id="delivery_type" name="delivery_type"
                                                   value="{{$item['type_shipping']}}">
                                            <input type="hidden" id="delivery_cost_id" name="delivery_cost_id"
                                                   value="{{$item['delivery_cost_id']}}">
                                        </span>
                                    </div>

                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text m--font-boldest sz_word">@lang("Thành tiền")
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest amount_bill"
                                              style="color: red; width: 150px;">
                                             {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                            <input type="hidden" name="amount_bill_input"
                                                   class="form-control amount_bill_input"
                                                   value="{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row m--margin-right-5">
                                <select class="form-control"
                                        {{$is_edit_full == 0 ? 'disabled' : ''}}
                                        id="refer_id"
                                        name="refer_id" style="width:100%;">
                                    <option></option>
                                    @foreach($customer_refer as $key => $value)
                                        @if($key != 1)
                                            <option value="{{$key}}" {{$item['refer_id'] == $key ? 'selected':''}}>{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row m--margin-right-5">
                                <input type="text"
                                       {{$is_edit_full == 0 ? 'disabled' : ''}} class="form-control kt-quick-search__input"
                                       value="{{$item['order_description']}}" name="order_description"
                                       placeholder="Nhập ghi chú...">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="m-list-timeline__items">
                                <div class="m-list-timeline__item sz_bill">
                                    <span class="m-list-timeline__text sz_word m--font-boldest d-flex align-items-center">{{__('Giao hàng cho khách hàng')}}
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm ">
                                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                <input type="checkbox" class="manager-btn receipt_info_check" {{$item['receive_at_counter'] == 0 ? 'checked' : ''}}>
                                                <span></span>
                                            </label>
                                        </span>
                                    </span>
                                    <span class="m-list-timeline__time m--font-boldest icon-edit-delivery"
                                          style="{{$item['receive_at_counter'] != 0 ? 'display:none' : ''}}">
                                        <a href="javascript:void(0)" onclick="delivery.showPopup()"
                                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                            <i class="la la-edit"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="block-address receipt_info_check_block"
                                 style="{{$item['receive_at_counter'] != 0 ? 'display:none' : ''}}">
                                <div class="m-list-timeline__items pb-2 ">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Tên người nhận:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50">{{$detailAddress != null ? $detailAddress['customer_name'] : ''}}</span>
                                    </div>
                                </div>
                                <div class="m-list-timeline__items pb-2">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('SĐT người nhận:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50">{{$detailAddress != null ? $detailAddress['customer_phone'] : ''}}</span>
                                    </div>
                                </div>
                                <div class="m-list-timeline__items pb-2">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Địa chỉ nhận hàng:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50">{{$detailAddress != null ? $detailAddress['address'].','.$detailAddress['ward_name'].','.$detailAddress['district_name'].','.$detailAddress['province_name'] : ''}}</span>
                                    </div>
                                </div>
                                <div class="m-list-timeline__items pb-2">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Thời gian mong muốn nhận hàng:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50">{{$item['time_address'] != '' && $item['time_address'] != '0000-00-00' ? ($item['type_time'] == 'before' ? __('Trước') : ($item['type_time'] == 'in' ? __('Trong') : __('Sau'))).' '.\Carbon\Carbon::parse($item['time_address'])->format('d/m/Y') : ''}}</span>
                                    </div>
                                </div>
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
                    @if($item['order_source_id'] == 2 && $item['delivery_active'] == 0)
                        <div class="row m--margin-bottom-25">
                            <div class="col-lg-2">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input id="delivery_active" name="delivery_active" type="checkbox"
                                                {{$item['delivery_active'] == 1 ? 'checked' : ''}}>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                            <div class="col-lg-10 m--margin-top-5">
                                <span>@lang("Xác nhận đơn hàng")</span>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        @if($is_payment_order == 1)
                            <div class="form-group col-lg-4">
                                <button type="button" onclick="processPayment.editPaymentAction('{{$paymentType}}')"
                                        id="btn_order"
                                        class="btn btn-metal btn-lg m-btn son-mb m-btn m-btn--icon wd_type m-btn--wide m-btn--md"
                                        style="background-color: #FF794E">
                                    {{__('THANH TOÁN')}}
                                </button>
                            </div>
                        @endif
                        @if($is_update_order == 1)
                            <div class="form-group col-lg-4">
                                <button type="submit" id="btn_add" onclick="save.submit_edit('{{$item['order_id']}}')"
                                        class="btn btn-success btn-lg m-btn son-mb m-btn m-btn--icon wd_type
                                m-btn--wide m-btn--md btn-add">
                                    {{__('LƯU THÔNG TIN')}}
                                </button>
                            </div>
                        @endif
                        <div class="form-group col-lg-4">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <a href="{{route('admin.order')}}"
                                       class="btn btn-metal btn-lg m-btn m-btn--icon m-btn--wide m-btn--md wd_type">
                                        {{__('HỦY')}}
                                    </a>
                                </div>

                            </div>
                        </div>
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
                        <a href="{{route('admin.order')}}"
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
    <form id="form-order-ss" target="_blank" action="{{route('admin.order.print-bill2')}}" method="GET">
        <input type="hidden" name="ptintorderid" id="orderiddd" value="">
    </form>
    <input type="hidden" value="{{$orderIdsss}}" class="hiddenOrderIdss">
    <input type="hidden" id="order_source_id" value="{{$item['order_source_id']}}">
    <input type="hidden" id="custom_price" name="custom_price" value="{{$customPrice}}">
    <input type="hidden" id="session" value="{{$session}}">
    <div id="showPopup"></div>

    <input type="hidden" id="type_time_hidden" name="type_time_hidden" value="{{$item['type_time']}}">
    <input type="hidden" id="time_address_hidden" name="time_address_hidden"
           value="{{$item['time_address'] != '' && $item['time_address'] != '0000-00-00' ? $item['time_address'] : ''}}">
    <input type="hidden" id="customer_contact_id_hidden" name="customer_contact_id_hidden"
           value="{{$item['customer_contact_id']}}">
    <div class="popupShow"></div>

    <form id="form-customer-debt" target="_blank" action="{{route('admin.customer.print-bill-debt')}}" method="GET">
        <input type="hidden" name="customer_id" id="customer_id_bill_debt">
    </form>
@endsection
@section('after_script')
    @if ($item['tranport_charge'] != 0 && $item['type_shipping'] == 0)
        <script>
            $(document).ready(function () {
                $('.delivery_type_0').trigger('click');
            })
        </script>
    @endif
    @if ($item['tranport_charge'] != 0 && $item['type_shipping'] == 1)
        <script>
            $(document).ready(function () {
                $('.delivery_type_1').trigger('click');
            })
        </script>
    @endif
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
        <tr class="tr_table">
            <td class="td_vtc stt_length" style="width: 30px;"></td>
            <td class="td_vtc" style="color: #000; font-weight: 500;width: 100px;">
                {name}
                <input type="hidden" name="number_tr" value="{stt}">
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" value="{code}">
            </td>
            <td class="td_vtc" style="width: 130px;">
                <input class="form-control price" name="price" value="{price}" id="price_{stt}">
            </td>
            <td class="td_vtc" style="width: 90px;">
                <input style="text-align: center; height: 31px;font-size: 13px;" type="text" name="quantity"
                       class="quantity quantity_{id} form-control btn-ct-input" data-id="{stt}" value="1"
                       {{$customPrice == 1 ? 'disabled' : ''}} {isSurcharge}>
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center; width: 130px;">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
                @if (!isset($customPrice) || $customPrice == 0)
                    <a href="javascript:void(0)" id="discount_{stt}"
                       class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                       onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}','{stt}')">
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
            <td class="td_vtc" style="width: 50px;">
                <input type="hidden" name="is_change_price" value="{is_change_price}">
                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
                <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>

            <input type="hidden" id="numberRow" value="{numberRow}">
            <input type="hidden" name="note" id="note_{stt}">
        </tr>

        <tr class="tr_note_child_{stt}">
            <td></td>
            <td colspan="3">
                <span id="note_text_{stt}">@lang('Ghi chú/Dịch vụ thêm')...</span>
                <a href="javascript:void(0)"
                   onclick="order.showPopupAttach('{stt}', '{type_hidden}', '{id}', '{name}', '{price}')"
                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                    <i class="la la-pencil"></i>
                </a>
            </td>
            <td></td>
            <td colspan="2" class="td_staff">
                <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple" style="width: 80%">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    </script>

    <script type="text/template" id="table-product-tpl">
        <tr class="tr_table">
            <td class="td_vtc stt_length" style="width: 30px;"></td>
            <td class="td_vtc" style="color: #000; font-weight: 500; width: 100px;">
                {name}
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" class="object_code_{id}" value="{code}">
            </td>
            <td class="td_vtc" style="width: 130px;">
                <input class="form-control price" name="price" value="{price}" id="price_{stt}">
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
                       onclick="order.modal_discount_add('{amount_hidden}','{id}','{id_type}','{stt}')">
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
            <td class="td_vtc" style="width: 50px;">
                <input type="hidden" name="is_change_price" value="{is_change_price}">
                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
                <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
            <input type="hidden" id="numberRow_{numberRow}" class="numberRow" value="{numberRow}">
            <input type="hidden" name="note" id="note_{stt}">
        </tr>

        <tr class="tr_note_child_{stt}">
            <td></td>
            <td colspan="3">
                <span id="note_text_{stt}">@lang('Ghi chú/Dịch vụ thêm')...</span>
                <a href="javascript:void(0)"
                   onclick="order.showPopupAttach('{stt}', '{type_hidden}', '{id}', '{name}', '{price}')"
                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                    <i class="la la-pencil"></i>
                </a>
            </td>
            <td></td>
            <td colspan="2" class="td_staff">
                <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple" style="width: 80%">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    </script>

    <script type="text/template" id="table-tpl-serial">
        <tr class="tr_table">
            <td class="td_vtc stt_length" style="width: 30px;"></td>
            <td class="td_vtc" style="color: #000; font-weight: 500; width: 100px;">
                {name}
                <input type="hidden" name="number_tr" value="{stt}">
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" class="object_code_{id}" value="{code}">
            </td>
            <td class="td_vtc" style="width: 130px;">
                <input class="form-control price" name="price" value="{price}" id="price_{stt}">
            </td>
            <td class="td_vtc td_vtc_{numberRow}" style="width:115px;">
                <input style="text-align: center;height: 30px; font-size: 13px;" type="text" name="quantity"
                       class="quantity quantity_{id} form-control btn-ct-input" data-id="{stt}" value="0"
                       {{$customPrice == 1 ? 'disabled' : ''}} {isSurcharge}>
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{stt} td_vtc" style="text-align: center;width: 130px;">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
                @if (!isset($customPrice) || $customPrice == 0)
                    <a href="javascript:void(0)" id="discount_{stt}"
                       class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                       onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}','{stt}')">
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
                        0{{__('đ')}}
                        <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                               id="amount_{stt}" value="0">
                    </div>
                @endif
            </td>
            <td class="td_vtc" style="width: 50px;">
                <input type="hidden" name="is_change_price" value="{is_change_price}">
                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
                <a class='remove' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
            <input type="hidden" id="numberRow_{numberRow}" class="numberRow" value="{numberRow}">
            <input type="hidden" name="note" id="note_{stt}">
        </tr>

        <tr class="tr_note_child_{numberRow}">
            <td></td>
            <td>
                <span id="note_text_{stt}">@lang('Ghi chú/Dịch vụ thêm')...</span>
                <a href="javascript:void(0)"
                   onclick="order.showPopupAttach('{stt}', '{type_hidden}', '{id}', '{name}', '{price}')"
                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                    <i class="la la-pencil"></i>
                </a>
            </td>
            <td colspan="3">
                <select class="form-control input_child_{numberRow}"
                        onkeydown="order.enterSerial(event,'{id}',{numberRow})">--}}
                    <option value="">{{__('Nhập số serial và enter')}}</option>
                </select>
            </td>
            <td colspan="2" class="td_staff">
                <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple" style="width: 80%">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
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
                <div class="input-group-append"><span class="input-group-text salary-unit-name"
                                                      id="basic-addon1"> @lang("VNĐ")</span>
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
            <td class="stt_length" style="width: 30px;"></td>
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
            <td class="td_vtc" style="width:130px;">
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
    <script type="text/template" id="close-discount-bill">
        <a href="javascript:void(0)" onclick="order.close_discount_bill('{close_discount_hidden}')"
           class="tag_a">
            <i class="la la-close cl_amount_bill"></i>
        </a>
        {discount}{{__('đ')}}
        <input type="hidden" id="discount_bill" name="discount_bill" value="{discount_hidden}">
        <input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="{code_bill}">

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
            <button type="button" onclick="order.discount('{id}','{id_type}','{stt}')"
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
            <button type="button" onclick="order.discount_add('{id}','{id_type}','{stt}')"
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
            <button type="button" onclick="order.modal_discount_bill_click()"
                    class="btn btn-primary color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
            </button>
        </div>
    </script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
        var decimalsQuantity = {{ config()->get('config.decimal_quantity') }};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/order/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/delivery.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/send-sms-code-service-card.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/set-interval.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/receipt-online/vnpay.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/process-payment/process-payment.js?v='.time())}}"
            type="text/javascript"></script>
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
        <tr class="tr_table promotion_gift">
            <td class="td_vtc stt_length" style="width: 30px;"></td>
            <td class="td_vtc">
                {name}
                <input type="hidden" name="number_tr" value="{stt}">
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
            <td class="td_vtc">
                <input type="hidden" name="is_change_price" value="0">
                <input type="hidden" name="is_check_promotion" value="0">
                <a class='remove' href="javascript:void(0)" onclick="order.removeGift(this)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>

            <input type="hidden" name="numberRow" id="numberRow" value="{numberRow}">
            <input type="hidden" name="note" id="note_{stt}">
        </tr>

        <tr class="tr_note_child_{stt} promotion_note_child_gift">
            <td></td>
            <td colspan="2">
                <span id="note_text_{stt}">@lang('Ghi chú/Dịch vụ thêm')...</span>
                <a href="javascript:void(0)" onclick="order.showPopupAttach('{stt}', '{type_hidden}', '{id}', '{name}', '{price}')"
                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                    <i class="la la-pencil"></i>
                </a>
            </td>
            <td></td>
            <td colspan="2" class="td_staff">
                <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple" style="width: 80%">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
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
                    <button type="button" style="display: {style-display}" onclick="vnpay.createQrCode(this)"
                            class="btn btn-primary m-btn m-btn--custom color_button">
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
        <li class="nav-item">
            <a class="nav-link {active}" data-toggle="tab" href="javascript:void(0)"
               onclick="order.loadProduct('{category_id}')" data-name="{category_id}"
               data-name="all" style="text-transform: capitalize;">
                {category_name}
            </a>
        </li>
    </script>
    <script type="text/template" id="table-child-tpl">
        <tr class="tr_child_{stt}">
            <td></td>
            <td style="color: #000; vertical-align: middle;">
                {object_name}

                <input type="hidden" class="object_type" name="object_type" value="{object_type}">
                <input type="hidden" class="object_id" name="object_id" value="{object_id}">
                <input type="hidden" class="object_code" name="object_code" value="{object_code}">
                <input type="hidden" class="object_name" name="object_name" value="{object_name}">
            </td>
            <td>
                <input class="form-control price_attach" name="price_attach" value="{price}"
                       onchange="order.changePriceAttach()">
            </td>
            <td class="td_vtc" style="width: 80px !important;text-align: center;">
                {quantity}
                <input type="hidden" class="quantity_child" name="quantity" value="{quantity}">
            </td>
            <td colspan="3"></td>
        </tr>
    </script>

    <script>
        var customPrice = {{$customPrice}};
        let numberRow = {{$tmpPosition}};
        stt_tr = {{count($order_detail)}}

        $(document).ready(function () {
            $('body').addClass('m-brand--minimize m-aside-left--minimize');
            $('.input_child').focus();

            @if(count($getTab) > 0)
                order.chooseType('{{$getTab[0]['code']}}');
            @endif
        });
    </script>
@stop
