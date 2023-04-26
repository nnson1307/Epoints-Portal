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
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css?v='.time())}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/pos-order.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/todh.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/fnb/owl.carousel.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/fnb/owl.theme.default.min.css')}}">

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
        .td_vtc{
            vertical-align: middle !important;
        }
        .info-table-select:hover {
            cursor: pointer;
        }
        .hover-cursor:hover {
            cursor: pointer;
        }

        .btn-info-update a {
            color : #fff;
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
            overflow-x: scroll;
            white-space: nowrap;
        }

        .background-style-1 {
            border-style: dotted;
            background-color: #FFCC99;
            color : #000 !important;
        }

        .background-style-1 span {
            color : #000 !important;
        }

        .demo-index {
            overflow-y : auto;
            height : 55vh;
        }

        .select2 {
            width : 100% !important;
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
{{--                <a href="javascript:void(0)" onclick="order.fullScreen()"--}}
{{--                    class="btn-full-screen btn m-btn btn-sm m-btn--icon m-btn--pill btn_add_pc">--}}
{{--                    <span> {{__('Toàn màn hình')}} <img src="{{asset('static/backend/images/fnb/icon-full-screen.png')}}"></span>--}}
{{--                </a>--}}
            </div>
            <input type="hidden" name="order_id" id="order_id">
            <input type="hidden" name="order_code" id="order_code">
            <input type="hidden" name="customer_id" id="customer_id" value="{{$customerLoad != null ? $customerLoad['customer_id'] : 1}}">
            <input type="hidden" name="money_customer" id="money_customer" value="{{isset($customerLoad['money']) ? $customerLoad['money']['balance'] : 0}}">
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group m-form__group m--margin-top-10 row">
                        <div class="col-lg-12 bdr">
                            <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm tab-list m--margin-bottom-10 ul_type"
                                role="tablist" style="margin-bottom: 0px !important;">
{{--                                <li class="nav-item m-tabs__item type">--}}

{{--                                    <a class="nav-link m-tabs__link active show" data-toggle="tab"--}}
{{--                                       href="javascript:void(0)" onclick="order.chooseType('area')" role="tab"--}}
{{--                                       data-name="area">--}}
{{--                                        {{__('Khu vực - bàn')}}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <li class="nav-item m-tabs__item type">
                                    <a class="nav-link m-tabs__link active show" data-toggle="tab" href="javascript:void(0)"
                                       onclick="order.chooseType('product')" role="tab" data-name="product">
                                        {{__('Sản phẩm')}}
                                    </a>
                                </li>
                            </ul>

                            <div class="m-separator m-separator-update m-separator--dashed"></div>
                            <div class="row">
{{--                                <div class="col-lg-12" id="tab_category">--}}
{{--                                    <ul class="nav nav-pills nav-pills--success ul_category" id="ul_category" role="tablist">--}}

{{--                                    </ul>--}}
{{--                                </div>--}}
                                <div class="col-lg-12 tab_category_order tab_category_order_new" id="tab_category">
                                    <img src="{{asset('static/backend/images/fnb/icon-next.png')}}" class="prevSlider" onclick="order.prevSlider()">
                                    <div class="owl-carousel ul_category " id="ul_category" role="tablist">

                                    </div>
                                    <img src="{{asset('static/backend/images/fnb/icon-next.png')}}" class="nextSlider" onclick="order.nextSlider()">
                                </div>
                            </div>

                            <div class="form-group m-form__group ">
                                <div class="m-input-icon m-input-icon--left">
                                    <span class="">
                                            <input id="search" name="search" autocomplete="off" type="text"
                                                   class="form-control m-input--pill m-input" value="" onkeyup="search('search',event)"
                                                   placeholder="{{__('Nhập thông tin tìm kiếm')}}">

                                            <input id="search_product" name="search_product" onkeyup="search('search_product',event)" style="display:none" autocomplete="off" type="text"
                                                   class="form-control m-input--pill m-input" value=""
                                                   placeholder="{{__('Nhập thông tin tìm kiếm')}}">
                                    </span>
                                    <span class="m-input-icon__icon m-input-icon__icon--left"><span><i
                                                    class="la la-search"></i></span></span>
                                </div>
                            </div>
                            <div id="list-product">
                                <div class="demo-index pt-3 row" >

                                </div>
                            </div>
                            <input type="hidden" value="" id="category_id_hidden">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row ">
                        <div class="col-12 list-order-select">
                            <div class="tag__header">
                                <div class="btn btn-info btn-info-update background-style-1">
                                    <a href="javascript:void(0)">
                                        {{--                {{ $list[$orderId]['table_name'].' - '.$list[$orderId]['order_code'] }}--}}
                                        {{ 'GH - '.__('Bản nháp') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if(isset($params['view_mode']) && $params['view_mode'] == 'chathub_popup')
                        @else
                            <div class="col-lg-4">
                            </div>
                        @endif

                    </div>
                    <div class="row bdb" id="lstItemOrder">
                        <div class="table-responsive">
                            <table class="table m-table m-table--head-bg-default" id="table_add">
                                <thead class="bg">
                                <tr>
                                    <td width="5%" class="tr_thead_od m--font-bolder m--font-transform-u"></td>
                                    <td width="55%" class="tr_thead_od m--font-bolder m--font-transform-u">{{__('Tên')}}</td>
                                    <td width="20%" class="tr_thead_quan m--font-bolder m--font-transform-u">{{__('Số lượng')}}
                                    </td>
                                    <td width="10%" class="tr_thead_od text-center m--font-bolder m--font-transform-u">
                                        {{__('Giảm')}}
                                    </td>
                                    <td width="10%" class="tr_thead_od text-center m--font-bolder m--font-transform-u">{{__('Thành tiền')}}
                                    </td>
{{--                                    <td></td>--}}
                                </tr>
                                </thead>
                                <tbody class="tr_thead_od">

                                </tbody>
                            </table>
                        </div>
                        <span class="error-table" style="color: #ff0000"></span>
                    </div>

                    <div class="form-group row " style="padding-top: 15px !important; padding-bottom: 0px !important;">
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="javascript:void(0)" onclick="order.modal_customer()"
                                           class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm son-mb btn-choose-customer">
                                            <img src="{{asset('static/backend/images/fnb/select-customer.png')}}"> {{__('Chọn khách hàng')}}
                                        </a>
{{--                                        @if($customerLoad != null)--}}
{{--                                        <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)"--}}
{{--                                           class="m-btn m-btn--pill ss--button-cms-piospa btn btn-sm choose_1 son-mb btn-choose-customer btn-choose-customer-temporary {{$customerLoad == null ? 'hide-customer' : ''}}">--}}
{{--                                            <img src="{{asset('static/backend/images/fnb/select-customer.png')}}">{{__('Khách hàng vãng lai')}}</a>--}}
{{--                                        @endif--}}

{{--                                        @if($is_update_order == 1)--}}
{{--                                            <button type="submit" id="btn_add" onclick="order.submitOrder()"--}}
{{--                                                    class="btn btn-fix btn-success btn-lg m-btn son-mb m-btn m-btn--icon btn-save-info-update--}}
{{--                                m-btn--wide m-btn--md btn-add">--}}
{{--                                                <img src="{{asset('static/backend/images/fnb/save-order.png')}}"> {{__('Lưu đơn hàng')}}--}}
{{--                                            </button>--}}
{{--                                        @endif--}}

                                        <button type="submit" id="btn_add" onclick="delivery.showPopup()" style="display:none"
                                                class="btn btn-fix btn-success btn-lg m-btn son-mb m-btn m-btn--icon btn-shipping-address btn-save-info-update
                                                m-btn--wide m-btn--md btn-add">
                                            <img src="{{asset('static/backend/images/fnb/shipping.png')}}"> {{__('Giao hàng')}}
                                        </button>



                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-lg-12 customer">
                                            @if($customerLoad != null)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="block-customer">
                                                                <div class="image-customer">
                                                                    <img src="{{$customerLoad['customer_avatar'] != null ? $customerLoad['customer_avatar'] : asset('static/backend/images/image-user.png')}}" style="width: 52px;height: 52px">
                                                                </div>
                                                                <div class="info-customer">
                                                                    <span class="m-widget4__title m-font-uppercase"><b>{{$customerLoad['full_name']}}</b></span><br>
                                                                    <span class="m-widget4__title m-font-uppercase">{{isset($customerLoad['phone1']) ? ' - '.$customerLoad['phone1'] : ''}}</span><br>
                                                                    <span class="m-widget4__title ">{{$customerLoad['point_rank']}} {{__('điểm')}}</span><br>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8"></div>
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
                                                                    <span class="m-widget4__title m-font-uppercase"><b>{{__('Khách vãng lai')}}</b></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group row m--margin-right-5">
                                <div class="m-list-timeline__items">
                                    <div class="m-list-timeline__item sz_bill d-none">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Nhân viên phục vụ')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest staff_name" style="width: 150px;">

                                        </span>
                                        <input type="hidden" id="staff_id" name="staff_id"
                                               class="form-control staff_id" value="">
                                    </div>
                                    <div class="m-list-timeline__item sz_bill">
                                        <span class="m-list-timeline__text sz_word m--font-boldest">{{__('Tổng tiền')}}
                                            :</span>
                                        <span class="m-list-timeline__time m--font-boldest append_bill" style="width: 150px;">
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
                                        <span class="m-list-timeline__time m--font-boldest discount_bill" style="width: 150px;">
                                            <a href="javascript:void(0)" onclick="order.modal_discount_bill(0)"
                                               class="tag_a">
                                            <i class="fa fa-plus-circle icon-sz" style="color: #0067AC "></i>
                                            </a>
                                            0 @lang('đ')
                                            <input type="hidden" id="discount_bill" name="discount_bill" value="0">
                                            <input type="hidden" id="voucher_code_bill" name="voucher_code_bill" value="">

                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item sz_bill" style="display:none">
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
                                        <span class="m-list-timeline__time m--font-boldest amount_bill" style="color: red; width: 150px;">
                                            0 {{__('đ')}}
                                            <input type="hidden" name="amount_bill_input"
                                                   class="form-control amount_bill_input"
                                                   value="0">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right w-100">

{{--                            <div class="waiter">--}}
{{--                                <button type="button" class="btn btn-info" onclick="order.chooseWaiter()">--}}
{{--                                    <i class="fa fa-user-plus"></i>--}}
{{--                                    {{ __('Nhân viên phục vụ') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    @if(isset($params['view_mode']))
                                        <a href="javascript:void(0)" onclick="cancelBtn();"
                                           class="btn btn-fix btn-metal btn-lg m-btn m-btn--icon m-btn--wide m-btn--md wd_type">
                                            {{__('HỦY')}}
                                        </a>
                                    @else
                                        <a href="{{route('fnb.orders')}}"
                                           class="btn btn-fix btn-metal btn-lg m-btn m-btn--icon m-btn--wide m-btn--md wd_type">
                                            {{__('HỦY')}}
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="form-group col-lg-4">
                            @if($is_update_order == 1)
                                <button type="submit" id="btn_add" onclick="order.submitOrder()"
                                        class="btn btn-fix  btn-lg m-btn son-mb m-btn m-btn--icon wd_type
                                    m-btn--wide m-btn--md btn-add">
                                    {{__('LƯU ĐƠN HÀNG')}}
                                </button>
                            @endif
                        </div>

                        @if($is_payment_order == 1)
                            <div class="form-group col-lg-4">
                                <button type="button" onclick="processPayment.createPaymentAction()" id="btn_order"
                                        class="btn  btn-fix btn-metal btn-lg m-btn son-mb m-btn m-btn--icon wd_type m-btn--wide m-btn--md" style="background-color: #FF794E">
                                    {{__('THANH TOÁN')}}
                                </button>
                            </div>
                        @endif

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
        <form id="form-order-ss" target="_blank" action="{{route('fnb.orders.print-bill')}}" method="GET">
            <input type="hidden" name="ptintorderid" id="orderiddd" value="">
        </form>
        <input type="hidden" value="" class="hiddenOrderIdss">
        <input type="hidden" id="custom_price" name="custom_price" value="{{$customPrice}}">

        @include('admin::orders.modal-discount')
        @include('fnb::orders.customer')
        @include('admin::orders.modal-discount-bill')
        @include('fnb::orders.receipt')
        @include('admin::orders.modal-enter-phone-number')
        @include('admin::orders.modal-enter-email')

        <input type="hidden" id="session" value="{{$session}}">
        <input type="hidden" id="table_id" name="table_id" value="">
        <div id="showPopup"></div>
        <input type="hidden" id="type_time_hidden" name="type_time_hidden" value="">
        <input type="hidden" id="time_address_hidden" name="time_address_hidden" value="">
        <input type="hidden" id="customer_contact_id_hidden" name="customer_contact_id_hidden" value="">
        <div class="popupShow"></div>
        <input type="hidden" id="view_mode" value="{{$params['view_mode'] ?? null}}">
        <input type="hidden" id="ch_full_name" value="{{$params['ch_full_name'] ?? null}}">
        <input type="hidden" id="ch_new_cus" value="{{$params['ch_new_cus'] ?? null}}">
        <input type="hidden" id="ch_customer_id" name="ch_customer_id" value="{{$params['ch_customer_id'] ?? null}}">
    </div>
    <div class="append-popup"></div>
        @stop
        @section('after_script')
            <script>
                var customPrice = {{$customPrice}};
                let numberRow = 0;
                let stt_tr = 0;
                let number_using_voucher = 0;
                var noImage = '{{asset('/uploads/admin/icon/person.png')}}';
            </script>
            <script>
                var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
                var decimalsQuantity = parseInt({{$decimalQuantity}});

                $(document).ready(function () {
                    $('body').addClass('m-brand--minimize m-aside-left--minimize');

                });
            </script>
            <script src="{{asset('static/backend/js/admin/order/html2canvas.min.js')}}" type="text/javascript"></script>

            <!-- html table product/service add order -->
            <script type="text/template" id="table-tpl">
                <tr class="tr_table tr_table_{id} tr_table_{id}_{key_string}">
{{--                    <td class="td_vtc" style="width: 30px;"></td>--}}
                    <td>
                        <div onclick="order.selectTopping('{id}','{key_string}')" class="hover-cursor">
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
                    <input type="hidden" id="numberRow" value="{numberRow}">
                </tr>

            </script>
            <script type="text/template" id="table-tpl-serial">
                <tr class="tr_table">
                    <td class="td_vtc" style="width: 30px;"></td>
                    <td class="td_vtc" style="color: #000; font-weight: 500;">
                        {name}
                        <input type="hidden" name="id" value="{id}">
                        <input type="hidden" name="name" value="{name}">
                        <input type="hidden" name="object_type" value="{type_hidden}">
                        <input type="hidden" name="object_code" class="object_code_{id}" value="{code}">
                    </td>
                    <td class="td_vtc" style="width: 130px;">
                        {price}{{__('đ')}}
                        <input type="hidden" name="price" value="{price_hidden}">
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
                            <a href="javascript:void(0)" id="discount_{stt}" class="abc btn ss--button-cms-piospa m-btn m-btn--icon m-btn--icon-only" onclick="order.modal_discount('{amount_hidden}','{id}','{id_type}','{stt}')">
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
                    <td style="width: 150px; vertical-align: middle;">
                        <select class="form-control staff" name="staff_id" multiple="multiple" style="width: 150px;">
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


            <script type="text/template" id="button-tpl">
{{--                <a href="javascript:void(0)" onclick="order.customer_haunt('1',this)"--}}
{{--                   class="m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary  cus_haunt  choose_1 son-mb"><i--}}
{{--                            class="la la-user icon-sz"></i>{{__('Khách hàng vãng lai')}}</a>--}}
            </script>
            <script type="text/template" id="customer-haunt-tpl">
                <div class="form-group">
                    <div class="row">
                        <div class="col-4">
                            <div class="block-customer">
                                <div class="image-customer">
                                    <img src="{{asset('static/backend/images/image-user.png')}}"
                                         style="width: 52px;height: 52px">
                                </div>
                                <div class="info-customer">
                                    <span class="m-widget4__title m-font-uppercase"><b>{{__('Khách vãng lai')}}</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-8"></div>
                    </div>
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
                <input type="hidden" name="total_bill" id="total_bill" class="form-control total_bill" value="{total_bill}">
            </script>
            <script type="text/template" id="customer-tpl">

                <div class="form-group">
                    <div class="row">
                        <div class="col-4">
                            <div class="block-customer">
                                <div class="image-customer">
                                    <img src="{{'{img}'}}" style="width: 52px;height: 52px">
                                </div>
                                <div class="info-customer">
                                    <span class="m-widget4__title m-font-uppercase"><b>{full_name}</b></span><br>
                                    <span class="m-widget4__title m-font-uppercase">{phone}</span><br>
                                    <span class="m-widget4__title ">{point_rank} {{__('điểm')}}</span><br>

                                </div>
                            </div>
                        </div>
                        <div class="col-8 mt-4">
                            <p>{{__('Mã khách hàng')}} : <strong>{customer_code}</strong></p>
                            <p>{{__('Công nợ')}} : <strong>{debt_money}đ</strong></p>
                            <div class="block-address receipt_info_check_block">
                                <p>{{__('Tên người nhận:')}} : </p>
                                <p>{{__('SĐT người nhận:')}} : </p>
                                <p>{{__('Địa chỉ nhận hàng:')}} : </p>
                                <p>{{__('Thời gian mong muốn nhận hàng:')}} : </p>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            <script type="text/template" id="type-receipt-tpl">
                <div class="row">
                    <label class="col-lg-6 font-13">{label}:<span
                                style="color:red;font-weight:400">{money}</span></label>
                    <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                        <input onkeyup="order.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
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
                        <input type="hidden" id="numberRow" value="">
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
            </script>
            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
            <script src="{{asset('static/backend/js/fnb/owl.carousel.min.js')}}"></script>
            <script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}"
                    type="text/javascript"></script>
            <script src="{{asset('static/backend/js/fnb/order/script.js?v='.time())}}" type="text/javascript"></script>
            {{--    <script src="{{asset('static/backend/js/admin/order/script-order-v2.js?v='.time())}}" type="text/javascript"></script>--}}
            <script src="{{asset('static/backend/js/fnb/order/delivery.js?v='.time())}}" type="text/javascript"></script>
            <script src="{{asset('static/backend/js/admin/general/send-sms-code-service-card.js')}}"
                    type="text/javascript"></script>
            <script src="{{asset('static/backend/js/admin/order/receipt-online/vnpay.js')}}" type="text/javascript"></script>
            <script src="{{asset('static/backend/js/fnb/order/process-payment/process-payment.js?v='.time())}}" type="text/javascript"></script>
            <script src="{{asset('static/backend/js/fnb/order/print-bill.js?v='.time())}}" type="text/javascript"></script>
            <script>
                $(document).ready(function () {
                    $('body').addClass('m-brand--minimize m-aside-left--minimize');
                    $('#suburbward').select2();
                    // order.chooseType('area');
                    order.chooseType('product');
                    // order.fullScreen();
                });
            </script>
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
                        <select class="form-control staff" name="staff_id"  disabled multiple="multiple">
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
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link {active}" data-toggle="tab" href="javascript:void(0)"--}}
{{--                       onclick="order.loadProduct('{category_id}')" data-name="{category_id}"--}}
{{--                       data-name="all" style="text-transform: capitalize;">--}}
{{--                        {category_name}--}}
{{--                    </a>--}}
{{--                </li>--}}
                <div class="item nav-item">
                    <a class="nav-link {active}" data-toggle="tab" href="javascript:void(0)"
                       onclick="order.loadProduct('{category_id}')" data-name="{category_id}"
                       data-name="all" style="text-transform: capitalize;">
                        {category_name}
                    </a>
                </div>
            </script>
            <script>
                @if(isset($params['view_mode']) && $params['view_mode'] == 'chathub_popup')
                if($('#ch_new_cus').val() == 1 ){
                    setTimeout(function(){
                        order.modal_customer();
                    }, 1000)

                }
                function cancelBtn(){
                    window.parent.focus();
                    var bc = new BroadcastChannel('cancelOrder');
                    bc.postMessage({}); /* send */
                    window.close();
                }
                @endif

            </script>
@stop
