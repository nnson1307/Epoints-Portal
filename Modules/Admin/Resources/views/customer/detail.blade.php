<?php $view = 'layout' ?>
@if(isset($params['view_mode']) && $params['view_mode'] == 'chathub_popup')
        <?php $view = 'layout-modal' ?>
@endif
@extends($view)
@section('title_header')
    <span class="title_header">@lang("QUẢN LÝ KHÁCH HÀNG")</span>
@stop
@section('content')
    <style>
        .m-image {
            padding: 5px;
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }

        .myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .myImg:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        .modal-zoom-image {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9); /* Black w/ opacity */
        }

        /* Modal Content (image) */
        #myModal .modal-content {
            margin: auto;
            display: block;
            width: 100%;
            max-width: 700px;
        }

        /* Caption of Modal Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation */
        #myModal .modal-content, #caption {
            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @-webkit-keyframes zoom {
            from {
                -webkit-transform: scale(0)
            }
            to {
                -webkit-transform: scale(1)
            }
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }
            to {
                transform: scale(1)
            }
        }

        /* The Close Button */
        .close_button {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close_button:hover,
        .close_button:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }

        .file_customer {
            white-space: nowrap;
            width: 80%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .scroll-chat {
            min-height: 50px !important;
            max-height: 400px !important;
            overflow-y: scroll;
            margin-bottom: 20px;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"
                                 style="font-size: 13px"></i> @lang("CHI TIẾT KHÁCH HÀNG")</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="m-form__actions m--align-right">
                    @if(isset($params['view_mode']) && $params['view_mode'] == 'chathub_popup')
                        <a href="javascript:void(0)" onclick="window.close()"
                           class="btn btn-metal bold-huy  m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang("QUAY LẠI")</span>
						</span>
                        </a>
                    @else
                        <a href="{{route('admin.customer')}}"
                           class="btn btn-metal bold-huy  m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang("QUAY LẠI")</span>
						</span>
                        </a>
                     
                        @if(in_array('admin.order.add',session('routeList')))
                            <a target="_blank"
                               href="{{route('admin.order.add', ['customer_id' => $item['customer_id']])}}"
                               class="btn btn-info color_button son-mb m--margin-left-10">
                                                <span>
                                                    <i class="fa fa-plus-circle"></i>
                                                    <span> {{__('THÊM ĐƠN HÀNG')}}</span>
                                                </span>
                            </a>
                        @endif

                        <button type="button" class="btn btn-info color_button son-mb m--margin-left-10"
                                data-toggle="modal" data-target="#m_modal_1">
                            <i class="la la-bitcoin"></i>@lang("NHẬP CÔNG NỢ")
                        </button>

                        @if(in_array('admin.customer.print-bill-debt',session('routeList')))
                            <a class="btn btn-info color_button son-mb m--margin-left-10"
                               href="javascript:void(0)" onclick="customer.printBillDebt('{{$item['customer_id']}}')">
                                <i class="la la-print"></i> @lang("IN CÔNG NỢ")
                            </a>
                        @endif

                        @if(in_array('admin.customer.pop-quick-receipt-debt',session('routeList')))
                            <a class="btn btn-info color_button son-mb m--margin-left-10"
                               href="javascript:void(0)"
                               onclick="customer.popQuickReceiptDebt('{{$item['customer_id']}}', '{{$amountDebt}}')">
                                <i class="la la-cc-paypal"></i> @lang("THANH TOÁN NHANH CÔNG NỢ")
                            </a>
                        @endif

                        @if(in_array('admin.customer.edit',session('routeList')))
                            <a class="btn btn-info color_button son-mb m--margin-left-10"
                               href="{{route('admin.customer.edit',$item['customer_id'])}}">
                                <i class="la la-edit"></i> @lang("CHỈNH SỬA THÔNG TIN")
                            </a>
                        @endif

                      
                    @endif

                </div>
            </div>
        </div>
        @include('admin::customer.pop.modal-zoom-image')
        <div class="m-portlet__body">
            <input type="hidden" name="customer_id" id="customer_id" value="{{ $item['customer_id'] }}">

            <div class="row">
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-3">
                            @if($item['customer_avatar']!=null)
                                <img class="m--bg-metal  img-dt  img-sd" id="blah" width="100%"
                                     height="100%"
                                     src="{{$item['customer_avatar']}}"
                                     alt="{{__('Hình ảnh')}}"/>
                            @else
                                <img class="m--bg-metal img-dt  img-sd" id="blah" width="100%"
                                     height="100%"
                                     src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                                     alt="{{__('Hình ảnh')}}"/>
                            @endif
                        </div>
                        <div class="col-lg-9">
                            <span class="font-weight-bold">{{$item['customer_code']}}</span> <br>
                            <span>
                                @switch($item['customer_type'])
                                    @case('personal')
                                        @lang('Cá nhân')
                                        @break
                                    @case('business')
                                        @lang('Doanh nghiệp')
                                        @break
                                @endswitch
                            </span> -
                            <span class="font-weight-bold" style="font-size: 20px;">{{$item['full_name']}}</span> <br>
                            <span>{{$item['phone1']}}</span> <br>
                            <span>{{$item['email']}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <span class="sz_dt">@lang("Công nợ"): <strong style="color: red;">{{number_format($amountDebt)}} @lang('đ')</strong></span> <br>
                    <span class="sz_dt">@lang("Tiền trong ví"): <strong>{{number_format($customer_money)}} @lang('đ')</strong></span> <br>
                    <span class="sz_dt">@lang("Hoa hồng"): <strong>{{number_format($commission_money)}} @lang('đ')</strong></span>&nbsp;
                    @if($commission_money > 0)
                        <a class="btn btn-sm m-btn--icon color" href="javascript:void(0)"
                           onclick="detail.modal_commission('{{$item['customer_id']}}','{{$commission_money}}')">
                            @lang("Quy đổi")
                        </a>
                    @endif
                    <br>
                    <span class="sz_dt">@lang("Tương tác gần nhất"):
                        <strong>{{$item['date_last_visit'] != null ? \Carbon\Carbon::parse($item['date_last_visit'])->format('d/m/Y H:i:s') : __('Chưa xác định')}}</strong>
                    </span>
                </div>
                <div class="col-lg-4">
                    <span class="sz_dt">@lang("Ghi chú gần nhất") -
                        {{$lastNote != null ? \Carbon\Carbon::parse($lastNote['created_at'])->format('d/m/Y H:i:s') : __('Chưa xác định')}}
                    </span> <br>
                    <span>
                        {{$lastNote['note'] ?? ''}}
                    </span>
                </div>
            </div>

            <div class="tab-content m--margin-top-40">
                <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;">
                    @foreach($configTab as $keyTab => $vTab)
                        @if ($vTab['code'] == 'comment')
                            <li class="nav-item">
                                <a class="nav-link son" data-toggle="tab"
                                   href="javascript:void(0)"
                                   onclick="CustomerComment.getListCustomerComment()">@lang('BÌNH LUẬN')</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link son {{$keyTab == 0 ? 'active' : ''}}" data-toggle="tab"
                                   {{$keyTab == 0 ? 'show' : ''}}
                                   href="javascript:void(0)"
                                   onclick="detail.loadTab('{{$vTab['code']}}', '{{$item['customer_id']}}')">{{$vTab['tab_name']}}</a>
                            </li>
                        @endif

                    @endforeach
                </ul>
                <div class="ps ps--active-y m--margin-top-10">
                    <div class="bd-ct tab_detail"></div>
                </div>
            </div>

        </div>
    </div>
    <div id="my-popup"></div>
    <div id="div-detail"></div>
    <div id="div-receipt"></div>
    <div id="show-modal"></div>
    <div id="my-modal-create-lead"></div>
    @include('admin::customer.pop.modal-enter-debt')
    <form id="bill-receipt" target="_blank" action="{{route('admin.receipt.print-bill')}}" method="GET">
        <input type="hidden" id="amount_bill" name="amount_bill">
        <input type="hidden" id="customer_debt_id" name="customer_debt_id">
        <input type="hidden" id="receipt_id" name="receipt_id">
        <input type="hidden" id="amount_return_bill" name="amount_return_bill">
    </form>

    <form id="form-print-bill" target="_blank" action="{{route('receipt.print-bill')}}" method="GET">
        <input type="hidden" name="print_receipt_id" id="receipt_id" value="">
    </form>

    <form id="form-order-ss" target="_blank" action="{{route('admin.order.print-bill-not-receipt')}}" method="GET">
        <input type="hidden" name="ptintorderid" id="orderiddd" value="">
    </form>

    <form id="form-customer-debt" target="_blank" action="{{route('admin.customer.print-bill-debt')}}" method="GET">
        <input type="hidden" name="customer_id" id="customer_id_bill_debt">
    </form>

    <div id="my-modal"></div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};

        var id_customer = {{$item['customer_id']}}
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/customer/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/customer/customer-comment.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        detail._init();
    </script>
    {{--Script của công nợ--}}
    <script src="{{asset('static/backend/js/admin/receipt/script.js?v='.time())}}" type="text/javascript"></script>
    {{--End script công nợ--}}
    <script src="{{asset('static/backend/js/admin/customer-appointment/list-calendar.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/payment/receipt/script.js?v='.time())}}"
            type="text/javascript"></script>
    {{--Script của đơn hàng--}}
    <script src="{{asset('static/backend/js/admin/order/index.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/order/print-bill.js?v='.time())}}" type="text/javascript"></script>
    {{--End script của đơn hàng--}}

    {{--Script của thẻ bảo hành--}}
    <script src="{{asset('static/backend/js/warranty/warranty-card/script.js')}}"
            type="text/javascript"></script>

    {{--End script của thẻ bảo hành--}}
    <script type="text/template" id="type-receipt-tpl">
        <div class="row">
            <label class="col-lg-6 font-15">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group col-lg-6" style="height: 30px;">
                <input onkeyup="indexDebt.changeAmountReceipt(this)" style="color: #008000"
                       class="form-control m-input amount" placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="{name_cash}" id="{id_cash}" value="0">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="payment_method_tpl">
        <div class="row mt-3 method payment_method_{id}" style="margin-bottom: 2rem">
            <label class="col-lg-4 font-15">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group col-lg-6" style="height: 30px;">
                <input onkeyup="indexDebt.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
                       placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="payment_method" id="payment_method_{id}" value="0">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
                </div>
            </div>
            <div class="col-lg-2" style="display:{displayQrCode};">
                <button type="button" onclick="indexDebt.genQrCode(this, '{id}')"
                        class="btn btn-primary m-btn m-btn--custom color_button">
                    @lang('TẠO QR')
                </button>
            </div>
        </div>
    </script>

    <script type="text/template" id="append-status-other-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info  color_button active" id="new"
                   onclick="customer_appointment.new_click()">
                <input type="radio" name="status" id="option1" value="new"
                       autocomplete="off" checked=""> {{__('MỚI')}}
            </label>
            <label class="btn btn-default" id="confirm"
                   onclick="customer_appointment.confirm_click()">
                <input type="radio" name="status" id="option2" value="confirm"
                       autocomplete="off"> {{__('XÁC NHẬN')}}
            </label>
        </div>
    </script>
    <script type="text/template" id="append-status-live-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info color_button active" id="wait">
                <input type="radio" name="status" id="option1" value="wait"
                       autocomplete="off" checked=""> {{__('CHỜ PHỤC VỤ')}}
            </label>
        </div>
    </script>
    <script type="text/template" id="table-card-tpl">
        <tr class="tr_quantity tr_card">
            <td>{name}
                <input type="hidden" name="customer_order" id="customer_order_{stt}" value="{stt}">
                <input type="hidden" name="object_type" id="object_type" value="{type}">
            </td>
            <td>
                <select class="form-control service_id" name="service_id" id="service_id_{stt}"
                        style="width: 100%" multiple="multiple">
                    <option></option>
                </select>
            </td>
            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                <select class="form-control staff_id" name="staff_id" id="staff_id_{stt}"
                        title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                <select class="form-control room_id" name="room_id" id="room_id_{stt}"
                        title="{{__('Chọn phòng')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
        </tr>
    </script>
    <script type="text/template" id="to-date-tpl">
        @if($configToDate == 1)
            <div class="form-group m-form__group row">
                <div class="form-group col-lg-6">
                    <label class="black-title">{{__('Ngày kết thúc')}}:<b
                                class="text-danger">*</b></label>
                    <div class="input-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input class="form-control m-input" name="end_date"
                                   id="end_date"
                                   readonly
                                   placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                                   value="">
                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                            class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group col-lg-6">
                    <label class="black-title">{{__('Giờ kết thúc')}}:<b
                                class="text-danger">*</b></label>
                    <div class="input-group m-input-group">
                        <input id="end_time" name="end_time" class="form-control"
                               placeholder="{{__('Chọn giờ hẹn')}}">
                    </div>
                </div>
            </div>
        @endif
    </script>
    <script type="text/template" id="w-m-y-tpl">
        <div class="form-group m-form__group">
            <label class="black-title">{{__('Số tuần/tháng/năm')}}:<b class="text-danger">*</b></label>
            <input class="form-control" id="type_number" name="type_number" value="1"
                   onchange="customer_appointment.changeNumberTime()">
        </div>
        @if($configToDate == 1)
            <div class="form-group m-form__group row">
                <div class="form-group col-lg-6">
                    <label class="black-title">{{__('Ngày kết thúc')}}:<b
                                class="text-danger">*</b></label>
                    <div class="input-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input class="form-control m-input" name="end_date"
                                   id="end_date"
                                   readonly
                                   placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                                   value="">
                            <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                            class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group m-form__group col-lg-6">
                    <label class="black-title">{{__('Giờ kết thúc')}}:<b
                                class="text-danger">*</b></label>
                    <div class="input-group m-input-group">
                        <input id="end_time" name="end_time" class="form-control"
                               placeholder="{{__('Chọn giờ hẹn')}}">
                    </div>
                </div>
            </div>
        @endif
    </script>
    <script>
        function registerSummernote(element, placeholder, max, callbackMax) {
            $('.description').summernote({
                placeholder: '',
                tabsize: 2,
                height: 100,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            uploadImgCk(files[i]);
                        }
                    },
                    onKeydown: function (e) {
                        var t = e.currentTarget.innerText;
                        if (t.length >= max) {
                            //delete key
                            if (e.keyCode != 8)
                                e.preventDefault();
                            // add other keys ...
                        }
                    },
                    onKeyup: function (e) {
                        var t = e.currentTarget.innerText;
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    },
                    onPaste: function (e) {
                        var t = e.currentTarget.innerText;
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        // var all = t + bufferText;
                        var all = bufferText;
                        document.execCommand('insertText', false, all.trim().substring(0, max - t.length));
                        // document.execCommand('insertText', false, bufferText);
                        if (typeof callbackMax == 'function') {
                            callbackMax(max - t.length);
                        }
                    }
                },
            });
        }

        new AutoNumeric.multiple('#amount_debt', {
            currencySymbol: '',
            decimalCharacter: '.',
            digitGroupSeparator: ',',
            decimalPlaces: decimal_number,
            minimumValue: 0
        });

        @if (count($configTab) > 0)
        detail.loadTab('{{$configTab[0]['code']}}', '{{$item['customer_id']}}');
        @endif
    </script>

    <script src="{{asset('static/backend/js/customer-lead/customer-deal/script.js')}}" type="text/javascript"></script>
@stop
