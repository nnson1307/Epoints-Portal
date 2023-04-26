<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('static/backend/css/print-bill.css')}}">
    <link rel="shortcut icon" href="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}" />
    <title>In hóa đơn</title>
    <style>
        .receipt {
            /*font-family: "Times New Roman", Times, serif;*/
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
            margin: 0 auto;
        }

        .widhtss {
            margin: 0 auto;
            width: 60mm;
        }

        .mm-mauto {
            margin: 0 auto !important;
        }

        @page {
            width: 100%;
            height: 100%;
            /*margin: 0 auto;*/
        }

        /* output size */
        .receipt .sheet {
            width: 100%;
            height: 100%;
            /*margin: 0*/
            /*float: left;*/
        }

        /* sheet size */
        @media print {
            #PrintArea {
                width: 100%;
                height: 100%;
                font-family: Arial, Helvetica, sans-serif;
                float: left;
                margin: 0 auto;
            }

            hr {
                border: 1px solid !important;
            }
        }

        .roww {
            flex-wrap: wrap;
            box-sizing: border-box;
            position: relative;
            width: 100%;
        }

        .roww:before, .roww:after {
            display: table;
            content: " ";
        }

        .roww:after {
            clear: both;
        }

        .coll-7 {
            width: 65%;
            float: left;
        }

        .coll-3 {
            width: 33%;
            float: left;
        }

        .imgss {
            width: 25mm;
            height: 13mm;
        }

        .tientong:after {
            clear: both;
        }

        .tientong strong:first-child {
            text-align: left;
            font-size: 11px;
            float: left;
        }

        .tientong strong:last-child {
            text-align: right;
            font-size: 11px;
            float: right;
        }

        .ss-font-size-10 {
            font-size: 11px !important;
        }

        .tks {
            font-size: 10px;
            text-align: center;
            width: 100%;
            display: block;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        h4 {
            font-size: 12px;
            text-align: center;
            font-weight: bold;
            margin: 3px 0;
        }

        h5 {
            font-size: 10px;
            margin: 3px 0;
            font-weight: bold;
        }

        .ss-nowap {
            white-space: nowrap;
        }

        .border-bottom {
            border-bottom: 1px dashed;
        }

        .text-align-right {
            text-align: right !important;
        }

        .coll-4 {
            width: 40%;
            float: left;
        }

        .coll-6 {
            width: 60%;
            float: left;
        }
        .pr-1 {
            padding-right: 5px;
        }

        .strong-text{
            font-weight: bold;
        }
    </style>
</head>

<body>
<div id="divToPrint">
    <div class="receipt">
        <section class="sheet">
            <div id="PrintArea">
                <div class="widhtss">
                    <div class="roww">
                        @php
                            $km=0;
                            $count=0;
                            $space=' &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
                        @endphp
                        @if($configPrintBill['is_show_logo']==1)
                            <div class="form-group m-form__group text-center">
                                <img class="imgss" src="{{asset($spaInfo['logo'])}}">
                            </div>
                        @endif
                        <div class="text-right" style="margin: 0 auto">
                            @if($configPrintBill['is_show_unit']==1)
                                <h5 class="text-center ss-font-size-10">
                                    {{$spaInfo['name']}}
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_address']==1)
                                <h5 class="text-center ss-font-size-10">
                                        <span class="ss-font-size-10 text-center">
                                           {{$spaInfo['address']}}
                                        </span>
                                </h5>
                                <h5 class="text-center ss-font-size-10">
                                        <span class="ss-font-size-10 text-center">
                                           {{$spaInfo['district_type'].' '
                                            .$spaInfo['district_name'].' '.$spaInfo['province_name']}}
                                        </span>
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_phone']==1)
                                <h4 class="text-center ss-font-size-10">
                                        <span>
                                           {{$spaInfo['phone']}}
                                       </span>
                                </h4>
                            @endif
                        </div>

                    </div>
                    <hr>

                    <div class="mm-mauto">
                        <h4 class="text-center">{{__('HÓA ĐƠN PHIẾU CHI')}}</h4>
{{--                        <div class="text-center" style="font-size: 9px;">{{$printTime}}</div>--}}
                        {{--                        <div class="text-center" style="font-size: 9px;">(Phiếu làm dịch vụ)</div>--}}
                        @if($configPrintBill['is_show_order_code']==1)
                            <div class="roww text-left">
                                <div>
                                    <h5 class="ss-font-size-10">{{__('HĐ')}}: {{$payment['payment_code']}}
                                    </h5>
                                </div>
                                {{--<div>--}}
                                {{--<h5 class="ss-font-size-10">Ngày in: {{date("d/m/Y H:m:i")}}--}}
                                {{--</h5>--}}
                                {{--</div>--}}
                            </div>
                        @endif
                    </div>
                    <hr>
                    @if($configPrintBill['is_show_customer'] == 1)
                        <div class="mm-mauto">
                            <strong class="ss-font-size-10">
                                {{__('Loại người nhận')}}: {{__($payment['object_accounting_type_name_vi'])}}
                            </strong>
                            <br/>
                            @if($payment['object_accounting_type_code'] == 'OAT_CUSTOMER')
                                <strong class="ss-font-size-10">
                                    {{__('Tên người nhận')}}: {{$payment['customer_name']}}
                                </strong>
                            @elseif($payment['object_accounting_type_code'] == 'OAT_SUPPLIER')
                                <strong class="ss-font-size-10">
                                    {{__('Tên người nhận')}}: {{$payment['supplier_name']}}
                                </strong>
                            @elseif($payment['object_accounting_type_code'] == 'OAT_EMPLOYEE')
                                <strong class="ss-font-size-10">
                                    {{__('Tên người nhận')}}: {{$payment['employee_name']}}
                                </strong>
                            @else
                                <strong class="ss-font-size-10">
                                    {{__('Tên người nhận')}}: {{$payment['accounting_name']}}
                                </strong>
                            @endif
                        </div>
                    @endif
{{--                    @if($configPrintBill['is_show_cashier'] == 1)--}}
{{--                        <div class="mm-mauto">--}}
{{--                            <strong class="ss-font-size-10">{{__('Thu ngân')}}:--}}
{{--                                <strong>Thu ngân 1</strong>--}}
{{--                            </strong>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                    <div class="mm-mauto">
                        <strong class="mm-mauto ss-font-size-10">
                            {{date("d/m/Y H:i:s")}}
                        </strong>
                    </div>
                    <hr>
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7 ss-font-size-10">{{__('Loại phiếu chi')}}</span>
                        <span class="coll-3 text-align-right ss-font-size-10">{{$payment['payment_type_name_vi']}}</span>
                    </div>
                    <hr>
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7 ss-font-size-10">{{__('Lý do chi')}}</span>
                        <span class="coll-3 text-align-right ss-font-size-10">{{$payment['note']}}</span>
                    </div>
                    <hr>
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7 ss-font-size-10">{{__('Số tiền chi')}}</span>
                        <span class="coll-3 text-align-right ss-font-size-10">
                        {{number_format($payment['total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </span>
                    </div>
                    <hr>
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7 ss-font-size-10">{{__('Hình thức thanh toán')}}</span>
                        <span class="coll-3 text-align-right ss-font-size-10">
                            {{$payment['payment_method_name_vi']}}
                        </span>
                    </div>
                    <hr>
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7 ss-font-size-10">{{__('Trạng thái')}}</span>
                            @switch($payment['status'])
                                @case ('new')
                                    <span class="coll-3 text-align-right ss-font-size-10">{{__('Mới')}}</span>
                                    @break
                                @case ('approved')
                                    <span class="coll-3 text-align-right ss-font-size-10">{{__('Đã xác nhận')}}</span>
                                    @break
                                @case ('paid')
                                    <span class="coll-3 text-align-right ss-font-size-10">{{__('Đã chi')}}</span>
                                    @break
                                @case ('unpaid')
                                    <span class="coll-3 text-align-right ss-font-size-10">{{__('Đã huỷ chi')}}</span>
                                    @break
                            @endswitch
                    </div>
                    <hr>
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7 ss-font-size-10">{{__('Mã chứng từ')}}</span>
                        <span class="coll-3 text-align-right ss-font-size-10">
                            {{$payment['document_code']}}
                        </span>
                    </div>
                    <hr>
{{--                    @if($cash!=0)--}}
{{--                        <div class="mm-mauto tientong ss-font-size-10 roww">--}}
{{--                            <i class="coll-7 strong-text">{{__('TIỀN MẶT')}}:</i>--}}
{{--                            <strong class="coll-3">{{number_format($cash, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {!!$space !!}</strong>--}}
{{--                            <span class="coll-3 strong-text text-align-right">{{number_format($cash, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    @if($transfer!=0)--}}
{{--                        <div class="mm-mauto tientong ss-font-size-10 roww">--}}
{{--                            <i class="coll-7 strong-text">{{__('CHUYỂN KHOẢN')}}:</i>--}}
{{--                            <strong class="coll-3">{{number_format($transfer, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {!!$space !!}</strong>--}}
{{--                            <span class="coll-3 strong-text text-align-right">{{number_format($transfer, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    @if($visa!=0)--}}
{{--                        <div class="mm-mauto tientong ss-font-size-10 roww">--}}
{{--                            <i class="coll-7 strong-text">{{__('VISA')}}:</i>--}}
{{--                            <strong class="coll-3">{{number_format($visa, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {!!$space !!}</strong>--}}
{{--                            <span class="coll-3 strong-text text-align-right">{{number_format($visa, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    @if($member_money!=0)--}}
{{--                        <div class="mm-mauto tientong ss-font-size-10 roww">--}}
{{--                            <i class="coll-7 strong-text">{{__('TÀI KHOẢN THÀNH VIÊN')}}:</i>--}}
{{--                            <strong class="coll-3">{{number_format($member_money, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {!!$space !!}</strong>--}}
{{--                            <span class="coll-3 strong-text text-align-right">{{number_format($member_money, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>--}}
{{--                        </div>--}}
{{--                    @endif--}}
                    <hr>
                    <div class="mm-mauto text-center tks">
                        {!! QrCode::size(120)->generate($QrCode); !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="widhtss" style="margin-top: 15px; text-align: right">
        <div style="color: red;margin-bottom: 5px;">
            <span class="error-print-bill font-size-15"></span>
        </div>
        <a class="btn btn-metal btn-sm" onclick="printBill.back()">{{__('THOÁT')}}</a>
        <a onclick="printBill.printBill()" class="btn btn-success btn-sm" style="margin-left: 10px">
            <span>
                <i class="la la-calendar-check-o"></i>
                <span>
                    {{__('IN HÓA ĐƠN')}}
                </span>
            </span>
        </a>
    </div>
</div>
<input type="hidden" id="payment_id" value="{{$paymentId}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
</script>
<script src="{{asset('js/laroute.js') . '?t=' . time()}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/payment/script.js?v='.time())}}" type="text/javascript"></script>
</body>
</html>