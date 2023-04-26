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
                /* width: 90px; */
                font-weight: normal;
            }
            th.quantity{
                width: 20px;
                font-weight: normal;
            }
            th.price{
                width: 110px;
                font-weight: normal;
            }
            th.total-price{
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
                padding-top: 10px;
            }
            td.hotline{
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
                padding-top: 10px;
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
                width: 210mm;
                max-width: 210mm;
                text-align: center;
                padding-top: 5px;
                padding-bottom: 5px;
                padding-right: 5px;
                padding-top: 5px;
               
            }
            .PrintArea {
                width: 205mm;
                margin: auto;
            }
            p{
                margin: 0px;
            }

            /* @page {
                size: 210mm ;
                margin: 0;
            } */
            /* @media print {
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
        </style>
    </head>
    <body>
        <div class="ticket">
            <div class="PrintArea">
                <table>
                    <tr>
                        <td rowspan="3" class="border-bottom" style="vertical-align: bottom; width: 30%;">
                            @if($configPrintBill['is_show_logo']==1)
                            <img src="{{asset($spaInfo['logo'])}}" style="width: 120px">
                            @endif
                            
                        </td>
                        <td class="border-bottom" style="vertical-align: bottom; width: 40%;">
                            @if($configPrintBill['is_show_unit']==1)
                                <p>{{$branchInfo['branch_name']}}</p>
                            @endif
                            @if($configPrintBill['is_show_address']==1)
                                <p>{{$branchInfo['address']}} {{$branchInfo['district_type'].' ' .$branchInfo['district_name'].' '.$branchInfo['province_name']}}</p>
                            @endif
                            @if($configPrintBill['is_show_phone']==1)
                              <p><b>Hotline : {{$branchInfo['hot_line']}}</b></p>
                            @endif
                        </td>
                        <td class="border-bottom" style="text-align: right; vertical-align: bottom; width: 30%;">
                            @if($configPrintBill['symbol'] != '')
                            <p>{{ __('Ký hiệu') }} :  {{$configPrintBill['symbol']}}</p>
                            @endif
                            @if($configPrintBill['is_company_tax_code']==1 && $spaInfo['tax_code'] != '')
                            <p>{{ __('Mã số thuế')}} : {{$spaInfo['tax_code']}}</p>
                           @endif
                         
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td class="order-title">
                            <b>{{__('HÓA ĐƠN BÁN HÀNG')}}</b>
                        </td>
                    </tr>
                    @if($configPrintBill['is_show_order_code']==1)
                        <tr>
                            <td class="order-title" style="padding-top: 0px;">
                                {{__(' Mã hóa đơn')}} : {{$order['order_code']}}
                            </td>
                        </tr>
                    @endif
                 
                </table>
                <table>
                   <tr>
                    <td style="width:50%; vertical-align: bottom; text-align:left">
                        @if($configPrintBill['is_show_customer']==1)
                            @if($order['customer_id']!=1)
                                <p>{{__('Khách hàng')}}: {{$order['full_name']}}</p>
                            @else
                                <p>{{__('Khách hàng')}}: {{__('Khách hàng vãng lai')}}</p>
                            @endif
                        @endif
                        @if($configPrintBill['is_customer_code']==1)
                            <p> {{__('Mã khách hàng')}}: {{$order['customer_code']}}</p>
                        @endif
                        @if($configPrintBill['is_profile_code']==1)
                            <p>{{__('Mã hồ sơ')}}: {{$order['profile_code']}}</p>
                        @endif
                    </td>
                    <td style="width:50%; vertical-align: bottom; text-align:right">
                       
                        @if($configPrintBill['is_show_cashier']==1)
                            <p>{{__('Thu ngân')}}: {{$order['staff_name']}}</p>
                        @endif
                        @if($configPrintBill['is_show_datetime'] == 1)
                            <p>{{ __('Thời gian') }} :  {{date("d/m/Y H:i:s")}}</p>
                        @endif
                    </td>
                   </tr>
    
                </table>
                <table class="product">
                    <thead>
                        <tr>
                            <th class="service-name" style="text-align: left">
                                {{__('Tên dịch vụ')}}
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
                            {{-- <tr>
                                <td colspan="4" class="product-name border">
                                <span>
                                    {{$item['object_name']}}
                                </span> 
                                    <br><span class="note">
                                        (Ghi chú: KH sử dụng dịch vụ lần 2)
                                    </span>
                                    
                                </td>
                            </tr> --}}
                            <tr>
                                <td class="product-info border-left border-bottom" style="padding-top: 5px;">
                                    {{$item['object_name']}}
                                    @if(isset($item['name_attribute']))
                                        <div>
                                            @foreach($item['name_attribute'] as $keyAttribute => $valueAttribute)
                                                @if($keyAttribute == 0)
                                                    {{$valueAttribute['product_attribute_group_name'].' '.$valueAttribute['product_attribute_label']}}
                                                @else
                                                    ,{{$valueAttribute['product_attribute_group_name'].' '.$valueAttribute['product_attribute_label']}}
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    @if(isset($order_detail_child[$item['order_detail_id']]))
                                        <div>
                                            <p>Topping :
                                                @foreach($order_detail_child[$item['order_detail_id']] as $keyValue => $value)
                                                    @if($keyValue == 0)
                                                        {{$value['object_name']}}
                                                    @else
                                                        ,{{$value['object_name']}}
                                                    @endif
                                                @endforeach
                                            </p>
                                        </div>
                                    @endif
                                </td>
                                <td class="product-quantity border-bottom" style="padding-top: 5px;">
                                    {{$item['quantity']}}
                                </td>
                                <td class="product-price border-bottom" style="padding-top: 5px;">
                                    {{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                </td>
                                <td class="product-total border-right border-bottom" style="padding-top: 5px;">
                                    {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                </td>
                            </tr>
                        @endforeach
                       
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td class="title-total-order border-left border-right padding-top-5">
                                <b>{{__('Tổng số dịch vụ')}}: </b>
                            </td>
                            <td class="price-total-order border-right padding-top-5">
                                <b>{{ $count }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td class="title-total-order border-left border-right">
                                + {{__('Thành tiền')}}: 
                            </td>
                            <td class="price-total-order border-right">
                                {{number_format($order['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </td>
                        </tr>
                        <tr>
                            <td class="title-total-order border-left border-right">
                                + {{__('Giảm giá')}}: 
                            </td>
                            <td class="price-total-order border-right">
                                {{number_format($order['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </td>
                        </tr>
                        <tr>
                            <td class="title-total-order border-left border-right padding-top-5">
                                <b>{{__('Tiền thanh toán')}}: </b>
                            </td>
                            <td class="price-total-order border-right padding-top-5">
                                <b>
                                    {{number_format($order['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                </b>
                            </td>
                        </tr>
                        <tr>
                            <td class="title-total-order border-left border-right">
                                + {{__('Tiền khách trả')}}: 
                            </td>
                            <td class="price-total-order border-right">
                                {{number_format($amount_paid, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </td>
                        </tr>
                        @if($configPrintBill['is_payment_method'] == 1)
                            @if(isset($list_receipt_detail))
                                @foreach ($list_receipt_detail as $objPayment)
                                <tr>
                                    <td class="title-total-order border-left border-right" style="padding-left: 25px;">
                                        {{ __($objPayment['payment_method_name'])}} 
                                    </td>
                                    <td class="price-total-order border-right">
                                        {{number_format($objPayment['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        @endif
                        @if ($configPrintBill['is_amount_return'])
                        <tr>
                            <td class="title-total-order border-left border-right">
                                + {{__('Tiền trả lại')}}: 
                            </td>
                            <td class="price-total-order border-right">
                                {{number_format($amount_return, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </td>
                        </tr>
                        @endif
                        
                        @if ($configPrintBill['is_dept_customer'])
                        <tr>
                            <td class="title-total-order border-left border-right">
                                + {{__('Khách nợ')}}: 
                            </td>
                            <td class="price-total-order border-right">
                                {{number_format($order['amount']-($amount_paid), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                            </td>
                        </tr>
                        @endif
                        
                        @if($configPrintBill['is_amount_member']==1)
                            <tr>
                                <td class="title-total-order border-left border-right padding-top-5">
                                <b> {{__('Tài khoản thành viên')}}: </b>
                                </td>
                                <td class="price-total-order border-right padding-top-5">
                                    <b>
                                        <b>{{number_format($accountMoney, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</b>
                                    </b>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="order-note">
                                {{__('Ghi chú')}}: {{ $order['note'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                @if($configPrintBill['is_sign']==1)
                    <table>
                        <tr>
                            <td class="padding-top-15" style="padding-bottom: 30px; padding-left: 30px; text-align: left">
                                <b>{{__('Người mua hàng')}}</b><br>
                                {{__('(Ký, ghi rõ họ tên)')}}
                            </td>
                            <td class="padding-top-15" style="padding-bottom: 30px;  text-align: right;">
                                <span style="padding-right: 30px;">
                                    <b>{{__('Người bán hàng')}}</b>
                                </span>
                                <br>
                                <span>
                                    {{__('(Ký, đóng dấu, ghi rõ họ tên)')}}
                                </span>
                            </td>
                        </tr>
                    </table>
                @endif
                <table>            
                    @if($configPrintBill['note_footer'] != '')
                    <tr>
                        <td>
                            {{$configPrintBill['note_footer']}}
                        </td>
                    </tr>
                    @endif
                    @if($configPrintBill['is_show_footer']==1)
                        <tr>
                            <td>
                                <b>{{__('Hoá đơn chỉ có giá trị trong ngày')}}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                               <b style="font-style: italic;">{{__('CẢM ƠN QUÝ KHÁCH VÀ HẸN GẶP LẠI')}}!</b>
                            </td>
                        </tr>
                    @endif
                   
                    @if($configPrintBill['is_qrcode_order'] == 1)
                    <tr>
                        <td>
                            {!! QrCode::size(120)->generate($QrCode); !!}
                        </td>
                    </tr>
                    @endif
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