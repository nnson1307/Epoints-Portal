<?php $view = 'layout' ?>

@if(isset($params['view_mode']) && $params['view_mode'] == 'chathub_popup')
        <?php $view = 'layout-modal' ?>
@endif

@extends($view)

@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ ĐƠN HÀNG')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/pos-order.css')}}">

    <style>
        /* span.select2.staff {
            width: 200px !important;
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
    <!--begin::Portlet-->

    <input type="hidden" id="img_hidden" value="{{asset('static/backend/images/image-user.png')}}">
    <div class="m-portlet m-portlet--head-sm" id="m-order-add" style="margin-bottom: 0px;">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÊM ĐƠN HÀNG')}}

                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
            <input type="hidden" name="order_id" id="order_id">
            <input type="hidden" name="order_code" id="order_code">
            <input type="hidden" name="customer_id" id="customer_id"
                   value="{{$customerLoad != null ? $customerLoad['customer_id'] : 1}}">
            <input type="hidden" name="money_customer" id="money_customer"
                   value="{{isset($customerLoad['money']) ? $customerLoad['money']['balance'] : 0}}">
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
                                            <a class="nav-link m-tabs__link {{$k == 0 ? 'active show': ''}}" data-toggle="tab"
                                               href="javascript:void(0)" onclick="order.chooseType('{{$v['code']}}')" role="tab"
                                               data-name="{{$v['code']}}">
                                                {{$v['tab_name']}}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif

                                @if(count($listMemberCard) > 0)
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
                            @if($customerLoad != null)
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        <img src="{{$customerLoad['customer_avatar'] != null ? $customerLoad['customer_avatar'] : asset('static/backend/images/image-user.png')}}"
                                             style="width: 52px;height: 52px">
                                    </div>
                                    <div class="col-lg-9">
                                         <span class="m-widget4__title m-font-uppercase"> {{$customerLoad['full_name']}}
                                             <span class="m-badge m-badge--success vanglai" data-toggle="m-tooltip"
                                                   title="" data-original-title="{{__('Thành viên')}}">
                                             </span>
                                         </span>
                                        <br>
                                        <span class="m-widget4__title m-font-uppercase">
                                        <i class="flaticon-support m--margin-right-5"></i> {{$customerLoad['phone1']}}</span>
                                        <br>
                                        <span class="m-widget4__title">
                                        {{__('Hạng')}}: {{$customerLoad['member_level_name']}}
                                            @switch($customerLoad['member_level_id'])
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
                                        {{__('Công nợ')}}: {{number_format($customerLoad['debt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <img src="{{asset('static/backend/images/image-user.png')}}"
                                         style="width: 52px;height: 52px">
                                    <span class="m-widget4__title m-font-uppercase">{{__('Khách vãng lai')}}
                                        <span class="m-badge m-badge--success vanglai"
                                              data-toggle="m-tooltip" data-placement="top" title=""
                                              data-original-title="{{__('Khách mới')}}">
                                        </span>
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if(isset($params['view_mode']) && $params['view_mode'] == 'chathub_popup')
                        @else
                            <div class="col-lg-4">

                                <a href="javascript:void(0)" onclick="order.modal_customer()"
                                   class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm choose_cus son-mb">
                                    <i class="la la-user-plus icon-sz"></i> {{__('Chọn khách hàng')}}
                                </a>
                                @if($customerLoad != null)
                                    <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)"
                                       class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm choose_1 son-mb m--margin-top-5"><i
                                                class="la la-user icon-sz"></i>{{__('Khách hàng vãng lai')}}</a>
                                @endif
                            </div>
                        @endif

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
                                    @foreach($customer_refer as $key => $value)
                                        @if($key != 1)
                                            <option value="{{$key}}">{{$value}}</option>
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
                                <button type="button" onclick="processPayment.createPaymentAction()" id="btn_order"
                                        class="btn btn-metal btn-lg m-btn son-mb m-btn m-btn--icon wd_type m-btn--wide m-btn--md"
                                        style="background-color: #FF794E">
                                    {{__('THANH TOÁN')}}
                                </button>
                            </div>
                        @endif
                        @if($is_update_order == 1)
                            <div class="form-group col-lg-4">
                                <button type="submit" id="btn_add"
                                        class="btn btn-success btn-lg m-btn son-mb m-btn m-btn--icon wd_type
                                m-btn--wide m-btn--md btn-add">
                                    {{__('LƯU THÔNG TIN')}}
                                </button>
                            </div>
                        @endif
                        <div class="form-group col-lg-4">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    @if(isset($params['view_mode']))
                                        <a href="javascript:void(0)" onclick="cancelBtn();"
                                           class="btn btn-metal btn-lg m-btn m-btn--icon m-btn--wide m-btn--md wd_type">
                                            {{__('HỦY')}}
                                        </a>
                                    @else
                                        <a href="{{route('admin.order')}}"
                                           class="btn btn-metal btn-lg m-btn m-btn--icon m-btn--wide m-btn--md wd_type">
                                            {{__('HỦY')}}
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="content-print-bill" style="display: none"></div>
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
        <input type="hidden" name="pt-discount" class="form-control pt-discount" value="0">
        <form id="form-order-ss" target="_blank" action="{{route('admin.order.print-bill2')}}" method="GET">
            <input type="hidden" name="ptintorderid" id="orderiddd" value="">
        </form>
        <input type="hidden" value="" class="hiddenOrderIdss">
        <input type="hidden" id="custom_price" name="custom_price" value="{{$customPrice}}">

        @include('admin::orders.modal-discount')
        @include('admin::orders.customer')
        @include('admin::orders.modal-discount-bill')
        @include('admin::orders.receipt')
        @include('admin::orders.modal-enter-phone-number')
        @include('admin::orders.modal-enter-email')

        <input type="hidden" id="session" value="{{$session}}">
        <div id="showPopup"></div>
        <input type="hidden" id="type_time_hidden" name="type_time_hidden" value="">
        <input type="hidden" id="time_address_hidden" name="time_address_hidden" value="">
        <input type="hidden" id="customer_contact_id_hidden" name="customer_contact_id_hidden" value="">
        <div class="popupShow"></div>
        <input type="hidden" id="view_mode" value="{{$params['view_mode'] ?? null}}">
        <input type="hidden" id="ch_full_name" value="{{$params['ch_full_name'] ?? null}}">
        <input type="hidden" id="ch_new_cus" value="{{$params['ch_new_cus'] ?? null}}">
        <input type="hidden" id="ch_customer_id" name="ch_customer_id" value="{{$params['ch_customer_id'] ?? null}}">

        <form id="form-customer-debt" target="_blank" action="{{route('admin.customer.print-bill-debt')}}" method="GET">
            <input type="hidden" name="customer_id" id="customer_id_bill_debt">
        </form>
        @stop
        @section('after_script')
            <script>
                var customPrice = {{$customPrice}};
                let numberRow = 0;
            </script>
            <script type="text/template" id="tab-card-tpl">
                <li class="nav-item m-tabs__item type">
                    <a href="javascript:void(0)" onclick="order.chooseType('member_card')"
                       class="nav-link m-tabs__link tab_member_card" data-toggle="tab" role="tab"
                       aria-selected="false" data-name="member_card">
                        <i class="la la-shopping-cart"></i>{{__('Thẻ dịch vụ đã mua')}}
                    </a>
                </li>
            </script>
            <script>
                var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
                $(document).ready(function () {
                    $('body').addClass('m-brand--minimize m-aside-left--minimize');

                });
            </script>
            <script src="{{asset('static/backend/js/admin/order/html2canvas.min.js')}}" type="text/javascript"></script>

            <!-- html table product/service add order -->
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
                            <input name="amount" style="text-align: center;" class="form-control amount"
                                   id="amount_{stt}"
                                   value="{amount_hidden}">
                        @else
                            <div id="amount_surcharge_{stt}">
                                <input name="amount" style="text-align: center;" class="form-control amount"
                                       id="amount_{stt}"
                                       value="{amount_hidden}">
                            </div>
                            <div id="amount_not_surcharge_{stt}">
                                {amount}{{__('đ')}}
                                <input type="hidden" style="text-align: center;" name="amount"
                                       class="form-control amount" id="amount_{stt}" value="{amount_hidden}">
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
                    <td colspan="2">
                        <span id="note_text_{stt}">@lang('Ghi chú/Dịch vụ thêm')...</span>
                        <a href="javascript:void(0)"
                           onclick="order.showPopupAttach('{stt}', '{type_hidden}', '{id}', '{name}', '{price}')"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-pencil"></i>
                        </a>
                    </td>
                    <td></td>
                    <td colspan="2" class="td_staff">
                        <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple"
                                style="width: 80%">
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
                            <input name="amount" style="text-align: center;" class="form-control amount"
                                   id="amount_{stt}"
                                   value="{amount_hidden}">
                        @else
                            <div id="amount_surcharge_{stt}">
                                <input name="amount" style="text-align: center;" class="form-control amount"
                                       id="amount_{stt}"
                                       value="{amount_hidden}">
                            </div>
                            <div id="amount_not_surcharge_{stt}">
                                0{{__('đ')}}
                                <input type="hidden" style="text-align: center;" name="amount"
                                       class="form-control amount"
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
                        <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple"
                                style="width: 80%">
                            <option></option>
                            @foreach($staff_technician as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </script>


            <script type="text/template" id="button-tpl">
                <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)"
                   class="m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary  cus_haunt  choose_1 son-mb"><i
                            class="la la-user icon-sz"></i>{{__('Khách hàng vãng lai')}}</a>
            </script>
            <script type="text/template" id="customer-haunt-tpl">
                <div class="col-lg-8">
                    <div class="form-group">
                        <img src="{{asset('static/backend/images/image-user.png')}}" style="width: 52px;height: 52px">
                        <span class="m-widget4__title m-font-uppercase">{{__('Khách vãng lai')}}
                    <span class="m-badge m-badge--success vanglai"
                          data-toggle="m-tooltip" data-placement="top" title=""
                          data-original-title="{{__('Khách mới')}}"></span>
                                </span>

                    </div>
                </div>
                <div class="col-lg-4">
                    <a href="javascript:void(0)" onclick="order.modal_customer()"
                       class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm  choose_cus son-mb">
                        <i class="la la-user-plus icon-sz"></i> {{__('Chọn khách hàng')}}</a>
                </div>
            </script>
            <script type="text/template" id="list-tpl">
                <div class="info-box col-lg-3 m--margin-bottom-10"
                     onclick="order.append_table({id},'{price_hidden}','{type}','{name}','{code}', '{is_surcharge}','{inventory_management}')">
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
            <script type="text/template" id="bill-tpl">
                <span class="total_bill">{total_bill_label}
           
        </span>
                <input type="hidden" name="total_bill" id="total_bill" class="form-control total_bill"
                       value="{total_bill}">
            </script>
            <script type="text/template" id="customer-tpl">
                <div class="col-lg-8">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <img src="{{'{img}'}}" style="width: 52px;height: 52px">
                        </div>
                        <div class="col-lg-9">
                     <span class="m-widget4__title m-font-uppercase">{full_name}
                    <span class="m-badge m-badge--success vanglai"
                          data-toggle="m-tooltip" data-placement="top" title=""
                          data-original-title="{{__('Thành viên')}}"></span>
                     </span>
                            <br>
                            <span class="m-widget4__title m-font-uppercase">
                    <i class="flaticon-support m--margin-right-5"></i> {phone}</span>
                            <br>
                            <span class="m-widget4__title">
                    {{__('Hạng')}}: {member_level_name} {icon}
                    </span><br>
                            <span class="m-widget4__title">
                    {{__('Công nợ')}}: {debt} {{__('đ')}}
                    </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="javascript:void(0)" onclick="order.modal_customer()"
                               class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm choose_cus son-mb">
                                <i class="la la-user-plus icon-sz"></i> {{__('Chọn khách hàng')}}</a>
                        </div>
                        <div class="col-lg-12">
                            <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)"
                               class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm choose_1 son-mb m--margin-top-5"><i
                                        class="la la-user icon-sz"></i>{{__('Khách hàng vãng lai')}}</a>
                        </div>
                    </div>


                </div>
            </script>
            <script type="text/template" id="type-receipt-tpl">
                <div class="row">
                    <label class="col-lg-6 font-13">{label}:<span
                                style="color:red;font-weight:400">{money}</span></label>
                    <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                        <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000"
                               class="form-control m-input"
                               placeholder="{{__('Nhập giá tiền')}}"
                               aria-describedby="basic-addon1"
                               name="{name_cash}" id="{id_cash}" value="0">
                        <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
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
                        {quantity}({{__('lần')}})
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
            </script>
            <script type="text/template" id="table-card-tpl">
                <tr class="tr_table">
                    <td class="stt_length" style="width: 30px;"></td>
                    <td class="td_vtc" style="color: #000; font-weight: 500; width: 100px;">
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
                           href="javascript:void(0)"
                           onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}')">
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
                        <input type="hidden" id="numberRow" value="">
                        <a class='remove_card' href="javascript:void(0)" style="color: #a1a1a1"><i
                                    class='la la-trash'></i></a>
                    </td>
                </tr>
            </script>
            <script type="text/template" id="active-tpl">
                <div class="m-checkbox-list">
                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m-checkbox--state-success sz_dt">
                        <input type="checkbox" name="check_active" id="check_active"
                               value="0"> {{__('Kích hoạt thẻ dịch vụ')}}
                        <span></span>
                    </label>
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
                            class="btn btn-primary  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
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
            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
            <script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}"
                    type="text/javascript"></script>
            <script src="{{asset('static/backend/js/admin/order/script.js?v='.time())}}"
                    type="text/javascript"></script>
            {{--    <script src="{{asset('static/backend/js/admin/order/script-order-v2.js?v='.time())}}" type="text/javascript"></script>--}}
            <script src="{{asset('static/backend/js/admin/order/delivery.js')}}" type="text/javascript"></script>
            <script src="{{asset('static/backend/js/admin/general/send-sms-code-service-card.js')}}"
                    type="text/javascript"></script>
            <script src="{{asset('static/backend/js/admin/order/receipt-online/vnpay.js')}}"
                    type="text/javascript"></script>
            <script src="{{asset('static/backend/js/admin/order/process-payment/process-payment.js?v='.time())}}"
                    type="text/javascript"></script>
            <script>
                $(document).ready(function () {
                    $('body').addClass('m-brand--minimize m-aside-left--minimize');
                    $('#suburbward').select2();

                });
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
                        <a class='remove' href="javascript:void(0)" onclick="order.removeGift(this)"
                           style="color: #a1a1a1"><i
                                    class='la la-trash'></i></a>
                    </td>

                    <input type="hidden" name="numberRow" id="numberRow" value="{numberRow}">
                    <input type="hidden" name="note" id="note_{stt}">
                </tr>

                <tr class="tr_note_child_{stt} promotion_note_child_gift">
                    <td></td>
                    <td colspan="2">
                        <span id="note_text_{stt}">@lang('Ghi chú/Dịch vụ thêm')...</span>
                        <a href="javascript:void(0)"
                           onclick="order.showPopupAttach('{stt}', '{type_hidden}', '{id}', '{name}', '{price}')"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-pencil"></i>
                        </a>
                    </td>
                    <td></td>
                    <td colspan="2" class="td_staff">
                        <select class="form-control staff staff_{stt}" name="staff_id" multiple="multiple"
                                style="width: 80%">
                            <option></option>
                            @foreach($staff_technician as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </script>
            <script type="text/template" id="list-promotion-tpl">
                <div class="info-box col-lg-3 m--margin-bottom-10"
                     onclick="order.append_table({id},'{price_hidden}','{type}','{name}','{code}', '{is_surcharge}','{inventory_management}')">
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
                        <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000"
                               class="form-control m-input"
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
            <script>
                @if(isset($params['view_mode']) && $params['view_mode'] == 'chathub_popup')
                if ($('#ch_new_cus').val() == 1) {
                    setTimeout(function () {
                        order.modal_customer();
                    }, 1000)

                }

                function cancelBtn() {
                    window.parent.focus();
                    var bc = new BroadcastChannel('cancelOrder');
                    bc.postMessage({}); /* send */
                    window.close();
                }

                @endif

                $(document).ready(function () {
                    @if(count($getTab) > 0)
                        order.chooseType('{{$getTab[0]['code']}}');
                    @endif

                    // $('.receipt_info_check').prop('checked', true).trigger('change');
                });
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
@stop
