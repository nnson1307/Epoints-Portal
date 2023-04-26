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
                                        @if(count($dataDetail)>0)
                                            @foreach($dataDetail as $k=> $v)
                                                <tr class="tr_table">
                                                    <td></td>
                                                    <td>{{$v['object_name']}}
                                                        <input type="hidden" name="id" value="{{$v['object_id']}}">
                                                        <input type="hidden" name="name" value="{{$v['object_name']}}">
                                                        <input type="hidden" name="object_type"
                                                               value="{{$v['object_type']}}">
                                                        <input type="hidden" name="object_code"
                                                               value="{{$v['object_code']}}">

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
                                                    <td class="discount-tr-{{$v['object_type']}}-{{$v['object_id']}}-{{$v['number_ran']}} text-center">
                                                        <input type="hidden" name="discount"
                                                               class="form-control discount"
                                                               value="0" maxlength="11">
                                                        <input type="hidden" name="voucher_code" value="">

                                                        @if($is_edit_full == 1)
                                                        @if ($customPrice == 0 || $v['object_type'] != 'member_card')
                                                            <a class="abc m-btn m-btn--pill m-btn--hover-brand-od btn btn-sm btn-secondary btn-sm-cus"
                                                               href="javascript:void(0)"
                                                               onclick="order.modal_discount('{{$v['price']*$v['quantity']}}','{{$v['object_id']}}',1,'{{$v['number_ran']}}')"><i
                                                                        class="la la-plus icon-sz"></i>
                                                            </a>
                                                        @endif
                                                        @endif
                                                    </td>
                                                    <td class="amount-tr text-center">
                                                        @if (isset($customPrice) && $customPrice == 1 && in_array($v['object_type'], ['service', 'product', 'service_card']))
                                                            <input name="amount" style="text-align: center;" class="form-control amount" id="amount_{{$v['number_ran']}}"
                                                                   value="{{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                        @else
                                                            {{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}@lang('đ')
                                                            <input type="hidden" name="amount" class="form-control amount"
                                                                   value="{{$v['amount']}}">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select class="form-control staff" name="staff_id"
                                                                {{ $is_edit_staff == 0 ? 'disabled' : '' }}
                                                                multiple="multiple"
                                                            style="width:80%;">
                                                            <option></option>
                                                            @foreach($optionStaff as $key => $value)
                                                                <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="number_ran"
                                                               value="{{$v['number_ran']}}">
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
                        <input type="hidden" name="order_id" id="order_id" value="">
                        <input type="hidden" name="order_code" id="order_code" value="">
                        <input type="hidden" name="deal_id" id="deal_id" value="{{$item['deal_id']}}">
                        <input type="hidden" name="deal_code" id="deal_code" value="{{$item['deal_code']}}">
                        <input type="hidden" name="customer_id" id="customer_id" value="{{$item['customer_id']}}">
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
                                     {{$item['customer_full_name']}}
                                        <span class="m-badge m-badge--success vanglai"
                                              data-toggle="m-tooltip" data-placement="top" title=""
                                              data-original-title="{{$item['customer_id'] != 1 ? 'Thành viên' : 'Khách mới'}}">
                                    </span>
                                     </span>
                                    <br>
                                    <span class="m-widget4__title m-font-uppercase">
                                                        <i class="flaticon-support m--margin-right-5"></i>
                                                        {{$item['customer_phone']}}
                                                    </span>
                                    <br>
                                    <span class="m-widget4__title">
                                        @lang('Hạng'): {{$item['member_level_name'] != null ? $item['member_level_name'] : __('Thành Viên')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">

                        </div>
                    </div>
                    <div class="row m--margin-bottom-25">
                        <div class="col-lg-12">
                            <select class="form-control"
                                    {{$is_edit_full == 0 ? 'disabled' : ''}}
                                    id="refer_id" name="refer_id" style="width:100%;">
                                <option></option>
                                @foreach($optionCustomer as $key => $value)
                                    @if($value['customer_id'] != 1)
                                        <option value="{{$value['customer_id']}}">{{$value['full_name']}}</option>
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
                        <button type="button" id="btn_order" onclick="processPayment.createPaymentAction('deal')"
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
                        <button type="button" id="btn_add" onclick="order.save_order('{{$item['deal_code']}}')"
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
        <input type="hidden" name="ptintorderid" id="order_id_to_print" value="">
    </form>
    <input type="hidden" id="custom_price" name="custom_price" value="{{$customPrice}}">
    <input type="hidden" id="order_source_id" value="{{$item['order_source_id']}}">
    <input type="hidden" name="member_money" id="member_money" value="{{isset($memberMoney) ? $memberMoney : 0}}">

    <form id="form-customer-debt" target="_blank" action="{{route('admin.customer.print-bill-debt')}}" method="GET">
        <input type="hidden" name="customer_id" id="customer_id_bill_debt">
    </form>
@endsection
@section('after_script')
    <script>
        var customPrice = {{$customPrice}};

        $(document).ready(function () {
            $('body').addClass('m-brand--minimize m-aside-left--minimize');

            @if(count($getTab) > 0)
            order.chooseType('{{$getTab[0]['code']}}');
            @endif
        });
    </script>
    <script src="{{asset('static/backend/js/admin/order/html2canvas.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/general/jquery.printPage.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-deal/receipt.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/receipt-online/vnpay.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/process-payment/process-payment.js?v='.time())}}" type="text/javascript"></script>
    <script>
        order._init();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/admin/general/send-sms-code-service-card.js?v='.time())}}"
            type="text/javascript"></script>

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
@stop
