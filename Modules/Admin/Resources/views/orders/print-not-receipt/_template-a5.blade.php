<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{__('In hóa đơn')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('static/backend/css/print-bill.css')}}">
    <link rel="shortcut icon" href="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}" />

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
        #PrintArea{
            border: 1px solid;
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
                                <img class="imgss" src="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}">
                            </div>
                        @endif
                        <div class="text-right coll-8" style="margin: 0 auto">
                            @if($configPrintBill['is_show_unit']==1)
                                <h5 class="text-center ss-font-size-10">
                                    {{$spaInfo['name']}}
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_address']==1)
                                <h5 class="text-center ss-font-size-10">
                                        <span class="ss-font-size-10 text-center">
                                            {{$spaInfo['address'].' '.$spaInfo['district_type'].' '
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
                        <h4 class="text-center font-size-15">{{__('HÓA ĐƠN BÁN HÀNG')}}</h4>
                        <div class="text-center" style="font-size: 9px;">{{__('(Phiếu làm dịch vụ)')}}</div>
                        @if($configPrintBill['is_show_order_code']==1)
                            <div class="roww text-left">
                                <div>
                                    <h5 class="ss-font-size-10">{{__('HĐ')}}: {{$order['order_code']}}
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
                    <div>
                        <h5 class="ss-font-size-10">{{__('Mã hồ sơ')}}: {{$order['profile_code']}}
                        </h5>
                    </div>
                    <div>
                        <h5 class="ss-font-size-10">{{__('Mã khách hàng')}}: {{$order['customer_code']}}
                        </h5>
                    </div>
                    <div class="mm-mauto tientong roww">
                        @if($configPrintBill['is_show_customer']==1)
                            <strong class="ss-font-size-10 coll-7">
                                {{__('KHÁCH HÀNG')}}: @if($order['customer_id']!=1)
                                    {{$order['full_name']}}
                                @else
                                    {{__('Khách hàng vãng lai')}}
                                @endif
                            </strong>
                        @endif
{{--                        @if($configPrintBill['is_show_cashier']==1)--}}
{{--                            <strong class="ss-font-size-10 coll-32" style="float:right;">Thu ngân:--}}
{{--                                <strong class="ss-font-size-10">{{$receipt['full_name']}}</strong>--}}
{{--                            </strong>--}}
{{--                        @endif--}}
                    </div>
                    <div class="mm-mauto">
                        <strong class="mm-mauto ss-font-size-10">
                            {{date("d/m/Y H:i:s")}}
                        </strong>
                    </div>

                    <hr>
                    @php
                        $km=0;
                        $count=0;
                    @endphp
                    <div class="tientong roww" style="font-weight: bold;">
                        <span class="coll-7">{{__('Tên SP/DV')}}</span>
                        <span class="coll-32 text-align-right">{{__('Tổng tiền')}}</span>
                    </div>
                    @foreach($oder_detail as $item)
                        @php
                            $km+=$item['discount'];
                            $count++;
                        @endphp
                        <span class="ss-font-size-10">
                         {{$item['object_name']}}
                        </span>
                        <div class="tientong roww ss-font-size-10">
                            <span class="coll-7">
                                ({{$item['quantity']}}x{{number_format($item['price'])}})
                            </span>
                            <span class="coll-32 text-align-right">
{{--                                {{number_format($item['amount'])}}--}}
                                {{number_format($item['price']*$item['quantity'])}}
                            </span>
                        </div>
                        @if($item['object_type']=='member_card')
                            <div class="tientong roww ss-font-size-10">
                                <span class="coll-7">{{$item['object_code']}}</span>
                                <span class="coll-32 text-align-right" style="text-align: right;"></span>
                            </div>
                        @endif
                        @if($item['discount']!=0)
                            <div class="tientong roww ss-font-size-10">
                                <span class="coll-7">{{__('Giảm giá')}}</span>
                                <span class="coll-32 text-align-right"
                                      style="text-align: right;">-{{number_format($item['discount'])}}</span>
                            </div>
                        @endif
                        @if($count<count($oder_detail))
                            <div class="border-bottom"></div>
                        @endif
                    @endforeach
                    <hr>
                    <div class="mm-mauto tientong font-size-15 roww">
                        <strong class="coll-7 font-size-15">{{__('TỔNG TIỀN ĐÃ GIẢM')}}:</strong>
                        <strong class="coll-3 font-size-15">-{{number_format($order['discount']+$km)}}</strong>
                    </div>
                    <div class="mm-mauto tientong font-size-15 roww">
                        <strong class="coll-7 font-size-15">{{__('TỔNG TIỀN PHẢI T.TOÁN')}}:</strong>
                        <strong class="coll-3 font-size-15">{{number_format(0)}}</strong>
                    </div>
                    @if($cash!=0)
                        <div class="mm-mauto tientong font-size-15 roww">
                            <strong class="coll-7 font-size-15">{{__('TIỀN MẶT')}}:</strong>
                            <strong class="coll-3 font-size-15">{{number_format($cash)}}</strong>
                        </div>
                    @endif
                    @if($transfer!=0)
                        <div class="mm-mauto tientong font-size-15 roww">
                            <strong class="coll-7 font-size-15">{{__('CHUYỂN KHOẢN')}}:</strong>
                            <strong class="coll-3 font-size-15">{{number_format($transfer)}}</strong>
                        </div>
                    @endif
                    @if($visa!=0)
                        <div class="mm-mauto tientong font-size-15 roww">
                            <strong class="coll-7 font-size-15">{{__('VISA')}}:</strong>
                            <strong class="coll-3 font-size-15">{{number_format($visa)}}</strong>
                        </div>
                    @endif
                    @if($member_money!=0)
                        <div class="mm-mauto tientong font-size-15 roww">
                            <strong class="coll-7 font-size-15">{{__('TÀI KHOẢN THÀNH VIÊN')}}:</strong>
                            <strong class="coll-3 font-size-15">{{number_format($member_money)}}</strong>
                        </div>
                    @endif
                    {{--<div class="mm-mauto tientong ss-font-size-10 roww">--}}
                    {{--<strong class="coll-7">TIỀN KHÁCH TRẢ:</strong>--}}
                    {{--<strong class="coll-3">{{number_format($receipt['amount_paid'])}}</strong>--}}
                    {{--</div>--}}
                    {{--<div class="mm-mauto tientong ss-font-size-10 roww">--}}
                    {{--<strong class="coll-7">{{__('TIỀN TRẢ LẠI')}}: </strong>--}}
                    {{--<strong class="coll-3">{{number_format($receipt['amount_return'])}}</strong>--}}
                    {{--</div>--}}
                    <hr>
                    @if($configPrintBill['is_show_footer']==1)
                        <div class="mm-mauto text-center tks">
                            <strong class="ss-nowap">{{__('CẢM ƠN QUÝ KHÁCH VÀ HẸN GẶP LẠI')}}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="widhtss" style="margin-top: 15px; text-align: right">
                <div style="color: red;margin-bottom: 5px;">
                    <span class="error-print-bill font-size-15"></span>
                </div>
                <a class="btn btn-metal btn-sm" onclick="PrintBill.back()">{{__('HỦY')}}</a>
                <a onclick="PrintBill.printBill()" class="btn btn-success btn-sm" style="margin-left: 10px">
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
<input type="hidden" id="orderId" value="{{$id}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    // $(window).on('load', function () {
    //     $('body').removeClass('m-page--loading');
    // });
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
</script>
<script src="{{asset('js/laroute.js') . '?t=' . time()}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/order/save-log-print-bill.js')}}" type="text/javascript"></script>
</body>
</html>