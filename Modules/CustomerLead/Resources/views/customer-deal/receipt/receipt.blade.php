@extends('layout')
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/pos-order.css')}}">
@endsection

@section('content')
    @include('customer-lead::customer-deal.receipt.modal-discount-ap')
    @include('customer-lead::customer-deal.receipt.modal-discount-bill-ap')
    @include('customer-lead::customer-deal.receipt.modal-receipt')

    <div class="m-portlet m-portlet--head-sm min-height-100" id="m-order-add">
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
                    <div class="form-group m-form__group m--margin-top-10 row">
                        <div class="col-lg-12 bdr">
                            <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm tab-list m--margin-bottom-10 ul_type"
                                role="tablist" style="margin-bottom: 0px !important;">

                                @if(count($getTab) > 0)
                                    @foreach($getTab as $k => $v)
                                        <li class="nav-item m-tabs__item type">
                                            <a class="nav-link m-tabs__link {{$k == 0 ? 'active show': ''}}"
                                               data-toggle="tab"
                                               href="javascript:void(0)" onclick="order.chooseType('{{$v['code']}}')"
                                               role="tab"
                                               data-name="{{$v['code']}}">
                                                {{$v['tab_name']}}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            <div class="m-separator m-separator--dashed"></div>
                            <div class="row">
                                <div class="col-lg-12" id="tab_category">
                                    <ul class="nav nav-pills nav-pills--success ul_category" role="tablist">

                                    </ul>
                                </div>
                            </div>

                            <div class="form-group m-form__group ">
                                <div class="m-input-icon m-input-icon--left">
                                    <span class="">
                                            <input id="search" name="search" autocomplete="off" type="text"
                                                   class="form-control m-input--pill m-input" value=""
                                                   onkeyup="search('search',event)"
                                                   placeholder="{{__('Nhập thông tin tìm kiếm')}}">

                                            <input id="search_product" name="search_product"
                                                   onkeyup="search('search_product',event)" style="display:none"
                                                   autocomplete="off" type="text"
                                                   class="form-control m-input--pill m-input" value=""
                                                   placeholder="{{__('TÌm kiếm sản phẩm theo Tên, Mã sản phẩm, Số serial, SKU, Barcode')}}">
                                    </span>
                                    <span class="m-input-icon__icon m-input-icon__icon--left"><span><i
                                                    class="la la-search"></i></span></span>
                                </div>
                            </div>
                            <div id="list-product">
                                <div class="demo-index m-scrollable m-scroller ps ps--active-y"
                                     data-scrollbar-shown="true" data-scrollable="true" data-height="300"
                                     style="overflow:hidden; height: 300px">

                                </div>
                            </div>
                            <input type="hidden" value="" id="category_id_hidden">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row customer">
                        <div class="col-lg-8">
                            <input type="hidden" name="order_id" id="order_id" value="">
                            <input type="hidden" name="order_code" id="order_code" value="">
                            <input type="hidden" name="deal_id" id="deal_id" value="{{$item['deal_id']}}">
                            <input type="hidden" name="deal_code" id="deal_code" value="{{$item['deal_code']}}">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{$item['customer_id']}}">

                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <img src="{{$item['customer_avatar'] != null ? $item['customer_avatar'] : asset('static/backend/images/image-user.png')}}"
                                         style="width: 52px;height: 52px">
                                </div>
                                <div class="col-lg-9">
                                         <span class="m-widget4__title m-font-uppercase"> {{$item['customer_full_name']}}
                                             <span class="m-badge m-badge--success vanglai" data-toggle="m-tooltip"
                                                   title="" data-original-title="{{__('Thành viên')}}">
                                             </span>
                                         </span>
                                    <br>
                                    <span class="m-widget4__title m-font-uppercase">
                                        <i class="flaticon-support m--margin-right-5"></i> {{$item['customer_phone']}}</span>
                                    <br>
                                    <span class="m-widget4__title">
                                        {{__('Hạng')}}: {{$item['member_level_name']}}
                                        @switch($item['member_level_id'])
                                            @case(1)
                                                <i class="fa flaticon-presentation icon_color"></i>
                                                @break;
                                            @case(2)
                                                <i class="fa flaticon-confetti icon_color"></i>
                                                @break;
                                            @case(3)
                                                <i class="fa flaticon-medal icon_color"></i>
                                                @break;
                                            @case(4)
                                                <i class="fa flaticon-customer icon_color"></i>
                                                @break;
                                        @endswitch
                                        </span> <br>
                                    <span class="m-widget4__title">
                                        {{__('Công nợ')}}: {{number_format($debt, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                @if(count($dataDetail) > 0)
                                    @foreach($dataDetail as $k => $v)
                                        <tr class="tr_table {{in_array($v['object_type'], ['product_gift', 'service_gift', 'service_card_gift']) ? 'promotion_gift' : ''}}">
                                            <td class="td_vtc stt_length" style="width: 30px;">
                                                {{$k+1}}
                                            </td>
                                            <td class="td_vtc" style="color: #000; font-weight: 500;width: 100px;">
                                                {{$v['object_name']}}
                                                <input type="hidden" name="number_tr" value="{{$k+1}}">
                                                <input type="hidden" name="id" value="{{$v['object_id']}}">
                                                <input type="hidden" name="name" value="{{$v['object_name']}}">
                                                <input type="hidden" name="object_type" value="{{$v['object_type']}}">
                                                <input type="hidden" name="object_code" class="object_code_{{$v['object_id']}}" value="{{$v['object_code']}}">
                                            </td>
                                            <td class="td_vtc" style="width: 130px;">
                                                <input class="form-control price" name="price"
                                                       value="{{number_format($v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"
                                                       id="price_{{$k + 1}}">
                                            </td>
                                            <td class="td_vtc td_vtc_{{$k+1}}" style="width: 90px;">
                                                @if(in_array($v['object_type'], ['product', 'service', 'service_card']))
                                                    <input style="text-align: center;height: 31px;font-size: 13px;"
                                                           type="text" name="quantity"
                                                           {{$is_edit_full == 0 ? 'disabled' : ''}} class="quantity form-control btn-ct"
                                                           value="{{$v['quantity']}}"
                                                           data-id="{{$k+1}}" {{$customPrice == 1 || $v['is_change_price'] == 1 ? 'disabled' : ''}}>
                                                    <input type="hidden" name="quantity_hidden" value="{{$v['quantity']}}">
                                                @elseif($v['object_type'] == 'member_card')
                                                    <input style="text-align: center;height: 31px;font-size: 13px;"
                                                           type="text" name="quantity"
                                                           {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                           class="quantity_card form-control btn-ct-input"
                                                           value="{{$v['quantity']}}" disabled>
                                                    <input type="hidden" name="quantity_hidden"
                                                           value="{{$v['max_quantity_card']['number_using'] - $v['max_quantity_card']['count_using']}}">
                                                @elseif(in_array($v['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                                    <input style="text-align: center;height: 31px;font-size: 13px;"
                                                           type="text" name="quantity"
                                                           class="form-control btn-ct"
                                                           {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                           value="{{$v['quantity']}}" data-id="{{$k+1}}" disabled>
                                                    <input type="hidden" name="quantity_hidden" value="{{$v['quantity']}}">
                                                @endif
                                            </td>
                                            <td class="discount-tr-{{$v['object_type']}}-{{$k+1}} td_vtc"
                                                style="text-align: center; width: 130px;">
                                                @if($is_edit_full == 1)
                                                    @if($v['object_type'] !='member_card' && $customPrice == 0 && $v['is_change_price'] == 0)
                                                        @if($v['discount']>0)
                                                            @if($v['object_type']=='service')
                                                                <a class="abc" href="javascript:void(0)"
                                                                   onclick="order.close_amount('{{$v['object_id']}}','1','{{$k+1}}')">
                                                                    <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                </a>
                                                            @elseif($v['object_type']=='service_card')
                                                                <a class="abc" href="javascript:void(0)"
                                                                   onclick="order.close_amount('{{$v['object_id']}}','2','{{$k+1}}')">
                                                                    <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                </a>
                                                            @elseif($v['object_type'] =='product')
                                                                <a class="abc" href="javascript:void(0)"
                                                                   onclick="order.close_amount('{{$v['object_id']}}','3','{{$k+1}}')">
                                                                    <i class="la la-close cl_amount m--margin-right-5"></i>
                                                                </a>
                                                            @endif
                                                        @else
                                                            @if($v['object_type']=='service')
                                                                <a href="javascript:void(0)"
                                                                   class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                                                                   onclick="order.modal_discount('{{$v['amount']}}','{{$v['object_id']}}','1','{{$k+1}}')">
                                                                    <i class="la la-plus icon-sz"></i>
                                                                </a>
                                                            @elseif($v['object_type']=='service_card')
                                                                <a href="javascript:void(0)"
                                                                   class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                                                                   onclick="order.modal_discount('{{$v['amount']}}','{{$v['object_id']}}','2','{{$k+1}}')">
                                                                    <i class="la la-plus icon-sz"></i>
                                                                </a>
                                                            @elseif($v['object_type'] =='product')
                                                                <a href="javascript:void(0)"
                                                                   class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only"
                                                                   onclick="order.modal_discount('{{$v['amount']}}','{{$v['object_id']}}','3','{{$k+1}}')">
                                                                    <i class="la la-plus icon-sz"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                                @if($v['discount']>0)
                                                    {{number_format($v['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                @endif
                                                <input type="hidden" name="discount" value="{{$v['discount']}}">
                                                <input type="hidden" name="voucher_code" value="{{$v['voucher_code']}}">
                                            </td>
                                            <td class="amount-tr td_vtc" style="text-align: center; width: 130px;">
                                                @if (isset($customPrice) && $customPrice == 1 || $v['is_change_price'] && in_array($v['object_type'], ['service', 'product', 'service_card']))
                                                    <input name="amount" style="text-align: center;"
                                                           class="form-control amount" id="amount_{{$k+1}}"
                                                           value="{{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                @else
                                                    {{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                    <input type="hidden" name="amount" value="{{$v['amount']}}">
                                                @endif

                                            </td>

                                            <td class="td_vtc" style="width: 50px;">
                                                <input type="hidden" name="is_change_price" value="{{$v['is_change_price']}}">
                                                <input type="hidden" name="is_check_promotion" value="{{$v['is_check_promotion']}}">
                                                @if($is_edit_full == 1)
                                                    @if(in_array($v['object_type'], ['product', 'service', 'service_card']))
                                                        <a class="remove"
                                                           {{$is_edit_full == 0 ? 'hidden' : ''}} href="javascript:void(0)"
                                                           style="color: #a1a1a1"><i class="la la-trash"></i></a>
                                                    @elseif($v['object_type'] == 'member_card')
                                                        <a class="remove_card"
                                                           {{$is_edit_full == 0 ? 'hidden' : ''}} href="javascript:void(0)"
                                                           style="color: #a1a1a1"><i class="la la-trash"></i></a>
                                                    @elseif(in_array($v['object_type'], ['product_gift', 'service_gift', 'service_card_gift']))
                                                        <a href="javascript:void(0)"
                                                           {{$is_edit_full == 0 ? 'hidden' : ''}} onclick="order.removeGift(this)"
                                                           style="color: #a1a1a1"><i class="la la-trash"></i></a>
                                                    @endif
                                                @endif
                                            </td>
                                            <input type="hidden" id="numberRow_{{$v['order_detail_id']}}" class="numberRow" value="{{$v['order_detail_id']}}">
                                            <input type="hidden" name="note" id="note_{{$k+1}}" value="{{$v['note']}}">
                                        </tr>

                                        <tr class="tr_note_child_{{ $k+1 }}" {{in_array($v['object_type'], ['product_gift', 'service_gift', 'service_card_gift']) ? 'promotion_note_child_gift' : ''}}>
                                            <td></td>
                                            <td colspan="{{$v['inventory_management'] == 'serial' ? 1 : 3}}">
                                                @if($v['note'] != '')
                                                    <span id="note_text_{{ $k+1 }}">{{ $v['note'] }}</span>
                                                @else
                                                    <span id="note_text_{{ $k+1 }}"> @lang('Ghi chú/Dịch vụ thêm') ...</span>
                                                @endif
                                                <a href="javascript:void(0)"
                                                   onclick="order.showPopupAttach('{{ $k+1 }}', '{{ $v['object_type'] }}', '{{ $v['object_id'] }}', '{{ $v['object_name'] }}', '{{ $v['price'] }}')"
                                                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                                    <i class="la la-pencil"></i>
                                                </a>
                                            </td>
                                            <td colspan="{{$v['inventory_management'] == 'serial' ? 3 : 1}}">
                                                @if($v['inventory_management'] == 'serial')
                                                    <select class="form-control input_child input_child_{{$v['order_detail_id']}}"
                                                            onfocusin="order.changeSelectSearch(`{{$v['object_id']}}`,'{{$v['object_code']}}',`{{$v['order_detail_id']}}`)"
                                                            onkeydown="order.enterSerial(event,`{{$v['object_id']}}`,`{{$v['order_detail_id']}}`)">
                                                        <option value="">{{__('Nhập số serial và enter')}}</option>
                                                    </select>
                                                @endif
                                            </td>
                                            <td colspan="2" class="td_staff">
                                                <select class="form-control staff staff_{{$k+1}}" name="staff_id"
                                                        multiple="multiple" style="width: 80%"
                                                        {{ $is_edit_staff == 0 ? 'disabled' : '' }}
                                                        {{in_array($v['object_type'], ['product_gift', 'service_gift', 'service_card_gift']) ? 'disabled':''}}>
                                                    <option></option>
                                                    @foreach($optionStaff as $key => $value)
                                                        <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <span class="error-table" style="color: #ff0000"></span>
                    </div>

                    <div class="form-group row m--margin-left-5 m--margin-right-5"
                         style="padding-top: 15px !important; padding-bottom: 0px !important;">
                        <div class="col-lg-6 bdr">
                            <div class="form-group row m--margin-right-5">
                                <div class="m-list-timeline__items">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Tổng tiền')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest append_bill"
                                              style="width: 150px;">
                                            {{__('0đ')}}
                                        </span>
                                        <input type="hidden" id="total_bill" name="total_bill"
                                               class="form-control total_bill" value="0">
                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Chiết khấu thành viên')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest" style="width: 150px;">
                                            <span class="span_member_level_discount">0</span> {{__('đ')}}
                                        </span>
                                        <input type="hidden" name="member_level_discount" id="member_level_discount"
                                               class="form-control" value="0">

                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text m--font-boldest sz_word">{{__('Giảm giá')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest discount_bill"
                                              style="width: 150px;">
                                            <a href="javascript:void(0)" onclick="order.modal_discount_bill(0)"
                                               class="tag_a">
                                            <i class="fa fa-plus-circle icon-sz" style="color: #0067AC "></i>
                                            </a>
                                            0 @lang('đ')
                                            <input type="hidden" id="discount_bill" name="discount_bill" value="0">
                                            <input type="hidden" id="voucher_code_bill" name="voucher_code_bill"
                                                   value="">

                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text m--font-boldest sz_word">{{__('Phí vận chuyển')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest" style="width: 150px;">
                                            <a href="javascript:void(0)"
                                               class="tag_a">
                                            </a>
                                            <span class="delivery_fee_text">0</span> @lang('đ')
                                            <input type="hidden" id="delivery_fee" name="delivery_fee" value="0">
                                            <input type="hidden" id="delivery_type" name="delivery_type" value="">
                                            <input type="hidden" id="delivery_cost_id" name="delivery_cost_id" value="">
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text m--font-boldest sz_word">{{__('Thành tiền')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest amount_bill"
                                              style="color: red; width: 150px;">
                                            0 {{__('đ')}}
                                            <input type="hidden" name="amount_bill_input"
                                                   class="form-control amount_bill_input"
                                                   value="0">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row m--margin-right-5">
                                <select class="form-control select-fix" id="refer_id" name="refer_id">
                                    <option></option>
                                    @foreach($optionCustomer as $key => $value)
                                        @if($value['customer_id'] != 1)
                                            <option value="{{$value['customer_id']}}">{{$value['full_name']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row m--margin-right-5">
                                <input type="text" class="form-control kt-quick-search__input" name="order_description"
                                       placeholder="Nhập ghi chú...">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="m-list-timeline__items">
                                <div class="m-list-timeline__item sz_bill">
                                    <span class="m-list-timeline__text sz_word m--font-boldest d-flex align-items-center">
                                        {{__('Giao hàng cho khách hàng')}}
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm ">
                                            <label style="margin: 0 0 0 10px;">
                                                <input type="checkbox" class="manager-btn receipt_info_check">
                                                <span></span>
                                            </label>
                                        </span>
                                    </span>
                                    <span class="m-list-timeline__time m--font-boldest icon-edit-delivery"
                                          style="display:none">
                                        <a href="javascript:void(0)" onclick="delivery.showPopup()"
                                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                            <i class="la la-edit"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="block-address receipt_info_check_block" style="display:none">
                                <div class="m-list-timeline__items pb-2 ">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Tên người nhận:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50"></span>
                                    </div>
                                </div>
                                <div class="m-list-timeline__items pb-2">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('SĐT người nhận:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50"></span>
                                    </div>
                                </div>
                                <div class="m-list-timeline__items pb-2">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Địa chỉ nhận hàng:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50"></span>
                                    </div>
                                </div>
                                <div class="m-list-timeline__items pb-2">
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest w-50">{{__('Thời gian mong muốn nhận hàng:')}}</span>
                                        <span class="m-list-timeline__time m--font-boldest w-50"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        @if($is_payment_order == 1)
                            <div class="form-group col-lg-4">
                                <button type="button" onclick="processPayment.createPaymentAction('deal')"
                                        id="btn_order"
                                        class="btn btn-metal btn-lg m-btn son-mb m-btn m-btn--icon wd_type m-btn--wide m-btn--md"
                                        style="background-color: #FF794E">
                                    {{__('THANH TOÁN')}}
                                </button>
                            </div>
                        @endif
                        @if($is_update_order == 1)
                            <div class="form-group col-lg-4">
                                <button type="submit" id="btn_add" onclick="order.save_order('{{$item['deal_code']}}')"
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
                        {{__('DANH SÁCH THẺ IN')}}
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
						<span>{{__('THOÁT')}}</span>
						</span>
                        </a>

                        <button type="button" onclick="order.print_all()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md  m--margin-left-10 m--margin-bottom-10">
							<span>
							<i class="la la-print"></i>
							<span>{{__('IN TẤT CẢ')}}</span>
							</span>
                        </button>
                        <button type="button" onclick="ORDERGENERAL.sendAllCodeCard()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add m--margin-left-10 m--margin-bottom-10 btn-send-sms">
							<span>
							<i class="la la-mobile-phone"></i>
							<span>{{__('SMS TẤT CẢ')}}</span>
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
							<span>{{__('GỬI EMAIL')}}</span>
							</span>
                        </button>

                    </div>
                </div>

            </div>
        </div>

    </div>
    <input type="hidden" name="pt-discount" class="form-control pt-discount"
           value="{{$item['member_level_discount'] != null ? $item['member_level_discount'] : 0}}">
    <input type="hidden" value="" class="hiddenOrderIdss">
    @include('admin::orders.modal-enter-phone-number')
    <form id="form-order-ss" target="_blank" action="{{route('admin.order.print-bill2')}}" method="GET">
        <input type="hidden" name="ptintorderid" id="order_id_to_print" value="">
    </form>
    <input type="hidden" id="custom_price" name="custom_price" value="{{$customPrice}}">
    <input type="hidden" id="order_source_id" value="{{$item['order_source_id']}}">
    <input type="hidden" name="member_money" id="member_money" value="{{isset($memberMoney) ? $memberMoney : 0}}">

    <form id="form-customer-debt" target="_blank" action="{{route('admin.customer.print-bill-debt')}}" method="GET">
        <input type="hidden" name="customer_id" id="customer_id_bill_debt">
    </form>
    <div id="showPopup"></div>
    <div class="popupShow"></div>

    <input type="hidden" id="type_time_hidden" name="type_time_hidden" value="">
    <input type="hidden" id="time_address_hidden" name="time_address_hidden" value="">
    <input type="hidden" id="customer_contact_id_hidden" name="customer_contact_id_hidden" value="">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/order/html2canvas.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/jquery.printPage.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-deal/receipt.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/receipt-online/vnpay.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/process-payment/process-payment.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/delivery.js')}}" type="text/javascript"></script>
    <script>
        order._init();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/admin/general/send-sms-code-service-card.js?v='.time())}}"
            type="text/javascript"></script>

    {{--    Include các temaplte của view order--}}
    @include('customer-lead::customer-deal.script-template.template-html')

    <script type="text/template" id="tab-category-tpl">
        <li class="nav-item">
            <a class="nav-link {active}" data-toggle="tab" href="javascript:void(0)"
               onclick="order.loadProduct('{category_id}')" data-name="{category_id}"
               data-name="all">
                {category_name}
            </a>
        </li>
    </script>

    <script>
        stt_tr = {{count($dataDetail)}}
            var customPrice = {{$customPrice}};

        $(document).ready(function () {
            $('body').addClass('m-brand--minimize m-aside-left--minimize');

            @if(count($getTab) > 0)
            order.chooseType('{{$getTab[0]['code']}}');
            @endif
        });
    </script>
@stop
