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
            table.product{
                padding-top: 10px;
            }
            /*th*/
            thead {
                border: 1px solid #030303;
                border-collapse: collapse;
            }
            th.border-left {
                border-left: 1px solid #030303;
            }
            th.service-name{
                width: 90px;
                font-weight: normal;
            }
            th.quantity{
                width: 30px;
                font-weight: normal;
            }
            th.price{
                width: 90px;
                font-weight: normal;
            }
            th.total-price{
                width: 90px;
                font-weight: normal;
            }
            /* td */
            td.border {
                border: 1px solid #030303;
            }
            td.border-left {
                border-left: 1px solid #030303;
            }
            td.border-right{
                border-right: 1px solid #030303;
            }
            td.border-bottom{
                border-bottom: 1px solid #030303;
            }
            td.border-top{
                border-top: 1px solid #030303;
            }
            td.branch, td.address, td.hotline, td.order-title, td.order-code, td.order-time, td.order-customer, td.order-position {
                padding-top: 0px;
            }
            td.hotline{
                border-bottom: 1px solid #030303;
                padding-bottom: 10px;
            }
            td.order-code, td.order-time, td.order-customer, td.order-position, td.product-name {
                text-align: left;
            }
            td.order-position {
                padding-bottom: 10px;
            }
            span.note{
                padding-top: 5px;
                font-style: italic;
                font-size: 13px;
            }
            td.product-name{
                padding: 5px;
                border-bottom: none;
            }
            td.product-info{
                vertical-align: bottom;
                text-align: left;
                padding-left: 5px;
                padding-bottom: 5px;
            }
            td.product-quantity{
                vertical-align: top;
                padding-bottom: 5px;
            }
            td.product-price{
                vertical-align: top;
                text-align: right;
                padding-bottom: 5px;
            }
            td.product-total{
                vertical-align: top;
                text-align: right;
                padding-right: 5px;
                padding-bottom: 5px;
            }
            td.title-total-order{
                text-align: left;
                padding-left: 5px;
                width: 195px;
            }
            td.price-total-order{
                text-align: right;
                padding-right: 5px;
                width: 105px;
            }
            td.order-note{
                text-align: left;
                padding: 5px;
                border: 1px solid #030303;
            }
            td.member{
                text-align: left;
                padding-left: 5px;
            }
            td.member-point{
                text-align: right;
                padding-right: 5px;
            }
            td.order-install{
                text-align: center;
                padding-top: 10px;
            }
            td.order-valid{
                font-style: italic;
                text-align: center;
            }
            td.text-qr{
                font-style: italic;
                text-align: center;
                padding-top: 10px;
            }
            .padding-top-5{
                padding-top: 5px;
            }
            .padding-top-10{
                padding-top: 10px;
            }
            .padding-top-20{
                padding-top: 20px;
            }
            /* img */
            img {
                max-width: inherit;
                width: inherit;
            }
            img{ filter: grayscale(100%); transition: filter .3s ease-in-out;max-width: inherit;width: inherit;}
            img:hover { filter: none;}
            .ticket {
                margin: auto;
                width: 80mm;
                max-width: 80mm;
                text-align: center;
                padding-bottom: 5px;
                padding-right: 10px;
                padding-left: 5px;
            }
            .PrintArea {
                width: 100%;
                margin: auto;
            }
            @page {
                size: 80mm 290mm;
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
            }
        </style>
    </head>
    <body>
       <div class="ticket">
        <div class="PrintArea">
            <table>
                @if($configPrintBill['is_show_logo']==1)
                    <tr>
                        <td>
                            <img src="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}" style="width: 120px;">
                        </td>
                    </tr>
                @endif
                @if($configPrintBill['is_show_unit']==1)
                    <tr>
                        <td class="branch">
                            {{$branchInfo['branch_name']}}
                        </td>
                    </tr>
                @endif
                @if($configPrintBill['is_show_address']==1)
                    <tr>
                        <td class="address">
                            {{$branchInfo['address']}} {{$branchInfo['district_type'].' '
                            .$branchInfo['district_name'].' '.$branchInfo['province_name']}}
                        </td>
                    </tr>
                @endif
              
              
                @if($configPrintBill['is_show_phone']==1)
                    <tr>
                        <td class="address">
                            <b>{{ __('Hotline')}} : {{$branchInfo['hot_line']}}</b>   
                        </td>
                    </tr>
                @endif
                @if($configPrintBill['is_company_tax_code']==1 && $branchInfo['tax_code'] != '')
                <tr>
                    <td class="address">
                        <b>{{ __('MST')}} : {{$branchInfo['tax_code']}}</b>   
                    </td>
                </tr>
            @endif
            </table>
            <table>
                <tr>
                    <td class="order-title" style="border-top: 1px solid #000000; padding-top:5px; padding-bottom:5px;">
                        <b>{{__('HÓA ĐƠN BÁN HÀNG')}}</b>
                    </td>
                </tr>
                @if($configPrintBill['is_show_order_code']==1)
                    <tr>
                        <td class="order-code">
                           {{__(' Mã hóa đơn')}} : {{$order['order_code']}}
                        </td>
                    </tr>
                @endif
                @if($configPrintBill['is_show_customer']==1)
                <tr>
                    <td class="order-customer">
                    {{__('Khách hàng')}}: @if($order['customer_id']!=1)
                        {{$order['full_name']}}
                    @else
                        {{__('Khách hàng vãng lai')}}
                    @endif
                    </td>
                </tr>
                @endif
                @if($configPrintBill['is_customer_code']==1)
                <tr>
                    <td class="order-customer">
                        {{__('Mã KH')}}: {{$order['customer_code']}}
                    </td>
                </tr>
                @endif
                @if($configPrintBill['is_profile_code']==1)
                <tr>
                    <td class="order-customer">
                        {{__('Mã hồ sơ')}}: {{$order['profile_code']}}
                    </td>
                </tr>
                @endif
                @if($configPrintBill['is_show_cashier']==1)
                <tr>
                    <td class="order-customer">
                        {{__('Thu ngân')}}: {{$order['staff_name']}}
                    </td>
                </tr>
                @endif
                @if($configPrintBill['is_show_datetime'] == 1)
                <tr>
                    <td class="order-time">
                        {{ __('Thời gian') }} :  {{date("d/m/Y H:i:s")}}
                    </td>
                </tr>
                @endif
               
            </table>
            <table class="product">
                <thead>
                    <tr>
                        <th class="service-name">
                            {{__('Tên DV')}}
                        </th>
                        <th class="border-left quantity">
                            {{__('SL')}}
                        </th>
                        <th class="border-left price">
                            {{__('Đơn giá')}}
                        </th>
                        <th class="border-left total-price">
                            {{ __('T.Tiền')}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $km = 0;
                        $count = 0;
                    ?>
                    @foreach($oder_detail as $item)
                        @php
                            $km+=$item['discount'];
                            $count = $count + 1;
                        @endphp
                        <tr>
                            <td colspan="4" class="product-name border">
                            <span>
                                {{$item['object_name']}}
                            </span> 
                                @if(isset($item['note']))
                                    <br><span class="note">
                                        ({{$item['note']}})
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="product-info border-left border-bottom">
                                {{-- Giảm giá --}}
                            </td>
                            <td class="product-quantity border-bottom">
                                {{number_format($item['quantity'], 1)}}
                            </td>
                            <td class="product-price border-bottom">
                                {{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </td>
                            <td class="product-total border-right border-bottom">
                               <span>
                                {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                               </span>
                               {{-- <br><span>
                                   -100,000
                               </span> --}}
                            </td>
                        </tr>
                    @endforeach
                   
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-10">
                            <b>{{__('Tổng số dịch vụ')}}: </b>
                        </td>
                        <td class="price-total-order border-right padding-top-10">
                            <b>{{ $count }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            {{__('Thành tiền')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            {{number_format($order['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            {{__('Giảm giá')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            {{number_format($order['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-10">
                            <b>{{__('Tiền thanh toán')}}: </b>
                        </td>
                        <td class="price-total-order border-right padding-top-10">
                            <b>
                                {{number_format($order['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Tiền khách trả')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            {{number_format($amount_paid + $amount_return, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                    </tr>
                    @if($configPrintBill['is_payment_method'] == 1)
                        @if(isset($list_receipt_detail))
                            @foreach ($list_receipt_detail as $objPayment)
                            <tr>
                                <td class="title-total-order border-left border-right padding-top-5" style="padding-left: 25px;">
                                    {{ __($objPayment['payment_method_name'])}} 
                                </td>
                                <td class="price-total-order border-right padding-top-5">
                                    {{number_format($objPayment['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endif
                    @if ($configPrintBill['is_amount_return'])
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Tiền trả lại')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            {{number_format($amount_return, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                    </tr>
                    @endif
                    @if ($configPrintBill['is_dept_customer'] == 1)
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Khách nợ')}}:
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            {{number_format($order['amount']-($amount_paid), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                    </tr>
                    @endif
                    @if($configPrintBill['is_amount_member']==1)
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-10">
                            <b>{{__('Tài khoản thành viên')}}: </b>
                        </td>
                        <td class="price-total-order border-right padding-top-10">
                            <b>
                                {{number_format($accountMoney, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </b>
                        </td>
                    </tr>
                    @endif
                    
                    <tr>
                        <td colspan="2" class="order-note">
                            <b>{{__('Ghi chú')}}</b>: {{ $order['order_description'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
           
            <table> 
                <tbody>
                    @if($configPrintBill['note_footer'] != '')
                    <tr>
                        <td class="order-valid">
                            {{$configPrintBill['note_footer']}}
                        </td>
                    </tr>
                    @endif
                    @if($configPrintBill['is_show_footer']==1)
                    <tr>
                        <td class="order-valid" style="font-size: 13px;">
                            <b>{{__('Hoá đơn chỉ có giá trị trong ngày')}}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <b style="font-style: italic;font-size: 13px;">{{__('CẢM ƠN QUÝ KHÁCH VÀ HẸN GẶP LẠI')}}!</b>
                        </td>
                    </tr>
                    @endif
                    @if($configPrintBill['is_qrcode_order']==1)
                    <tr>
                        <td>
                            {!! QrCode::size(120)->generate($QrCode); !!}
                        </td>
                    </tr>
                    @endif
                    {{-- <tr>
                        <td class="padding-top-5">
                           Lần in : 1 - Ngày in : 01/06/2022 12:00:00
                        </td>
                    </tr> --}}
                   
                    {{-- <tr>
                        <td class="order-install">
                            <b>Cài app ngay để sử dụng điểm</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-qr">
                            Quét mã tải app
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {!! QrCode::size(120)->generate($QrCode); !!}
                        </td>
                    </tr> --}}
                    {{-- <tr>
                        <td>
                            <b>Sản phẩm của phần mềm ePoints</b><br>
                            <b style="font-style: italic;">www.epoints.vn</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img src="https://epoints.vn/uploads/config/20210904/9163076374804092021_config.png" width="40" height="40" alt="">
                        </td>
                    </tr> --}}
                    
                </tbody>
            </table>
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