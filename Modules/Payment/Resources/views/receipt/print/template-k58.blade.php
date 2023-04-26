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
                                    {{$branchInfo['branch_name']}}
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_address']==1)
                                <h5 class="text-center ss-font-size-10">
                                        <span class="ss-font-size-10 text-center">
                                           {{$branchInfo['address']}}
                                        </span>
                                </h5>
                                <h5 class="text-center ss-font-size-10">
                                        <span class="ss-font-size-10 text-center">
                                           {{$branchInfo['district_type'].' '
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
                    @if($configPrintBill['is_show_customer'] == 1)
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
                    @if($configPrintBill['is_show_cashier'] == 1)
                        <div class="mm-mauto">
                            <strong class="ss-font-size-10">{{__('Thu ngân')}}:
                                <strong>{{$receipt['staff_name']}}</strong>
                            </strong>
                        </div>
                    @endif
                    <div class="mm-mauto">
                        <strong class="mm-mauto ss-font-size-10">
                            {{date("d/m/Y H:i:s")}}
                        </strong>
                    </div>
                    <hr>
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7 ss-font-size-10">{{__('Tên SP/DV')}}</span>
                        <span class="coll-3 text-align-right ss-font-size-10">{{__('Tổng tiền')}} {!!$space !!}</span>
                    </div>
                    <hr>
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <span class="coll-7 strong-text">{{__('TỔNG TIỀN PHẢI T.TOÁN')}}:</span>
                        <span class="coll-3 strong-text text-align-right">{{number_format($receipt['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                    </div>
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <span class="coll-7 strong-text">{{__('TỔNG TIỀN KHÁCH TRẢ')}}:</span>
                        <span class="coll-3 strong-text text-align-right">{{number_format($receipt['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                    </div>
                    <div class="mm-mauto tientong ss-font-size-10 roww">
                        <span class="coll-7 strong-text">{{__('TIỀN TRẢ LẠI')}}:</span>
                        <span class="coll-3 strong-text text-align-right">{{number_format($receipt['amount_return'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                    </div>
                    @if(($receipt['amount']-$receipt['amount_paid'])>0)
                        <div class="mm-mauto tientong ss-font-size-10 roww">
                            <span class="coll-7 strong-text">{{__('KHÁCH NỢ')}}:</span>
                            <span class="coll-3 strong-text text-align-right">
                                {{number_format($receipt['amount']-$receipt['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </span>
                        </div>
                    @endif
                    <hr>
                    <div class="mm-mauto " style="font-size:10px ;margin-right: 10px !important;">
                        <i>{{__('Ghi chú')}}: {{$receipt['note']}}</i>
                    </div>
                    <div style="height: 5px;"></div>
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

<script type="text/javascript">
</script>
<script src="{{asset('js/laroute.js') . '?t=' . time()}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/payment/receipt/script.js')}}" type="text/javascript"></script>
</body>
</html>