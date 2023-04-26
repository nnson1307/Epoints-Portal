@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ ĐƠN HÀNG')}}</span>
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
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"></i> {{__('CHI TIẾT ĐƠN HÀNG ONLINE')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('admin.order-app')}}"
                       class="btn btn-metal bold-huy  m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('QUAY LẠI')}}</span>
						</span>
                    </a>
                    @if ($order['receive_at_counter'] != 1)
                        @if(isset($order['delivery_active']))
                            @if($order['delivery_active'] == 0)
                                <button class="btn btn-info color_button m-btn--md m--margin-left-10"
                                        onclick="detail.updateStatusDelivery({{$order['delivery_id']}})">
                                    {{__('TẠO ĐƠN HÀNG CẦN GIAO')}}
                                </button>
                            @endif
                        @else
                            <button class="btn btn-info color_button m-btn--md m--margin-left-10"
                                    onclick="detail.addDelivery()">
                                {{__('TẠO ĐƠN HÀNG CẦN GIAO')}}
                            </button>
                        @endif
                    @endif
                    @if(in_array($order['process_status'], ['paysuccess', 'new', 'pay-half']))
                        <button class="btn btn-info  color_button m--margin-left-10" onclick="submitPrint()">
                            <i class="la la-print"></i> {{__('IN HÓA ĐƠN')}}
                        </button>
                    @endif
                    @if($order['process_status'] == "new")
                        <a class="btn btn-info color_button m--margin-left-10"
                           href="{{route('admin.order-app.receipt', $order['order_id']) . '?type=order-online'}}">
                            <i class="la la-file-text"></i> {{__('THANH TOÁN')}}
                        </a>
                    @endif
                    @if ($isCreateContract == 1)
                        <a class="btn btn-info color_button m--margin-left-10"
                           href="{{route('contract.contract.create', ['order_code' => $order['order_code']])}}">
                            {{__('TẠO HỢP ĐỒNG')}}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active show" data-toggle="tab" href="#m_staff_detail_3_1">
                        @lang('Thông tin đơn hàng')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#m_staff_detail_3_2">
                        @lang('Thông tin thanh toán')
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="m_staff_detail_3_1" role="tabpanel">

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="m-widget13">

                                <div class="m-widget13__item">
                                    <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                        Tên khách hàng:
                                    </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        @if($order['customer_id']!=1)
                                            {{$order['full_name']}}
                                        @else
                                            {{__('Khách hàng vãng lai')}}
                                        @endif

                                    </span>
                                </div>
                                <div class="m-widget13__item">
                                    <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                        Loại khách hàng:
                                    </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        @if($order['customer_id']!=1)
                                            {{__('Thành viên')}}
                                        @else
                                            {{__('Khách mới')}}
                                        @endif

                                    </span>
                                </div>
                                @if($order['customer_id']!=1)
                                    <div class="m-widget13__item">
                                    <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                        Sđt khách hàng:
                                    </span>
                                        <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        <i class="la la-phone"></i> {{$order['phone1']}}
                                    </span>
                                    </div>
                                @endif
                                @if($order['receive_at_counter'] != 1)
                                    <div class="m-widget13__item">
                                        <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                            {{__('Người nhận')}}:
                                        </span>
                                        <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                            {{$detailAddress != null ? $detailAddress['customer_name'] : ''}}
                                        </span>
                                    </div>
                                    <div class="m-widget13__item">
                                        <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                            {{__('Sđt người nhận')}}:
                                        </span>
                                        <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                            {{$detailAddress != null ? $detailAddress['customer_phone'] : ''}}
                                        </span>
                                    </div>
                                    <div class="m-widget13__item">
                                        <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                            {{__('Địa chỉ người nhận')}}:
                                        </span>
                                        <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                            {{$detailAddress != null ? $detailAddress['address'].','.$detailAddress['ward_name'].','.$detailAddress['district_name'].','.$detailAddress['province_name'] : ''}}
                                        </span>
                                    </div>
                                @endif
                                <div class="m-widget13__item">
                                    <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                        {{__('Ngày hẹn trả hàng')}}:
                                    </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        {{\Carbon\Carbon::parse($order['delivery_date'])->format('d/m/Y')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="m-widget13">
                                <div class="m-widget13__item">
                                <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                    {{__('Mã đơn hàng')}}:
                                </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                       {{ $order['order_code'] }}
                                    </span>
                                </div>
                                <div class="m-widget13__item">
                                <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                    {{__('Trạng thái đơn hàng')}}:
                                </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                @if($order['process_status']=='paysuccess')
                                            <span class="m-badge m-badge--primary m-badge--wide">{{__('Đã thanh toán')}}</span>
                                        @elseif($order['process_status']=='pay-half')
                                            <span class="m-badge m-badge--info m-badge--wide">{{__('Thanh toán còn thiếu')}}</span>
                                        @elseif($order['process_status']=='new')
                                            <span class="m-badge m-badge--success m-badge--wide">{{__('Mới')}}</span>
                                        @elseif($order['process_status']=='ordercancle')
                                            <span class="m-badge m-badge--danger m-badge--wide">{{__('Đã hủy')}}</span>
                                        @elseif($order['process_status']=='confirmed')
                                            <span class="m-badge m-badge--warning m-badge--wide">{{__('Đã xác nhận')}}</span>
                                        @endif
                                </span>
                                </div>

                                @if($receipt!=null)
                                    <div class="m-widget13__item">
                                    <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                        {{__('Thu ngân:')}}
                                    </span>
                                        <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        {{$receipt['full_name']}}
                                    </span>
                                    </div>
                                    <div class="m-widget13__item">
                                    <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                        {{__('Thời gian thu ngân:')}}
                                    </span>
                                        <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        {{$receipt['created_at'] != null ? date("H:i d/m/Y", strtotime($receipt['created_at'])) : ''}}
                                    </span>
                                    </div>


                                @endif

                                <div class="m-widget13__item">
                                <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                    {{__('Ghi chú')}}:
                                </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                    {{$order['order_description']}}
                                </span>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="m-widget13">
                                <div class="m-widget13__item">
                                <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                    {{__('Tổng tiền cần thanh toán')}}:
                                </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        {{number_format($order['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                    </span>
                                </div>

                                <div class="m-widget13__item">
                                <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                    {{__('Tổng tiền đã thanh toán')}}:
                                </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        {{number_format($amountPaid, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                    </span>
                                </div>

                                <div class="m-widget13__item">
                                <span class="m-widget13__desc m--align-right" style="width: 40%;">
                                    {{__('Còn nợ')}}:
                                </span>
                                    <span class="m-widget13__text m-widget13__text-bolder" style="text-align:left">
                                        {{number_format($order['amount'] - $amountPaid > 0 ? $order['amount'] - $amountPaid : 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                    </span>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table m-table">
                                    <thead style="white-space: nowrap;">
                                    <tr class="bg">
                                        <th class="tr_thead_od_detail"></th>
                                        <th class="tr_thead_od_detail">{{__('TÊN DỊCH VỤ')}}</th>
                                        <th class="tr_thead_od_detail">{{__('GIÁ DỊCH VỤ')}}</th>
                                        <th class="tr_thead_od_detail text-center">{{__('SỐ LƯỢNG')}}</th>
                                        <th class="tr_thead_od_detail text-center">{{__('GIẢM GIÁ')}}</th>
                                        <th class="tr_thead_od_detail text-center">{{__('MÃ GIẢM GIÁ')}}</th>
                                        <th class="tr_thead_od_detail text-center">{{__('NHÂN VIÊN PHỤC VỤ')}}</th>
                                        <th class="tr_thead_od_detail text-center">{{__('GHI CHÚ')}}</th>
                                        <th class="tr_thead_od_detail" style="text-align: right">{{__('TỔNG TIỀN')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($oder_detail as $item)
                                        <tr>
                                            <td style="text-align: center" style="vertical-align: middle;">
                                                <img class="m--bg-metal m-image img-sd myImg" src="{{$item['object_image']}}"
                                                     alt="Hình ảnh" width="50px" height="50px"
                                                     onclick="detail.zoomImage('{{$item['object_image']}}')">
                                            </td>
                                            <td style="vertical-align: middle;">
                                                @if($item['object_type'] == "service")
                                                    <a target="_blank" href="{{route("admin.service.detail",$item['object_id'])}}">
                                                        {{$item['object_name']}}
                                                    </a>
                                                @elseif($item['object_type'] == "product")
                                                    <a target="_blank"  href="{{route('admin.product-child-new.detail',$item['object_id'])}}">
                                                        {{$item['object_name']}}
                                                    </a>
                                                @elseif($item['object_type'] == "service_card")
                                                    <a target="_blank"   href='{{route("admin.service-card.detail",$item['object_id'])}}'>
                                                        {{$item['object_name']}}
                                                    </a>
                                                @elseif($item['object_type'] == "member_card")
                                                    <a target="_blank"   href='{{route("admin.service-card.detail",$item['object_id'])}}'>
                                                        {{$item['object_name']}}
                                                    </a>
                                                @else
                                                    {{$item['object_name']}}
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;">{{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')</td>
                                            <td class="text-center" style="vertical-align: middle;">{{number_format($item['quantity'], 1)}}</td>
                                            <td class="text-center" style="vertical-align: middle;">{{number_format($item['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')</td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                @if($item['voucher_code']!=null)
                                                    {{$item['voucher_code']}}
                                                @else
                                                    {{--                                           {{__('Không có')}}--}}
                                                @endif

                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                {{$item['full_name']}}
                                            </td>
                                            <td class="text-center" style="vertical-align: middle;">
                                                {{$item['note']}}
                                            </td>
                                            <td style="text-align: right; vertical-align: middle;">
                                                {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                            </td>
                                        </tr>
                                        @if(isset($listOrderDetailSerial[$item['order_detail_id']]))
                                            <tr>
                                                <td colspan="5">
                                                    <h4>
                                                        @foreach($listOrderDetailSerial[$item['order_detail_id']] as $keySerial => $itemSerial)
                                                            @if($keySerial <= 9)
                                                                <span class="badge badge-pill badge-secondary">{{$itemSerial['serial']}} </span>
                                                            @endif
                                                        @endforeach
                                                    </h4>
                                                </td>
                                                <td>
                                                    @if(count($listOrderDetailSerial[$item['order_detail_id']]) > 10)
                                                        <a href="javascript:void(0)"
                                                           onclick="detail.showPopupSerial('{{$item['order_detail_id']}}','{{$item['object_code']}}')">{{__('Xem thêm')}}</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        @if (count($item['attach']) > 0)
                                            @foreach($item['attach'] as $v)
                                                <tr>
                                                    <td></td>
                                                    <td>{{$v['object_name']}}</td>
                                                    <td>
                                                        {{number_format($v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                                    </td>
                                                    <td class="text-center">{{number_format($v['quantity'], 1)}}</td>
                                                    <td colspan="6"></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="2" rowspan="6">
                                            <div class="form-group m-form__group">
                                                <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                                   onclick="detail.showModalImage('before')">
                                                    <i class="fa fa-plus-circle"></i> @lang('Ảnh trước khi sử dụng')
                                                </a>
                                            </div>
                                            <div class="div_image_before image-show row">
                                                @if(count($orderImage) > 0)
                                                    @foreach($orderImage as $v)
                                                        @if ($v['type'] == 'before')
                                                            <div class="wrap-img image-show-child">
                                                                <img class="m--bg-metal m-image img-sd myImg" src="{{$v['link']}}"
                                                                     alt="Hình ảnh" width="100px" height="100px"
                                                                     onclick="detail.zoomImage('{{$v['link']}}')">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td colspan="2" rowspan="6">
                                            <div class="form-group m-form__group">
                                                <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                                   onclick="detail.showModalImage('after')">
                                                    <i class="fa fa-plus-circle"></i> @lang('Ảnh sau khi sử dụng')
                                                </a>
                                            </div>
                                            <div class="div_image_after image-show row">
                                                @if(count($orderImage) > 0)
                                                    @foreach($orderImage as $v)
                                                        @if ($v['type'] == 'after')
                                                            <div class="wrap-img image-show-child">
                                                                <img class="m--bg-metal m-image img-sd myImg" src="{{$v['link']}}"
                                                                     alt="Hình ảnh" width="100px" height="100px"
                                                                     onclick="detail.zoomImage('{{$v['link']}}')">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td colspan="3" style="text-align: right;">
                                            {{__('Tổng tiền')}}:
                                        </td>
                                        <td style="text-align: right; ">
                                            <b>
                                                {{number_format($order['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right; border-top: none; ">
                                            {{__('Chiết khấu thành viên')}}:
                                        </td>
                                        <td style="text-align: right; border-top: none; ">
                                            <b>{{$order['discount_member'] != null ? number_format($order['discount_member'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : 0}} @lang('đ')</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right; border-top: none; ">
                                            {{__('Giảm giá')}}:
                                        </td>
                                        <td style="text-align: right; border-top: none; ">
                                            <b>{{number_format($order['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right; border-top: none; ">
                                            {{__('Mã giảm giá')}}:
                                        </td>
                                        <td style="text-align: right; border-top: none; ">
                                            <b>
                                                @if($order['voucher_code']!=null)
                                                    {{$order['voucher_code']}}
                                                @endif
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right; border-top: none; ">
                                            {{__('Phí vận chuyển')}}:
                                        </td>
                                        <td style="text-align: right; border-top: none; ">
                                            <b>
                                                {{number_format($order['tranport_charge'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: right; border-top: none; ">
                                            {{__('Thành tiền')}}:
                                        </td>
                                        <td style="text-align: right; border-top: none; ">
                                            <b>
                                                {{number_format($order['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('đ')}}
                                            </b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="m_staff_detail_3_2" role="tabpanel">
                    <div class="m-portlet__body">
                        <div class="tab-content">
                            <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;">
                                <li class="nav-item">
                                    <a class="nav-link active son" data-toggle="tab" show
                                       id="order_receipt">@lang("LỊCH SỬ THANH TOÁN")</a>
                                </li>
                            </ul>
                            <div class="bd-ct">
                                <div id="div_receipt" style="display: block">
                                    <div class="table-responsive">
                                        <table class="table table-striped m-table m-table--head-bg-default">
                                            <thead class="bg">
                                            <tr>
                                                <th class="tr_thead_list">@lang('MÃ PHIẾU')</th>
                                                <th class="tr_thead_list">@lang('LOẠI PHIẾU')</th>
                                                <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
                                                <th class="tr_thead_list">@lang('NGƯỜI TẠO')</th>
                                                <th class="tr_thead_list">@lang('SỐ TIỀN THU')</th>
                                                <th class="tr_thead_list">@lang('NGÀY GHI NHẬN')</th>
                                                <th class="tr_thead_list">@lang('NGÀY THANH TOÁN')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($receiptOrder) && count($receiptOrder) > 0)
                                                @foreach ($receiptOrder as $key => $v)
                                                    <tr>
                                                        <td>
                                                            <a target="_blank" href="{{route('receipt.show', $v['receipt_id'])}}">
                                                                {{$v['receipt_code']}}
                                                            </a>
                                                        </td>
                                                        <td>{{$v['receipt_type_name']}}</td>
                                                        <td>
                                                            @switch($v['status'])
                                                                @case('unpaid') {{__('Chưa thanh toán')}} @break
                                                                @case('part-paid') {{__('Thanh toán một phần')}} @break
                                                                @case('paid') {{__('Đã thanh toán')}} @break
                                                                @case('cancel') {{__('Hủy')}} @break
                                                                @case('fail') {{__('Lỗi')}} @break
                                                            @endswitch
                                                        </td>
                                                        <td>{{$v['full_name']}}</td>
                                                        <td>{{number_format($v['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                                        <td>{{\Carbon\Carbon::parse($v['created_at'])->format('d/m/Y H:i')}}</td>
                                                        <td>
                                                            @if ($v['status'] == 'paid')
                                                                {{\Carbon\Carbon::parse($v['updated_at'])->format('d/m/Y H:i')}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="order_id" value="{{$order['order_id']}}">
        <input type="hidden" id="customer_id" value="{{$order['customer_id']}}">
        <input type="hidden" id="contact_name" value="{{$order['full_name']}}">
        <input type="hidden" id="contact_phone" value="{{$order['phone1']}}">
        <input type="hidden" id="contact_address" value="{{$order['address']}}">
    </div>
    <form id="form-order-ss" target="_blank" action="{{route('admin.order.print-bill2')}}" method="GET">
        <input type="hidden" name="ptintorderid" id="orderiddd" value="{{$order['order_id']}}">
    </form>

    @include('admin::orders.pop.pop-image-before')
    @include('admin::orders.pop.pop-image-after')
    @include('admin::orders.pop.pop-zoom-image')
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section("after_script")
    <script src="{{asset('static/backend/js/admin/order-app/detail.js?v='.time())}}" type="text/javascript"></script>
    <script>
        function submitPrint() {
            $('#form-order-ss').submit();
        }
    </script>
    <script>
        detail.dropzoneBefore();
        detail.dropzoneAfter();
    </script>
    <script type="text/template" id="tpl-image">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img-order" value="{imageName}">
            <img class="m--bg-metal m-image img-sd " src="{imageName}" alt="Hình ảnh" width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                <a href="javascript:void(0)" onclick="detail.removeImage(this)">
                    <i class="la la-close"></i>
                </a>
            </span>
        </div>
    </script>
@stop
