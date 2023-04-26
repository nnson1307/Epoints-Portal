@extends('layout')
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/pos-order.css')}}">
@endsection

@section('content')
    @include('admin::customer-appointment.receipt.modal-discount-ap')
    @include('admin::customer-appointment.receipt.modal-discount-bill-ap')
    @include('admin::customer-appointment.receipt.modal-receipt')
    @include('admin::orders.modal-enter-email')

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
                <div class="col-lg-8 bdr">
                    <div class="row bdb" style="padding: 0px !important;">
                        <div class="m-section__content col-lg-12">
                            <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true"
                                 style="height: 200px; overflow: hidden; width:100%;padding: 0px !important;">
                                <div class="table-responsive">
                                    <table class="table table-striped m-table m-table--head-bg-default" id="table_add">
                                        <thead class="bg">
                                        <tr>
                                            <td class="tr_thead_od">#</td>
                                            <td class="tr_thead_od">{{__('Tên')}}</td>
                                            <td class="tr_thead_od">{{__('Giá')}}</td>
                                            <td class="tr_thead_quan width-110-od">{{__('Số lượng')}}</td>
                                            <td class="tr_thead_od  text-center">{{__('Giảm')}}</td>
                                            <td class="tr_thead_od  text-center">{{__('Thành tiền')}}</td>
                                            <td class="tr_thead_od text-center">{{__('Nhân viên')}}</td>
                                            <td></td>
                                        </tr>
                                        </thead>
                                        <tbody class="tr_thead_od">
                                        @if(count($data_detail)>0)
                                            @foreach($data_detail as $k=> $v)
                                                <tr class="tr_table">
                                                    <td></td>
                                                    <td>{{$v['service_name']}}
                                                        <input type="hidden" name="id" value="{{$v['service_id']}}">
                                                        <input type="hidden" name="name" value="{{$v['service_name']}}">
                                                        <input type="hidden" name="object_type"
                                                               value="{{$v['object_type']}}">
                                                        <input type="hidden" name="object_code"
                                                               value="{{$v['service_code']}}">

                                                    </td>
                                                    <td>
                                                        {{number_format($v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                        <input type="hidden" name="price" value="{{$v['price']}}">
                                                    </td>
                                                    <td>
                                                        <input style="text-align: center;" name="quantity"
                                                               class="{{$v['object_type'] == 'member_card' ? 'quantity_c quantity' : 'quantity'}} form-control btn-ct"
                                                               {{$is_edit_full == 0 ? 'disabled' : ''}}
                                                               value="{{$v['quantity']}}" {{$customPrice == 1 && $v['object_type'] != 'member_card'  ? 'disabled' : ''}}>
                                                        @if($v['object_type'] == 'member_card')
                                                            <input type="hidden" name="quantity_hid" value="{{$v['number_using'] != 0 ? $v['number_using'] - $v['count_using'] : 0}}">
                                                        @else
                                                            <input type="hidden" name="quantity_hid" value="0">
                                                        @endif
                                                    </td>
                                                    <td class="discount-tr-{{$v['object_type']}}-{{$v['service_id']}}-{{$v['number_ran']}} text-center">
                                                        <input type="hidden" name="discount"
                                                               class="form-control discount"
                                                               value="0" maxlength="11">
                                                        <input type="hidden" name="voucher_code" value="">

                                                        @if($is_edit_full == 1)
                                                        @if ($customPrice == 0 || $v['object_type'] != 'member_card')
                                                            <a class="abc m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-cus"
                                                               href="javascript:void(0)"
                                                               onclick="order.modal_discount('{{$v['price']*$v['quantity']}}','{{$v['service_id']}}',1,'{{$v['number_ran']}}')"><i
                                                                        class="la la-plus icon-sz"></i>
                                                            </a>
                                                        @endif
                                                        @endif
                                                    </td>
                                                    <td class="amount-tr text-center">
                                                        @if (isset($customPrice) && $customPrice == 1 && in_array($v['object_type'], ['service', 'product', 'service_card']))
                                                            <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{{$v['number_ran']}}"
                                                                   value="{{number_format($v['price']*$v['quantity'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                        @else
                                                            {{number_format($v['price']*$v['quantity'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                            <input type="hidden" name="amount" class="form-control amount"
                                                                   value="{{$v['price']*$v['quantity']}}">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select class="form-control staff"
                                                                {{ $is_edit_staff == 0 ? 'disabled' : '' }}
                                                                name="staff_id" multiple="multiple"
                                                            style="width:80%;">
                                                            <option></option>
                                                            @foreach($staff_technician as $key => $value)
                                                                <option value="{{$key}}" {{$v['staff_id'] != null && in_array($key, $v['staff_id']) ? 'selected': ''}}>{{$value}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="number_ran" value="{{$v['number_ran']}}">
                                                        <input type="hidden" name="is_change_price" value="{{$customPrice}}">
                                                        <input type="hidden" name="is_check_promotion" value="{{$v['is_check_promotion']}}">
                                                        <a class="remove" href="javascript:void(0)"  {{$is_edit_full == 0 ? 'hidden' : ''}}
                                                           style="color: #a1a1a1"><i class="la la-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <span class="error-table" style="color: #ff0000"></span>
                            </div>
                        </div>
                    </div>
                    @if($is_edit_full == 1)
                    <div class="form-group m-form__group m--margin-top-10 row">
                        <div class="col-lg-12 bdr">
                            
                            <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm tab-list m--margin-bottom-10 ul_type"
                                role="tablist">
                                <li class="nav-item m-tabs__item type">
                                    <a class="nav-link m-tabs__link active show" data-toggle="tab"
                                       href="javascript:void(0)" onclick="order.chooseType('service')" role="tab"
                                       data-name="service">
                                        {{__('Dịch vụ')}}
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item type">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="javascript:void(0)"
                                       onclick="order.chooseType('service_card')" role="tab"
                                       data-name="service_card">{{__('Thẻ dịch vụ')}}
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item type">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="javascript:void(0)"
                                       onclick="order.chooseType('product')" role="tab" data-name="product">
                                        {{__('Sản phẩm')}}
                                    </a>
                                </li>

                                @if(count($data_card) > 0)
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

                            <div class="m-separator m-separator--dashed"></div>

                            <ul class="nav nav-pills nav-pills--success ul_category" role="tablist"></ul>

                            <div class="form-group m-form__group ">
                                <div class="m-input-icon m-input-icon--left">
                                    <span class="">
                                            <input id="search" name="search" autocomplete="off" type="text"
                                                   class="form-control m-input--pill m-input" value=""
                                                   placeholder="{{__('Nhập thông tin tìm kiếm')}}">
                                    </span>
                                    <span class="m-input-icon__icon m-input-icon__icon--left"><span><i
                                                    class="la la-search"></i></span></span>
                                </div>
                            </div>
                            <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true"
                                 style="height: 250px; overflow: hidden;" id="list-product">

                            </div>
                            <input type="hidden" value="" id="category_id_hidden">
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="row m--margin-bottom-25">
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" name="order_code" id="order_code">
                        <input type="hidden" name="customer_appointment_id" id="customer_appointment_id"
                               value="{{$item['customer_appointment_id']}}">
                        <input type="hidden" name="customer_id" id="customer_id"
                               value="{{$item['customer_id']}}">
                        <input type="hidden" name="money_customer" id="money_customer"
                               value="{{$money_branch != null ? $money_branch : 0}}">
                        <div class="col-lg-8">
                            <div class="form-group row customer">
                                <div class="col-lg-3">
                                    @if($item['customer_avatar']!="")
                                        <img src="{{asset($item['customer_avatar'])}}" height="52px" width="52px">
                                    @else
                                        <img src="{{asset('static/backend/images/image-user.png')}}" height="52px"
                                             width="52px">
                                    @endif
                                </div>
                                <div class="col-lg-9">
                                     <span class="m-widget4__title m-font-uppercase">
                                     {{$item['full_name_cus']}}
                                        <span class="m-badge m-badge--success vanglai"
                                              data-toggle="m-tooltip" data-placement="top" title=""
                                              data-original-title="{{$item['customer_id'] != 1 ? 'Thành viên' : 'Khách mới'}}">
                                    </span>
                                     </span>
                                    <br>
                                    <span class="m-widget4__title m-font-uppercase">
                                                        <i class="flaticon-support m--margin-right-5"></i>
                                                        {{$item['phone1']}}
                                                    </span>
                                    <br>
                                    <span class="m-widget4__title">
                                        @lang('Hạng'): {{$item['member_level_name'] != null ? $item['member_level_name'] : __('Thành Viên')}}
                                    </span><br>
                                    <span class="m-widget4__title">
                                        {{__('Công nợ')}}
                                        : {{number_format($debt, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                    </span> <br>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">

                        </div>
                    </div>
                    <div class="row m--margin-bottom-25">
                        <div class="col-lg-12">
                            <select class="form-control" id="refer_id"
                                    {{$is_edit_full == 0 ? 'disabled' : ''}}
                                    name="refer_id" style="width:100%;">
                                <option></option>
                                @foreach($customer_refer as $key => $value)
                                    @if($key != 1)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row m--margin-left-5 m--margin-right-5"
                         style="padding-top: 0px !important;padding-bottom: 0px !important;">
                        <div class="order_tt">
                            <div class="m-list-timeline__items">
                                <div class="m-list-timeline__item sz_bill">
                                    <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Tổng tiền')}}:</span>
                                    <span class="m-list-timeline__time m--font-boldest append_bill">
                                    {{number_format($item['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                    <input type="hidden" name="total_bill" id="total_bill"
                                           class="form-control total_bill"
                                           value="{{$item['total']}}">
                                </span>
                                </div>
                                <div class="m-list-timeline__item sz_bill">
                                    <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Chiết khấu thành viên')}}:</span>
                                    <span class="m-list-timeline__time m--font-boldest">
                                        <span class="span_member_level_discount">0</span> @lang('đ')
                                    </span>
                                    <input type="hidden" name="member_level_discount" id="member_level_discount"
                                           class="form-control" value="0">

                                </div>
                                <div class="m-list-timeline__item sz_bill">
                                    <span class="m-list-timeline__text m--font-boldest sz_word">{{__('Giảm giá')}}:</span>
                                    <span class="m-list-timeline__time m--font-boldest discount_bill">

                                        @if($item['discount']>0)
                                            <a class="tag_a" href="javascript:void(0)"
                                               onclick="order.close_discount_bill({{$item['total']}})">
                                                <i class="la la-close cl_amount_bill"></i>
                                            </a>
                                        @else
                                            <a href="javascript:void(0)"
                                               onclick="order.modal_discount_bill({{$item['total']}})"
                                               class="tag_a">
                                            <i class="fa fa-plus-circle icon-sz m--margin-right-5"
                                               style="color: #4fc4cb;"></i>
                                            </a>
                                        @endif
                                        @if($item['discount']>0)
                                            {{number_format($item['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
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
                                    <span class="m-list-timeline__text m--font-boldest sz_word">{{__('Thành tiền')}}:</span>
                                    <span class="m-list-timeline__time m--font-boldest amount_bill" style="color: red">
                                         {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                        <input type="hidden" name="amount_bill_input"
                                               class="form-control amount_bill_input"
                                               value="{{$item['amount']}}">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($is_payment_order == 1)
                    <div class="m-form__group m--margin-top-10">
                        <button type="submit" id="btn_order" onclick="processPayment.createPaymentAction('appointment')"
                                class="btn btn-success color_button son-mb wd_type
                                m-btn m-btn--icon m-btn--wide m-btn--md">
							<span>
							<i class="la la-reorder"></i>
							<span>{{__('THANH TOÁN')}}</span>
							</span>
                        </button>
                    </div>
                    @endif
                    @if($is_update_order == 1)
                    <div class="m-form__group m--margin-top-10">
                        <button type="button" id="btn_add" onclick="order.save_order()"
                                class="btn btn-success color_button son-mb m-btn m-btn--icon wd_type
                                m-btn--wide m-btn--md btn-add">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                    @endif
                    <div class="m-form__group m--margin-top-10">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <a href="{{route('admin.order')}}"
                                   class="btn btn-metal bold-huy  m-btn m-btn--icon m-btn--wide m-btn--md wd_type">
                                    <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>{{__('HUỶ')}}</span>
                                    </span>
                                </a>
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
        <input type="hidden" name="ptintorderid" id="orderiddd" value="">
    </form>
    <input type="hidden" id="custom_price" name="custom_price" value="{{$customPrice}}">
@endsection
@section('after_script')
    <script>
        var customPrice = {{$customPrice}};

        $(document).ready(function () {
            $('body').addClass('m-brand--minimize m-aside-left--minimize');
        });
    </script>
    <script src="{{asset('static/backend/js/admin/order/html2canvas.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}"
            type="text/javascript"></script>
    <script type="text/template" id="button-tpl">
        <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)"
           class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary cus_haunt"><i
                    class="la la-user"></i>{{__('Khách hàng vãng lai')}}</a>
    </script>
    <script type="text/template" id="customer-haunt-tpl">
        <div class="m-widget4__item ">
            <div class="m-widget4__img m-widget4__img--pic">
                <img src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                     alt="">
            </div>
            <div class="m-widget4__info">
							<span class="m-widget4__title ">
							{{__('Khách vãng lai')}}
							</span><br>

                <span class="m-widget4__sub">

							</span>

            </div>

        </div>
    </script>
    <script type="text/template" id="tab-card-tpl">
        <li class="nav-item m-tabs__item type">
            <a href="javascript:void(0)" onclick="order.click('member_card')"
               class="nav-link m-tabs__link tab_member_card" data-toggle="tab" role="tab"
               aria-selected="false" data-name="member_card">
                <i class="la la-shopping-cart"></i>{{__('Thẻ dịch vụ đã mua')}}
            </a>
        </li>
    </script>
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
            <td></td>
            <td>{name}
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" value="{code}">
            </td>
            <td>
                {price}@lang('đ')
                <input type="hidden" name="price" value="{price_hidden}">
            </td>
            <td>
                <input style="text-align: center;" name="quantity" class="quantity form-control btn-ct-input" value="1"
                        {{$customPrice == 1 ? 'disabled' : ''}} {isSurcharge}>
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{id}-{number_ran} text-center">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
                @if (!isset($customPrice) || $customPrice == 0)
                    <a class="abc m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-cus" id="discount_{stt}"
                       href="javascript:void(0)"
                       onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}','{number_ran}')">
                        <i class="la la-plus icon-sz"></i>
                    </a>
                @endif
            </td>
            <td class="amount-tr text-center">
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
                        <input type="hidden" style="text-align: center;" name="amount" class="form-control amount" id="amount_{stt}"
                               value="{amount_hidden}">
                    </div>
                @endif
            </td>
            <td>
                <select class="form-control staff" name="staff_id" style="width:80%;" multiple="multiple">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="hidden" name="number_ran" value="{number_ran}">
                <input type="hidden" name="is_change_price" value="{is_change_price}">
                <input type="hidden" name="is_check_promotion" value="{is_check_promotion}">
                <a class='remove' style="color: #a1a1a1" href="javascript:void(0)"><i class='la la-trash'></i></a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="bill-tpl">
        <span class="total_bill">
                                   {total_bill_label}
                 <input type="hidden" name="total_bill" id="total_bill" class="form-control total_bill"
                        value="{total_bill}">

                    </span>
    </script>
    <script type="text/template" id="customer-tpl">
        <div class="m-widget4__img m-widget4__img--pic">
            <img src="{img}" alt="" width="50px" height="50px">
        </div>
        <div class="m-widget4__info">
							<span class="m-widget4__title ">
							{full_name}
							</span><br>
            <span class="m-widget4__sub">
							<i class="la la-phone"></i>{phone}<br>
                             {{__('Điểm')}}: 0<br>
                             {{__('Tiền còn lại')}}: {money} đ
							</span>
        </div>
    </script>
    <script type="text/template" id="type-receipt-tpl">
        <div class="row">
            <label class="col-lg-6 font-13">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group-sm col-lg-6">
                <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
                       placeholder="Nhập giá tiền"
                       aria-describedby="basic-addon1"
                       name="{name_cash}" id="{id_cash}" value="0">
                <div class="input-group-append"><span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}</span>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="active-card-tpl">
        <a href="javascript:void(0)" onclick="order.modal_card({id})"
           class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary active-a"><i
                    class="la la-plus"></i>{{__('Thẻ dịch vụ đã mua')}}</a>
    </script>
    <script type="text/template" id="list-card-tpl">
        <div class="info-box col-lg-3 m--margin-bottom-10"
             onclick="order.append_table_card({id_card},'0','member_card','{card_name}','{quantity_app}','{card_code}',this)">
            <div class="info-box-content ss--text-center">
                <div class="m-widget4__item card_check_{id_card}">
                    <span class="m-widget4__sub m--font-bolder m--font-success quantity">
                        {quantity}(lần)
                    </span>
                    <div class="m-widget4__img m-widget4__img--pic">
                        <img src="{img}" class="ss--image-pos">
                    </div>
                    <div class="m-widget4__info">
                        <span class="m-widget4__title"> {card_name} </span>
                    </div>
                    <input type="hidden" class="card_hide" value="{card_code}">
                    <input type="hidden" class="quantity_card" value="{quantity_app}">
                </div>
            </div>
        </div>


        <!-- <div class="m-widget4__item card_check_{id_card}">
            <div class="m-widget4__img m-widget4__img--pic">
                <img src="{{asset('{img}')}}" alt="" width="52px" height="52px">
            </div>
            <div class="m-widget4__info">
                <span class="m-widget4__title"> {card_name} </span><br>
                <span class="m-widget4__sub m--font-bolder m--font-success quantity">{quantity}(lần)</span><br>
                <span class="m-widget4__sub m--font-bolder m--font-success">{card_code}</span><br>
            </div>
            <div class="m-widget4__ext">
                <input type="hidden" class="card_hide" value="{card_code}">
                <input type="hidden" class="quantity_card" value="{quantity_app}">
                <a href="javascript:void(0)"
                   onclick="order.append_table_card({id_card},'0','member_card','{card_name}','{quantity_app}','{card_code}',this)"
                   class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                    Chọn
                </a>
            </div>
        </div> -->

    </script>
    <script type="text/template" id="table-card-tpl">
        <tr class="tr_table">
            <td></td>
            <td>{name}
                <input type="hidden" name="id" value="{id}">
                <input type="hidden" name="name" value="{name}">
                <input type="hidden" name="object_type" value="{type_hidden}">
                <input type="hidden" name="object_code" value="{code}">
            </td>
            <td>
                {price}đ
                <input type="hidden" name="price" value="{price_hidden}">
            </td>
            <td>
                <input style="text-align: center;" name="quantity" class="quantity_c form-control btn-ct-input">
                <input type="hidden" name="quantity_hid" value="{quantity_hid}">
            </td>
            <td class="discount-tr-{type_hidden}-{id} text-center">
                <input type="hidden" name="discount" class="form-control discount" value="0">
                <input type="hidden" name="voucher_code" value="">
                <a class="{class} m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary"
                   href="javascript:void(0)" onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}')">
                    <i class="la la-plus"></i>
                </a>
            </td>
            <td class="amount-tr text-center">
                {amount}đ
                <input type="hidden" style="text-align: center;" name="amount" class="form-control amount"
                       value="{amount_hidden}">
            </td>
            <td>
                <select class="form-control staff" name="staff_id" style="width:80%;" multiple="multiple">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="hidden" name="number_ran">
                <input type="hidden" name="is_change_price" value="0">
                <input type="hidden" name="is_check_promotion" value="0">
                <a class='remove_card' href="javascript:void(0)" style="color: #a1a1a1"><i
                            class='la la-trash'></i></a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="active-tpl">
        <div class="m-checkbox-list">
            <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success sz_dt">
                <input type="checkbox" name="check_active" id="check_active" value="0"> {{__('Kích hoạt thẻ dịch vụ')}}
                <span></span>
            </label>
        </div>
    </script>
    <script type="text/template" id="sv-card-print-tpl">
        <div class="col-lg-6 m--padding-bottom-10 card_print">
            <label class="m-option">
                            <span class="m-option__label">
																					<span class="m-option__head">
																						<span class="m-option__title"
                                                                                              style="font-weight: bold;">
																							{card_name}
																						</span>
																						<span class="m-option__focus">
																							{money}
																						</span>
																					</span>
																					<span class="m-option__body">
																						{{__('Số lần sử dụng')}}: {number_using}
																					</span>
                                <span class="m-option__body">
																						{{__('Thời hạn sử dụng')}}: {date_using}
																					</span>
                                 <span class="m-option__body">
																						{{__('Chưa sử dụng')}}
																					</span>
                                <span class="m-option__body">
																						{{__('Mã thẻ')}}: {card_code}
																					</span>
																				</span>
            </label>
            <div class="form-group m-form__group" align="center">
                <a href="javascript:void(0)" onclick="order.print('{card_code}')"
                   class="btn btn-outline-primary btn-sm 	m-btn m-btn--icon">
															<span>
																<i class="la la-print"></i>
																<span>
																	{{__('In')}}
																</span>
															</span>
                </a>
                <a href="javascript:void(0)" onclick="ORDERGENERAL.sendEachSmsServiceCard('{card_code}')"
                   class="btn btn-outline-primary btn-sm m-btn m-btn--icon btn-send-sms">
															<span>
																<i class="la la-mobile-phone"></i>
																<span>
																	{{__('SMS')}}
																</span>
															</span>
                </a>
            </div>
        </div>
    </script>
    <script type="text/template" id="button-discount-add-tpl">
        <div class="m-form__actions m--align-right w-100">
            <button data-dismiss="modal"
                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{(__('HUỶ'))}}</span>
                                </span>
            </button>
            <button type="button" onclick="order.discount('{id}','{id_type}','{numb_ran}')"
                    class="btn btn-primary  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-print m--margin-left-10">
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
                                <span>{{(__('HUỶ'))}}</span>
                                </span>
            </button>
            <button type="button" onclick="order.modal_discount_bill_click()"
                    class="btn btn-primary  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-print m--margin-left-10">
							<span>
							<span>{{__('ĐỒNG Ý')}}</span>
							</span>
            </button>
        </div>
    </script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/customer-appointment/receipt.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/receipt-online/vnpay.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/process-payment/process-payment.js')}}" type="text/javascript"></script>
    <script>
        order._init();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/admin/general/send-sms-code-service-card.js')}}"
            type="text/javascript"></script>
    <script type="text/template" id="table-gift-tpl">
        <tr class="tr_table promotion_gift">
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
                <select class="form-control staff" name="staff_id" style="width:100%;" disabled multiple="multiple">
                    <option></option>
                    @foreach($staff_technician as $key => $value)
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
        <li class="nav-item">
            <a class="nav-link {active}" data-toggle="tab" href="javascript:void(0)"
               onclick="order.loadProduct('{category_id}')" data-name="{category_id}"
               data-name="all">
                {category_name}
            </a>
        </li>
    </script>
@stop
