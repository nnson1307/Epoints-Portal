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
        border: 1px solid #030303 !important;
        border-collapse: collapse;
    }
    th.border-left {
        border-left: 1px solid #030303 !important;
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
        border: 1px solid #030303 !important;
    }
    td.border-left {
        border-left: 1px solid #030303 !important;
    }
    td.border-right{
        border-right: 1px solid #030303 !important;
    }
    td.border-bottom{
        border-bottom: 1px solid #030303 !important;
    }
    td.border-top{
        border-top: 1px solid #030303 !important;
    }
    td.branch, td.address, td.hotline, td.order-title, td.order-code, td.order-time, td.order-customer, td.order-position {
        padding-top: 10px;
    }
    td.hotline{
        border-bottom: 1px solid #030303 !important;
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
        border: 1px solid #030303 !important;
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
        /* width: 210mm;
        max-width: 210mm;
        height: 297mm;
        max-height: 297mm; */
        padding-right: 3px;
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: center;
    }
    p{
        line-height: 5px;
       
    }
    
    @page {
        size: A4;
        margin: 0;
    }
    @media print {
    html, body {
        width: 210mm;
        height: 297mm;
    }
    /* ... the rest of the rules ... */
    }
</style>

<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="la la-print ss--icon-title m--margin-right-5"></i>
            {{__('MẪU IN HÓA ĐƠN')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="ticket">
            <table>
                <tr>
                    <td rowspan="3" class="border-bottom" style="vertical-align: bottom;">
                        @if($configPrintBill['is_show_logo']==1)
                        <img src="{{asset($spaInfo['logo'])}}" style="width: 120px">
                        @endif
                        
                    </td>
                    <td class="border-bottom" style="vertical-align: bottom;">
                        @if($configPrintBill['is_show_unit']==1)
                            <p>{{$spaInfo['name']}}</p>
                        @endif
                        @if($configPrintBill['is_show_address']==1)
                            <p>{{$spaInfo['address']}} {{$spaInfo['district_type'].' ' .$spaInfo['district_name'].' '.$spaInfo['province_name']}}</p>
                        @endif
                        @if($configPrintBill['is_show_phone']==1)
                          <p><b>Hotline : {{$spaInfo['hot_line']}}</b></p>
                        @endif
                    </td>
                    <td class="border-bottom" style="text-align: right; vertical-align: bottom;">
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
                            {{__(' Mã hóa đơn')}} : HD_00000001
                        </td>
                    </tr>
                @endif
             
            </table>
            <table>
               <tr>
                <td style="width:50%; vertical-align: bottom; text-align:left">
                    @if($configPrintBill['is_show_customer']==1)
                    <p>{{__('Khách hàng')}}: {{__('Khách hàng vãng lai')}}</p>
                    @endif
                    @if($configPrintBill['is_customer_code']==1)
                        <p> {{__('Mã khách hàng')}}: KH_000001</p>
                    @endif
                    @if($configPrintBill['is_profile_code']==1)
                        <p>{{__('Mã hồ sơ')}}: P_0000001</p>
                    @endif
                </td>
                <td style="width:50%; vertical-align: bottom; text-align:right">
                   
                    @if($configPrintBill['is_show_cashier']==1)
                        <p>{{__('Thu ngân')}}: Nguyễn Văn A</p>
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
                    <tr>
                        <td class="product-info border-left border-bottom" style="padding-top: 5px;">
                            Mặt Nạ Dưỡng Da Senka Perfect Aqua Rich/vàng
                        </td>
                        <td class="product-quantity border-bottom" style="padding-top: 5px;">
                            1
                        </td>
                        <td class="product-price border-bottom" style="padding-top: 5px;">
                            35,000	
                        </td>
                        <td class="product-total border-right border-bottom" style="padding-top: 5px;">
                            35,000	
                        </td>
                    </tr>
                   
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-10">
                            <b>{{__('Tổng số dịch vụ')}}: </b>
                        </td>
                        <td class="price-total-order border-right padding-top-10">
                            <b>1</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Thành tiền')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            35,000
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Giảm giá')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            0
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-10">
                            <b>{{__('Tiền thanh toán')}}: </b>
                        </td>
                        <td class="price-total-order border-right padding-top-10">
                            <b>
                                35,000
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Tiền khách trả')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            35,000
                        </td>
                    </tr>
                    @if($configPrintBill['is_payment_method'] == 1)
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5" style="padding-left: 25px;">
                            {{ __('Tiền mặt')}} 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            35,000
                        </td>
                    </tr>
                    @endif
                    @if ($configPrintBill['is_amount_return'])
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Tiền trả lại')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            0
                        </td>
                    </tr>
                    @endif
                    
                    @if ($configPrintBill['is_dept_customer'])
                    <tr>
                        <td class="title-total-order border-left border-right padding-top-5">
                            + {{__('Khách nợ')}}: 
                        </td>
                        <td class="price-total-order border-right padding-top-5">
                            0
                        </td>
                    </tr>
                    @endif
                    
                    @if($configPrintBill['is_amount_member']==1)
                        <tr>
                            <td class="title-total-order border-left border-right padding-top-10">
                            <b> {{__('Tài khoản thành viên')}}: </b>
                            </td>
                            <td class="price-total-order border-right padding-top-10">
                                <b>
                                    <b>20,000</b>
                                </b>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2" class="order-note">
                            {{__('Ghi chú')}}: Ghi chú
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
                        <td class="order-valid">
                            <b>{{__('Hoá đơn chỉ có giá trị trong ngày')}}</b>
                        </td>
                    </tr>
                    <tr>
                        <td class="padding-top-5">
                           <b style="font-style: italic;">{{__('CẢM ƠN QUÝ KHÁCH VÀ HẸN GẶP LẠI')}}!</b>
                        </td>
                    </tr>
                @endif
                @if($configPrintBill['is_qrcode_order'] == 1)
                <tr>
                    <td>
                        {!! QrCode::size(120)->generate("HD_000001"); !!}
                    </td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                    <span class="ss--text-btn-mobi">
                    <i class="la la-arrow-left"></i>
                    <span>{{__('HỦY')}}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
