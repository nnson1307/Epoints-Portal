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
            width: 210mm;
            margin: 0 auto;
        }

        .widhtss {
            margin: 0 auto;
            width: 98%;
            height: 98%;
        }

        .mm-mauto {
            margin: 0 auto !important;
        }

        /*@page {*/
        /*width: 10%;*/
        /*height: 10%;*/
        /*!*margin: 0 auto;*!*/
        /*}*/

        /* output size */
        .receipt .sheet {
            width: 210mm;
            height: 148mm;
            /*margin: 0*/
            /*float: left;*/
        }

        /* sheet size */
        @media print {
            #PrintArea {
                width: 213mm;
                height: 148mm;
                font-family: Arial, Helvetica, sans-serif;
                /*float: right;*/
                float: left;

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
                width: 200mm;
            }

            hr {
                border: 1px solid !important;
            }
        }

        .hr2 {
            border-bottom-width: 1px;
            border-bottom-style: dotted;
        }

        .border-bottom {
            border-bottom: 1px dashed;
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

        .coll-2 {
            width: 20%;
            float: left;
        }

        .coll-8 {
            width: 80%;
            float: left;
        }

        .coll-6 {
            width: 60%;
            float: left;
        }

        .coll-32 {
            width: 30%;
            float: right;
        }

        .text-align-right {
            text-align: right !important;
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
            font-size: 14px !important;
        }

        .font-size-15 {
            font-size: 15px !important;
        }

        .tks {
            font-size: 15px;
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
            width: 20mm !important;
            max-width: 20mm !important;
        }

        .ss-nowap {
            white-space: nowrap;
        }

        .width-table {
            width: 149mm;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .row-right {
            flex-wrap: wrap;
            box-sizing: border-box;
            position: relative;
            width: 50%;
            float: right;
        }

        .coll-7-right {
            width: 70%;
            float: right;
        }

        .coll-3-right {
            width: 30%;
            float: right;
        }

        .fontw-200 {
            font-weight: 200 !important;
        }

        /*.first-text-upper {*/
            /*text-transform: lowercase;*/
        /*}*/

        .first-text-upper:first-letter {
            text-transform: uppercase;
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
                            <div class="form-group m-form__group text-center coll-2">
                                <img class="imgss" src="{{asset($spaInfo['logo'])}}">
                            </div>
                        @endif
                        <div class="text-right coll-6" style="margin: 0 auto">
                            @if($configPrintBill['is_show_unit']==1)
                                <h5 class="text-center ss-font-size-10">
                                    {{$branchInfo['branch_name']}}
                                </h5>
                            @endif
                            {{--@if($configPrintBill['is_show_address']==1)--}}
                                {{--<h5 class="text-center ss-font-size-10">--}}
                                        {{--<span class="ss-font-size-10 text-center">--}}
                                            {{--{{$branchInfo['address'].' '.$branchInfo['district_type'].' '--}}
                                            {{--.$branchInfo['district_name'].' '.$branchInfo['province_name']}}--}}
                                        {{--</span>--}}
                                {{--</h5>--}}
                            {{--@endif--}}
                            {{--@if($configPrintBill['is_show_phone']==1)--}}
                                {{--<h4 class="text-center ss-font-size-10">--}}
                                        {{--<span>--}}
                                           {{--{{$branchInfo['hot_line']}}--}}
                                       {{--</span>--}}
                                {{--</h4>--}}
                            {{--@endif--}}
                        </div>
                        <div class="form-group m-form__group text-center coll-2">
                            <h4 class="text-left ss-font-size-10">
                                        <span>
                                           {{__('Ký hiệu')}}: {{$configPrintBill['symbol']}}
                                       </span>
                            </h4>
                            <h4 class="text-left ss-font-size-10">
                                        <span>
                                           {{__('Số')}}: {{$STT+1}}
                                       </span>
                            </h4>
                            <h4 class="text-left ss-font-size-10">
                                        <span>
                                           MST: {{$spaInfo['tax_code']}}
                                       </span>
                            </h4>
                        </div>
                    </div>
                    <hr>

                    <div class="mm-mauto">
                        <h4 class="text-center font-size-15">{{__('HÓA ĐƠN PHIẾU THU')}}</h4>
                        <div class="text-center" style="font-size: 9px;">{{$printTime}}</div>
                    </div>

                    <div class="mm-mauto tientong roww">
                        <div class="coll-7">
                            @if($configPrintBill['is_show_order_code']==1)
                                <div class="roww text-left">
                                    <div>
                                        <span class="ss-font-size-10">{{__('Mã hóa đơn')}}: {{$receipt['receipt_code']}}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="coll-3">
                            <span style="margin-left: 20px" class="text-center font-size-13">{{__('Ngày')}} {{date('d')}}
                                {{__('tháng')}} {{date('m')}} {{__('năm')}} {{date('Y')}} </span>
                        </div>
                    </div>
                    <div class="mm-mauto tientong roww">
                        @if($configPrintBill['is_show_customer']==1)
                            <span class="ss-font-size-10 coll-7">
                                {{__('Đối tượng thu')}}:
                                @if ($receipt['object_type'] != 'debt' && $receipt['order_id'] === 0)
                                    {{$receipt['object_accounting_name']}}
                                @elseif ($receipt['object_type'] == 'debt')
                                    {{$receipt['customer_name_debt']}}
                                @else
                                    {{$receipt['customer_name']}}
                                @endif
                            </span>
                        @endif
                        @if($configPrintBill['is_show_cashier']==1)
                            <strong class="ss-font-size-10 coll-32" style="float:right;">
                                <strong class="ss-font-size-10"></strong>
                            </strong>
                        @endif
                    </div>
                    <div class="mm-mauto tientong roww">
                            <span class="ss-font-size-10 coll-7">
                                {{--{{__('Thu ngân')}}: {{$receipt['staff_name']}}--}}
                            </span>
                        @if($configPrintBill['is_show_cashier']==1)
                            <strong class="ss-font-size-10 coll-32" style="float:right;">
                                <strong class="ss-font-size-10"></strong>
                            </strong>
                        @endif
                    </div>
                    <br>
                    @php
                        $km=0;
                        $count=0;
                        $stt=1;
                    @endphp
                    <table style="width:100%">
                        <tr>
                            <td colspan="5">
                                <div class="row_custom">
                                    <div class="coll-8" style="font-size: 15px;">
                                        {{__('CHIẾT KHẤU THÀNH VIÊN')}}:
                                    </div>
                                    <div class="coll-4 text-right" style="font-size: 15px;">
                                        {{number_format(($receipt['discount_member']+$km))}} {{__('VNĐ')}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="row_custom">
                                    <div class="coll-8" style="font-size: 15px;">
                                        {{__('TỔNG TIỀN ĐÃ GIẢM')}}:
                                    </div>
                                    <div class="coll-4 text-right" style="font-size: 15px;">
                                        {{number_format(($receipt['discount']+$km))}} {{__('VNĐ')}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="row_custom">
                                    <div class="coll-8" style="font-size: 15px;">
                                        {{__('TỔNG TIỀN PHẢI THANH TOÁN')}}:
                                    </div>
                                    <div class="coll-4 text-right" style="font-size: 15px; font-weight: bold;">
                                        {{number_format($receipt['amount'])}} {{__('VNĐ')}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="row_custom">
                                    <div class="coll-8" style="font-size: 15px;">
                                        {{__('TỔNG TIỀN KHÁCH TRẢ')}}:
                                    </div>
                                    <div class="coll-4 text-right" style="font-size: 15px;">
                                        {{number_format($receipt['amount_paid'])}} {{__('VNĐ')}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                        {{__('TỔNG TIỀN KHÁCH TRẢ')}} {{__('VIẾT BẰNG CHỮ')}}: <span class="first-text-upper">{{$text_total_amount_paid}}
                            {{__('đồng')}}.</span>
                    </div>
                    @if($receipt['note']!='' && $receipt['note']!=null)
                        <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                            {{__('Ghi chú')}}: <i> {{$receipt['note']}}</i>
                        </div>
                    @endif
{{--                    <div class="mm-mauto tientong ss-font-size-10 roww">--}}
{{--                        <strong class="coll-7">TIỀN KHÁCH TRẢ:</strong>--}}
{{--                        <strong class="coll-3">{{number_format($receipt['amount_paid'])}}</strong>--}}
{{--                    </div>--}}
{{--                    <div class="mm-mauto tientong ss-font-size-10 roww">--}}
{{--                        <strong class="coll-7">{{__('TIỀN TRẢ LẠI')}}: </strong>--}}
{{--                        <strong class="coll-3">{{number_format($receipt['amount_return'])}}</strong>--}}
{{--                    </div>--}}
                    <hr>
                    <div class="mm-mauto tientong font-size-15 roww">
                        <strong class="coll-5 font-size-15" style="margin-left: 50px">{{__('Người mua hàng')}}</strong>
                        <strong class="coll-5 font-size-15" style="margin-right: 50px">{{__('Người bán hàng')}}</strong>
                    </div>
                    <div class="mm-mauto tientong font-size-15 roww">
                        <strong class="coll-5 font-size-13" style="font-weight: 300;font-size: 13px;margin-left: 60px">{{__('(Ký, ghi rõ họ tên)')}}</strong>
                        <strong class="coll-5 font-size-13" style="font-weight: 300;font-size: 13px;margin-right: 25px">{{__('(Ký, đóng dấu, ghi rõ họ tên)')}}</strong>
                    </div>
                    <br>
                    <br>
                    <br>
                </div>
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
        </section>
    </div>
</div>
<input type="hidden" id="receipt_id" value="{{$receiptId}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    const name = $('.first-text-upper').text();
    const nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1);
    $('.first-text-upper').text(nameCapitalized);
</script>
<script src="{{asset('js/laroute.js') . '?t=' . time()}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/payment/receipt/script.js')}}" type="text/javascript"></script>
</body>
</html>