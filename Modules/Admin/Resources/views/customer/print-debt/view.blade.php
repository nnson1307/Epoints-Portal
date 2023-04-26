<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Receipt example</title>
    <style>
        * {
            font-size: 14px;
            font-family: 'Roboto';
        }

        /* table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table.product {
            padding-top: 10px;
        }

        th {
            height: 40px;
        }

        /*th*/
        thead {
            border: 1px solid #030303;
            border-collapse: collapse;
        }

        th.border-left {
            border-left: 1px solid #030303;
        }

        th.service-name {
            /* width: 90px; */
            font-weight: normal;
        }

        th.quantity {
            width: 20px;
            font-weight: normal;
        }

        th.price {
            width: 110px;
            font-weight: normal;
        }

        th.total-price {
            width: 110px;
            font-weight: normal;
        }

        /* td */
        td.border {
            border: 1px solid #030303;
        }

        td.border-left {
            border-left: 1px solid #030303;
        }

        td.border-right {
            border-right: 1px solid #030303;
        }

        td.border-bottom {
            border-bottom: 1px solid #030303;
        }

        td.border-top {
            border-top: 1px solid #030303;
        }

        td.branch, td.address, td.hotline, td.order-title, td.order-code, td.order-time, td.order-customer, td.order-position {
            padding-top: 10px;
        }

        td.hotline {
            border-bottom: 1px solid #030303;
            padding-bottom: 10px;
        }

        td.order-code, td.order-customer, td.order-position, td.product-name {
            text-align: left;
            padding-top: 5px;
        }

        td.order-time {
            text-align: right;
            padding-top: 5px;
        }

        td.order-position {
            padding-bottom: 10px;
        }

        span.note {
            padding-top: 5px;
            font-style: italic;
            font-size: 13px;
        }

        td.product-name {
            padding: 5px;
            border-bottom: none;
        }

        td.product-info {
            vertical-align: bottom;
            text-align: left;
            padding-left: 5px;
            padding-bottom: 5px;
        }

        td.product-quantity {
            vertical-align: top;
            padding-bottom: 5px;
        }

        td.product-price {
            vertical-align: top;
            text-align: right;
            padding-bottom: 5px;
        }

        td.product-total {
            vertical-align: top;
            text-align: right;
            padding-right: 5px;
            padding-bottom: 5px;
        }

        td.title-total-order {
            text-align: left;
            padding-left: 5px;
            width: 195px;
        }

        td.price-total-order {
            text-align: right;
            padding-right: 5px;
            width: 105px;
        }

        td.order-note {
            text-align: left;
            padding: 5px;
            border: 1px solid #030303;
        }

        td.member {
            text-align: left;
            padding-left: 5px;
        }

        td.member-point {
            text-align: right;
            padding-right: 5px;
        }

        td.order-install {
            text-align: center;
            padding-top: 10px;
        }

        td.order-valid {
            font-style: italic;
            text-align: center;
            padding-top: 10px;
        }

        td.text-qr {
            font-style: italic;
            text-align: center;
            padding-top: 10px;
        }

        .padding-top-5 {
            padding-top: 5px;
        }

        .padding-top-10 {
            padding-top: 10px;
        }

        .padding-top-20 {
            padding-top: 20px;
        }

        /* img */
        img {
            max-width: inherit;
            width: inherit;
        }

        img {
            filter: grayscale(100%);
            transition: filter .3s ease-in-out;
            max-width: inherit;
            width: inherit;
        }

        img:hover {
            filter: none;
        }

        .ticket {
            margin: auto;
            width: 210mm;
            max-width: 210mm;
            text-align: center;
            padding-bottom: 5px;
            padding-right: 10px;
            padding-left: 5px;
        }

        .PrintArea {
            width: 205mm;
            margin: auto;
        }

        p {
            margin: 0px;
        }

        /* @page {
            size: 210mm;
            margin: 0;
        }

        @media print {
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        } */

        #table_detail td {
            height: 40px;
        }
    </style>
</head>
<body>
<div class="ticket">
    <div class="PrintArea">
        <table>
            <tr>
                <td rowspan="3" class="border-bottom" style="vertical-align: middle; width: 30%;">
                    @if($configPrintBill['is_show_logo']==1)
                        <img src="{{asset($spaInfo['logo'])}}" style="width: 120px">
                    @endif

                </td>
                <td class="border-bottom" style="vertical-align: middle; width: 40%; font-weight: bold;">
                    @if($configPrintBill['is_show_unit']==1)
                        <p style="font-size: 18px;">{{$branchInfo['branch_name']}}</p>
                    @endif
                </td>
                <td class="border-bottom" style="text-align: right; vertical-align: middle; width: 30%;">
                    @if($configPrintBill['symbol'] != '')
                        <p style="font-weight: bold;">{{ __('Ký hiệu') }} : {{$configPrintBill['symbol']}}</p>
                    @endif
                    @if($configPrintBill['is_company_tax_code']==1 && $spaInfo['tax_code'] != '')
                        <p style="font-weight: bold;">{{ __('Mã số thuế')}} : {{$spaInfo['tax_code']}}</p>
                    @endif

                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="order-title">
                    <b style="font-size: 18px;">{{__('CHI TIẾT CÔNG NỢ')}}</b>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width:50%; vertical-align: bottom; text-align:left">
                    @if($configPrintBill['is_show_customer']==1)
                        <p>{{__('Đối tượng thu')}}: {{$infoCustomer['full_name']}}</p>
                    @endif
                </td>
                <td style="width:50%; vertical-align: bottom; text-align:right">
                    @if($configPrintBill['is_show_datetime'] == 1)
                        <p>@lang('Ngày') {{date('d')}} @lang('tháng') {{date('m')}} @lang('năm') {{date('Y')}}</p>
                    @endif
                </td>
            </tr>
        </table>
        <br>


        <table>
            <thead>
            <tr>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="title-total-order border-left border padding-top-5" style="font-weight: bold;">
                    @lang('TỔNG TIỀN CẦN THANH TOÁN')
                </td>
                <td class="title-total-order border-left border padding-top-5" style="text-align: right;">
                    {{number_format($totalDebt, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                </td>
            </tr>
            <tr>
                <td class="title-total-order border-left border padding-top-5" style="font-weight: bold;">
                    @lang('TỖNG SỐ TIỀN ĐÃ THANH TOÁN')
                </td>
                <td class="title-total-order border-left border padding-top-5" style="text-align: right;">
                    {{number_format($totalDebtPaid, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                </td>
            </tr>
            <tr>
                <td class="title-total-order border-left border padding-top-5" style="font-weight: bold;">
                    @lang('TỔNG CÔNG NỢ CÒN LẠI')
                </td>
                <td class="title-total-order border-left border padding-top-5"
                    style="text-align: right; font-weight: bold;">
                    {{number_format($totalDebt - $totalDebtPaid, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        @if (count($listReceipt) > 0)
            <table>
                <tr>
                    <td style="width:50%; vertical-align: bottom; text-align:left">
                        @lang('Chi tiết các lần đã thanh toán')
                    </td>
                </tr>
            </table>
            <br>
            <table id="table_detail" style="width: 100%;margin-bottom: 1rem;background-color: transparent;">
                <thead style="white-space: nowrap;">
                <tr style="background-color: rgba(142, 142, 142, 0.35); white-space: nowrap;">
                    <th style="vertical-align: middle !important;">@lang('MÃ PHIẾU')</th>
                    <th style="vertical-align: middle !important;">@lang('LOẠI PHIẾU')</th>
                    <th style="vertical-align: middle !important;">@lang('ĐỐI TƯỢNG')</th>
                    <th style="vertical-align: middle !important;">@lang('TÊN ĐỐI TƯỢNG')</th>
                    <th style="vertical-align: middle !important;">@lang('TRẠNG THÁI')</th>
                    <th style="vertical-align: middle !important;">@lang('NGƯỜI TẠO')</th>
                    <th style="vertical-align: middle !important;">@lang('SỐ TIỀN THU')</th>
                    <th style="vertical-align: middle !important;">@lang('NGÀY GHI NHẬN')</th>
                </tr>
                </thead>
                <tbody>

                @foreach($listReceipt as $v)
                    <tr>
                        <td class="border">{{$v['receipt_code']}}</td>
                        <td class="border">{{$v['receipt_type_name']}}</td>
                        <td class="border">
                            @if ($v['object_type'] != 'debt' && $v['order_id'] === 0)
                                {{$v['object_accounting_type_name']}}
                            @elseif ($v['object_type'] == 'debt')
                                @lang('Công nợ')
                            @else
                                @lang('Khách hàng')
                            @endif
                        </td>
                        <td class="border">
                            @if ($v['object_type'] != 'debt' && $v['order_id'] === 0)
                                {{$v['object_accounting_name']}}
                            @elseif ($v['object_type'] == 'debt')
                                {{$v['customer_name_debt']}}
                            @else
                                {{$v['customer_name']}}
                            @endif
                        </td>
                        <td class="border">
                            @switch($v['status'])
                                @case('unpaid') {{__('Chưa thanh toán')}} @break
                                @case('part-paid') {{__('Thanh toán một phần')}} @break
                                @case('paid') {{__('Đã thanh toán')}} @break
                                @case('cancel') {{__('Hủy')}} @break
                                @case('fail') {{__('Lỗi')}} @break
                            @endswitch
                        </td>
                        <td class="border">
                            {{$v['staff_name']}}
                        </td>
                        <td class="border">
                            {{number_format($v['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                        <td class="border">
                            {{\Carbon\Carbon::parse($v['created_at'])->format('d/m/Y H:i')}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
</body>
<script type="text/javascript">
    setTimeout(myFunction, 1000);
    function myFunction() {
        window.print();
        setTimeout(function(){window.close();}, 1);
    }
</script>
</html>