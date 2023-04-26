<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>In hóa đơn</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('static/backend/css/print-bill.css')}}">
    <link rel="shortcut icon" href="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}"/>
    <style>
        .receipt {
            /*font-family: "Times New Roman", Times, serif;*/
            font-family: Arial, Helvetica, sans-serif;
            width: 69mm;
            margin: 0 auto;
        }

        .widhtss {
            margin: 0 auto;
            width: 66mm;
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
                width: 71mm;
                height: 100%;
                font-family: Arial, Helvetica, sans-serif;
                float: right;

                /*Canh giữa trên trình duyệt nhưng in ra lệch phải*/

                /*position:absolute;*/
                /*width: 300px;*/
                /*height: 100%;*/
                /*z-index:15;*/
                /*top:42mm;*/
                /*left:50%;*/
                /*margin:-150px 0 0 -150px;*/
            }

            .width-table {
                width: 71mm;
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
            width: 70%;
            float: left;
        }

        .coll-3 {
            width: 30%;
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

        .width-collumn-16 {
            width: 16% !important;
        }

        .width-collumn-11 {
            width: 11% !important;
        }

        .width-collumn-55 {
            width: 29mm !important;
            max-width: 29mm !important;
        }

        .ss-nowap {
            white-space: nowrap;
        }

        .width-table {
            width: 66mm;
        }

        .text-align-right {
            text-align: right !important;
        }

        .border-bottom {
            border-bottom: 1px dashed;
        }

        .font-size-15 {
            font-size: 15px
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
                        @if($configPrintBill['is_show_logo']==1)
                            <div class="form-group m-form__group text-center">
                                <img class="imgss" src="{{$spaInfo['logo']}}">
                            </div>
                        @endif
                        <div class="text-right" style="margin: 0 auto">
                            @if($configPrintBill['is_show_unit']==1)
                                <h5 class="text-center ss-font-size-10">
                                    {{$branchInfo['branch_name']}}
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_address']==1)
                                <h5 class="text-center ss-font-size-10">
                                        <span class="ss-font-size-10 text-center">
                                            {{$branchInfo['address'].' '.$branchInfo['district_type'].' '
                                            .$branchInfo['district_name'].' '.$branchInfo['province_name']}}
                                        </span>
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_phone']==1)
                                <h4 class="text-center ss-font-size-10">
                                        <span>
                                           {{$branchInfo['hot_line']}}
                                       </span>
                                </h4>
                            @endif
                        </div>

                    </div>
                    <hr>

                    <div class="mm-mauto">
                        <h4 class="text-center">{{__('HÓA ĐƠN PHIẾU THU')}}</h4>
{{--                        <div class="text-center" style="font-size: 9px;">{{$printTime}}</div>--}}
                        @if($configPrintBill['is_show_order_code']==1)
                            <div class="roww text-left">
                                <div>
                                    <h5 class="ss-font-size-10">{{__('HĐ')}}: {{$receipt['receipt_code']}}
                                    </h5>
                                </div>
                            </div>
                        @endif
                    </div>
                    <hr>
                    @if($configPrintBill['is_show_customer']==1)
                        <div class="mm-mauto">
                            <strong class="ss-font-size-10">
                                {{__('KHÁCH HÀNG')}}:
                                @if ($receipt['object_type'] != 'debt' && $receipt['order_id'] === 0)
                                    {{$receipt['object_accounting_name']}}
                                @elseif ($receipt['object_type'] == 'debt')
                                    {{$receipt['customer_name_debt']}}
                                @else
                                    {{$receipt['customer_name']}}
                                @endif
                            </strong>
                        </div>
                    @endif
                    @if($configPrintBill['is_show_cashier']==1)
                        <div class="mm-mauto">
                            <strong class="ss-font-size-10">{{__('Thu ngân')}}:
                                <strong>{{$receipt['staff_name']}}</strong>
                            </strong>
                        </div>
                    @endif
                    <div class="mm-mauto">
                        <strong class="mm-mauto ss-font-size-10">
                            {{--                                T.g mua: {{date("H:i d/m/Y",strtotime($receipt['created_at']))}}--}}
                            {{date("d/m/Y H:i:s")}}
                        </strong>
                    </div>

                    <hr>
                    @php
                        $km=0;
                        $count=0;
                    @endphp
                    <div class="tientong roww ss-font-size-10" style="font-weight: bold;">
                        <span class="coll-7">{{__('Tên SP/DV')}}</span>
                        <span class="coll-3 text-align-right">{{__('Tổng tiền')}}</span>
                    </div>
{{--                    @foreach($oder_detail as $item)--}}
{{--                        @php--}}
{{--                            $km+=$item['discount'];--}}
{{--                            $count++;--}}
{{--                        @endphp--}}
{{--                        <span class="ss-font-size-10">--}}
{{--                            {{$item['object_name']}}--}}
{{--                        </span>--}}
{{--                        <div class="tientong roww ss-font-size-10">--}}
{{--                            <span class="coll-7">--}}
{{--                                ({{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}})--}}
{{--                            </span>--}}
{{--                            <span class="coll-3 text-align-right">--}}
{{--                                {{number_format($item['amount'])}}--}}
{{--                                ({{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}})--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                        @if($item['object_type']=='member_card')--}}
{{--                            <div class="tientong roww ss-font-size-10">--}}
{{--                                <span class="coll-7">{{$item['object_code']}}</span>--}}
{{--                                <span class="coll-3 text-align-right"></span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if($item['discount']!=0)--}}
{{--                            <div class="tientong roww ss-font-size-10">--}}
{{--                                <span class="coll-7">{{__('Giảm giá')}}</span>--}}
{{--                                <span class="coll-3 text-align-right">-{{number_format($item['discount'])}}</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if($count<count($oder_detail))--}}
{{--                            <div class="border-bottom"></div>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
                    <hr>
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <strong class="coll-7">{{__('CHIẾT KHẤU THÀNH VIÊN')}}:</strong>
                        <strong class="coll-3">-{{number_format($receipt['discount_member']+$km, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
                    </div>
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <strong class="coll-7">{{__('TỔNG TIỀN ĐÃ GIẢM')}}:</strong>
                        <strong class="coll-3">-{{number_format($receipt['discount']+$km, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
                    </div>
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <strong class="coll-7">{{__('TỔNG TIỀN PHẢI T.TOÁN')}}:</strong>
                        <strong class="coll-3">{{number_format($receipt['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
                    </div>
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <strong class="coll-7">{{__('TỔNG TIỀN KHÁCH TRẢ')}}:</strong>
                        <strong class="coll-3">{{number_format($receipt['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
                    </div>
{{--                    @if (isset($receipt_detail) && $receipt_detail != null)--}}
{{--                        @foreach($receipt_detail as $paymentMethod)--}}
{{--                            <div class="mm-mauto tientong ss-font-size-10 roww">--}}
{{--                                <i class="coll-7 ">{{$paymentMethod['payment_method_name']}}:</i>--}}
{{--                                <strong class="coll-3 text-align-right">{{number_format($paymentMethod['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <strong class="coll-7">{{__('TIỀN TRẢ LẠI')}}:</strong>
                        <strong class="coll-3">{{number_format($receipt['amount_return'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
                    </div>
                    @if(($receipt['amount']-$receipt['amount_paid'])>0)
                        <div class="mm-mauto tientong ss-font-size-10 roww">
                            <strong class="colentl-7">{{__('KHÁCH NỢ')}}:</strong>
                            <strong class="coll-3">
                                {{number_format($receipt['amount']-$receipt['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </strong>
                        </div>
                    @endif
                    <hr>
                    @if($configPrintBill['is_show_footer']==1)
                        @if($receipt['note']!='' && $receipt['note']!=null)
                            <div class="mm-mauto " style="font-size:10px ;margin-right: 10px !important;">
                                <i>{{__('Ghi chú')}}: {{$receipt['note']}}</i>
                            </div>
                            <div style="height: 5px;"></div>
                        @endif
                        <div class="mm-mauto text-center tks">
                            <strong class="ss-nowap">{{__('CẢM ƠN QUÝ KHÁCH VÀ HẸN GẶP LẠI')}}</strong>
                        </div>
                    @endif
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
<input type="hidden" id="receipt_id" value="{{$receiptId}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="{{asset('js/laroute.js') . '?t=' . time()}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/payment/receipt/script.js')}}" type="text/javascript"></script>
</body>
</html>