<!doctype html>
<!--Quite a few clients strip your Doctype out, and some even apply their own. Many clients do honor your doctype and it can make things much easier if you can validate constantly against a Doctype.-->
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{__('Email template By Adobe Dreamweaver CC')}}</title>

    <!-- Please use an inliner tool to convert all CSS to inline as inpage or external CSS is removed by email clients -->
    <!-- important in CSS is used to prevent the styles of currently inline CSS from overriding the ones mentioned in media queries when corresponding screen sizes are encountered -->

    <style type="text/css">
        body {
            margin: 0;
        }

        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: none !important;
            font-family: 'Roboto', sans-serif;
            font-style: normal;
            /*	font-weight: 400;*/
        }

        .footer-responsive {
            display: none;
        }

        .footer-pc {
            display: block;
        }

        .size {
            width: 150px;
        }

        .size_text {
            width: 350px;
        }

        /*button {*/
        /*width: 90%;*/
        /*}*/

        @media screen and (max-width: 600px) {
            /*styling for objects with screen size less than 600px; */
            body, table, td, p, a, li, blockquote {
                -webkit-text-size-adjust: none !important;
                font-family: 'Roboto', sans-serif;
            }

            table {
                /* All tables are 100% width */
                width: 100%;
            }

            .footer {
                /* Footer has 2 columns each of 48% width */
                height: auto !important;
                max-width: 48% !important;
                width: 48% !important;
            }

            table.responsiveImage {
                /* Container for images in catalog */
                height: auto !important;
                max-width: 30% !important;
                width: 30% !important;
            }

            table.responsiveContent {
                /* Content that accompanies the content in the catalog */
                height: auto !important;
                max-width: 66% !important;
                width: 66% !important;
            }

            .top {
                /* Each Columnar table in the header */
                height: auto !important;
                max-width: 48% !important;
                width: 48% !important;
            }

            .catalog {
                margin-left: 0% !important;
            }

            .border-top {
                border-top: 5px solid #55ccb9 !important;
            }

            .border-footer {
                border-top: 1px dashed #ccc !important;
            }

            .border {
                border: 1px solid #cccccc !important;
            }
        }

        @media screen and (max-width: 480px) {
            /*styling for objects with screen size less than 480px; */
            body, table, td, p, a, li, blockquote {
                -webkit-text-size-adjust: none !important;
                font-family: 'Roboto', sans-serif;
            }

            table {
                /* All tables are 100% width */
                width: 100% !important;
                border-style: none !important;
            }

            .footer {
                /* Each footer column in this case should occupy 96% width  and 4% is allowed for email client padding*/
                height: auto !important;
                max-width: 96% !important;
                width: 96% !important;
            }

            .table.responsiveImage {
                /* Container for each image now specifying full width */
                height: auto !important;
                max-width: 96% !important;
                width: 96% !important;
            }

            .responsiveImage {
                height: 40px !important;
                max-width: 40px !important;
                width: 40px !important;
            }

            .table.responsiveContent {
                /* Content in catalog  occupying full width of cell */
                height: auto !important;
                max-width: 96% !important;
                width: 96% !important;
            }

            .top {
                /* Header columns occupying full width */
                height: auto !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .catalog {
                margin-left: 0% !important;
            }

            button {
                width: 90% !important;
            }

            .border-top {
                border-top: 5px solid #55ccb9 !important;
            }

            .border-footer {
                border-top: 1px dashed #ccc !important;
            }

            .border {
                border: 1px solid #cccccc !important;
            }

            .footer-responsive {
                display: block;
            }

            .footer-pc {
                display: none;
            }

            .size {
                font-size: 11px;
                width: 200px !important;

            }

            .size_text {
                font-size: 11px;
                width: 200px !important;
            }

            .font_res {
                font-size: 13px;
            }
        }
    </style>
</head>
<body yahoo="yahoo">
<table width="100%" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td>

            <table width="600" align="center" cellpadding="0" cellspacing="0" style="background: #ccc">
                <!-- Main Wrapper Table with initial width set to 60opx -->
                <tbody>
                <tr>
                    <td>
                        <table bgcolor="#fff" class="top border-top" width="100%" height="100px
				  " align="left" cellpadding="0" cellspacing="0"
                               style="padding:10px 10px 10px 10px; text-align:right;border-top: 10px solid #55ccb9;background-color:{{'#'.$config_template->background_header}};">
                            <!-- Second header column with ISSUE|DATE -->
                            <tbody>
                            <tr style="text-align: center;">
                                <td style="font-size: 16px; color:#929292; text-align:center; font-family: sans-serif;">
                                    @if($spa_info->logo!=null)
                                        <img src="{{asset($spa_info->logo)}}" width="90px" height="70px"><br>
                                    @else
                                        <img src="{{asset('static/backend/images/template-email/logo-slider.png')}}"
                                             width="90px"
                                             height="70px"><br>
                                    @endif
                                    <span style="font-weight: bold; color:{{'#'.$config_template->color_header}};text-transform: uppercase;">{{$spa_info->name}}</span>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <!-- HTML Spacer row -->

                </tr>
                <tr>
                    <!-- HTML IMAGE SPACER -->

                </tr>
                <tr>
                    <!-- HTML Spacer row -->            </tr>
                <tr>
                    <!-- Introduction area -->
                    <td style="background: #f4f4f4;padding-right: 20px;padding-left: 20px;">
                        <table class="border-top" width="100%" align="left" cellpadding="0" cellspacing="0"
                               style="background: {{'#'.$config_template->background_body}}; border-top: 5px solid #55ccb9">
                            <tr>
                                <!-- Row container for Intro/ Description -->
                                <td align="left" class="font_res"
                                    style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$config_template->color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                    @if($type=='print_card')
                                        @foreach($content_sent as $item)
                                            <img class="img-card" src="{{asset($item)}}"
                                                 style="margin-bottom: 15px;width: 80%;"/>
                                        @endforeach
                                    @else
                                        {!! $content !!}
                                    @endif
                                </td>
                            </tr>
                            @if($type=='paysuccess')
                                <tr>
                                    <td align="left" class="footer-pc"
                                        style="font-size: 14px; font-style: normal; font-weight: 100; color:{{'#'.$config_template->color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                        <table width="100%"
                                               style="margin-bottom: 15px;border-collapse: collapse;">
                                            <tbody>
                                            <tr style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                <td style="padding-left:10px;">{{__('Sản phẩm')}}</td>
                                                <td></td>
                                                <td>{{__('Giá')}}</td>
                                                <td>{{__('SL')}}</td>
                                                <td>{{__('Giảm')}}</td>
                                                <td>{{__('Thành tiền')}}</td>
                                            </tr>
                                            @foreach($order_detail as $key=>$value)

                                                <tr style="border-bottom: 1px dashed #ccc;">
                                                    <td style="padding:10px 0 10px 0;">
                                                        @if($value['image']!=null)
                                                            <img src="{{asset($value['image'])}}" width="100px"
                                                                 height="70px;"
                                                                 class="responsiveImage">
                                                        @else
                                                            <img src="{{asset('static/backend/images/template-email/Layer8.png')}}"
                                                                 width="100px"
                                                                 height="70px;"
                                                                 class="responsiveImage">
                                                        @endif
                                                    </td>
                                                    <td style="width: 160px;line-height: 16px;padding:10px 20px 10px 0;">
                                                        {{$value['object_name']}}
                                                    </td>
                                                    <td style="padding:10px 0 10px 0;">{{number_format($value['price'])}}</td>
                                                    <td style="text-align: center;padding:10px 0 10px 0;">
                                                        {{$value['quantity']}}
                                                    </td>
                                                    <td style="padding:10px 0 10px 0;">{{number_format($value['discount'])}}</td>
                                                    <td style="font-weight: bold;padding:10px 0 10px 0;">{{number_format($value['amount'])}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td style="padding-top:10px;">
                                                    {{__('Tổng tiền')}}:
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="font-weight: bold;padding-top:10px;">{{number_format($order->total)}}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{__('Giảm')}}:
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="font-weight: bold;">{{number_format($order->discount)}}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom:10px;">
                                                    {{__('Thành tiền')}}:
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="font-weight: bold;">{{number_format($order->amount)}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td align="left" class="footer-responsive"
                                        style="font-size: 13px; font-style: normal; font-weight: 100; color:{{'#'.$config_template->color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                        <table width="100%" style="margin-bottom: 10px;border-collapse: collapse;">
                                            <tbody>
                                            @foreach($order_detail as $key=>$value)
                                                <tr style="border-bottom: 1px dashed #ccc;width: 100%;">
                                                    <td>
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td style="padding-top:5px;width: 60px;">
                                                                    @if($value['image']!=null)
                                                                        <img src="{{asset($value['image'])}}"
                                                                             width="100px"
                                                                             height="70px;" class="responsiveImage">
                                                                    @else
                                                                        <img src="{{asset('static/backend/images/template-email/Layer8.png')}}"
                                                                             width="100px"
                                                                             height="70px;" class="responsiveImage">
                                                                    @endif
                                                                </td>
                                                                <td style="padding-top:5px;font-size: 11px;">
                                                                    @if($value['object_type']=='service')
                                                                        <span><strong>@lang('Dịch vụ'): {{$value['object_name']}}</strong></span>
                                                                        <br>
                                                                    @elseif($value['object_type']=='product')
                                                                        <span><strong>@lang('Sản phẩm'): {{$value['object_name']}}</strong></span>
                                                                        <br>
                                                                    @elseif($value['object_type']=='service_card')
                                                                        <span><strong>@lang('Thẻ dịch vụ'): {{$value['object_name']}}</strong></span>
                                                                        <br>
                                                                    @else
                                                                        <span><strong> {{$value['object_name']}}</strong></span>
                                                                        <br>
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 11px;width: 80px;">{{__('Giá')}}:</td>
                                                                <td style="font-size: 11px;">
                                                                    <strong>{{number_format($value['price'])}}</strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 11px;width: 80px;">{{__('Số lượng')}}:</td>
                                                                <td style="font-size: 11px;">
                                                                    <strong>{{$value['quantity']}}</strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 11px;width: 80px;">{{__('Giảm')}}:</td>
                                                                <td style="font-size: 11px;">
                                                                    <strong>{{number_format($value['discount'])}}</strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-bottom: 5px;font-size: 11px;width: 80px;">
                                                                    {{__('Thành tiền')}}:
                                                                </td>
                                                                <td style="padding-bottom: 5px;font-size: 11px;">
                                                                    <strong>{{number_format($value['amount'])}}</strong>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr style="border-bottom: 1px dashed #ccc;">
                                                <td style="width: 100%;">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td style="padding-top:5px;width: 60px;">

                                                            </td>
                                                            <td style="padding-top:5px;font-size: 11px;">

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 11px;width: 80px">{{__('Tổng tiền')}}:</td>
                                                            <td style="font-size: 11px;">
                                                                <strong>{{number_format($order->total)}}</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 11px;width: 80px">{{__('Giảm')}}:</td>
                                                            <td style="font-size: 11px;">
                                                                <strong>{{number_format($order->discount)}}</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-bottom:5px;font-size: 11px;width: 80px">
                                                                {{__('Thành tiền')}}:
                                                            </td>
                                                            <td style="padding-bottom:5px;font-size: 11px;">
                                                                <strong>{{number_format($order->amount)}}</strong>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <!-- Row container for Intro/ Description -->
                                    <td align="left"
                                        style="font-size: 14px; font-style: normal; font-weight: 100; color: black; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                        <table class="border" width="100%"
                                               style="margin-bottom: 10px;border-collapse: collapse;border: 1px solid #cccccc;color:{{'#'.$config_template->color_body}}">
                                            <tbody>
                                            <tr style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                <td style="padding-left:10px;">{{__('Thông tin đơn hàng')}}</td>

                                            </tr>
                                            <tr>
                                                <td style="padding-left:10px;">
                                                    <table>
                                                        <tr class="footer-pc">
                                                            <td class="size">{{__('Họ & tên')}}:</td>
                                                            <td class="size_text">{{$order->customer_name}}</td>
                                                        </tr>
                                                        <tr class="footer-responsive font_res">
                                                            <td>
                                                                <span class="size">{{__('Họ & tên')}}:</span>
                                                                <span class="size_text">{{$order->customer_name}}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td style="padding-left:10px;">
                                                    <table>
                                                        <tr class="footer-pc">
                                                            <td class="size">{{__('Số điện thoại')}}:</td>
                                                            <td class="size_text">{{$order->customer_phone}}</td>
                                                        </tr>
                                                        <tr class="footer-responsive font_res">
                                                            <td>
                                                                <span class="size">{{__('Số điện thoại')}}:</span>
                                                                <span class="size_text">{{$order->customer_phone}}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:10px;">
                                                    <table>
                                                        <tr class="footer-pc">
                                                            <td class="size">{{__('Email')}}:</td>
                                                            <td class="size_text">{{$order->customer_email}}</td>
                                                        </tr>
                                                        <tr class="footer-responsive font_res">
                                                            <td>
                                                                <span class="size">{{__('Email')}}:</span>
                                                                <span class="size_text">{{$order->customer_email}}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:10px;">
                                                    <table>
                                                        <tr class="footer-pc">
                                                            <td class="size">{{__('Chi nhánh thực hiện')}}:</td>
                                                            <td class="size_text">{{$order->branch_name}}</td>
                                                        </tr>
                                                        <tr class="footer-responsive font_res">
                                                            <td>
                                                                <span class="size">{{__('Chi nhánh thực hiện')}}:</span>
                                                                <span class="size_text">{{$order->branch_name}}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:10px;">
                                                    <table>
                                                        <tr class="footer-pc">
                                                            <td class="size">{{__('Kỹ thuật viên phục vụ')}}:</td>
                                                            <td class="size_text">{{$order->staff_name}}</td>
                                                        </tr>
                                                        <tr class="footer-responsive font_res">
                                                            <td>
                                                                <span class="size">{{__('Kỹ thuật viên phục vụ')}}:</span>
                                                                <span class="size_text">{{$order->staff_name}}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:10px;">
                                                    <table>
                                                        <tr class="footer-pc">
                                                            <td class="size">{{__('Thời gian thực hiện')}}:</td>
                                                            <td class="size_text">{{date("H:i",strtotime($order->created_at)).' '.date("d/m/Y",strtotime($order->created_at))}}</td>
                                                        </tr>
                                                        <tr class="footer-responsive font_res">
                                                            <td>
                                                                <span class="size">{{__('Thời gian thực hiện')}}:</span>
                                                                <span class="size_text">{{date("H:i",strtotime($order->created_at)).' '.date("d/m/Y",strtotime($order->created_at))}}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left:10px;">
                                                    <table>
                                                        <tr class="footer-pc">
                                                            <td class="size">{{__('Ghi chú')}}:</td>
                                                            <td class="size_text">{{$order->note}}</td>
                                                        </tr>
                                                        <tr class="footer-responsive font_res">
                                                            <td>
                                                                <span class="size">{{__('Ghi chú')}}:</span>
                                                                <span class="size_text">{{$order->note}}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>

                                </tr>
                            @endif
                            @if($type=='new_appointment')
                                @if(isset($listService)&&count($listService)>0)
                                    <tr>
                                        <td align="left" class="footer-pc"
                                            style="font-size: 14px; font-style: normal; font-weight: 100; color:{{'#'.$config_template->color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                            <table width="100%"
                                                   style="margin-bottom: 15px;border-collapse: collapse;">
                                                <tbody>
                                                <tr style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                    <td style="padding-left:10px;"></td>
                                                    <td>{{__('Dịch vụ')}}</td>
                                                    <td>{{__('Giá')}}</td>
                                                </tr>
                                                @foreach($listService as $key=>$value)
                                                    <tr style="border-bottom: 1px dashed #ccc;">
                                                        <td style="padding:10px 0 10px 0;">
                                                            @if($value->service_avatar!=null)
                                                                <img src="{{asset($value->service_avatar)}}"
                                                                     width="100px"
                                                                     height="70px;"
                                                                     class="responsiveImage">
                                                            @else
                                                                <img src="{{asset('static/backend/images/template-email/Layer8.png')}}"
                                                                     width="100px"
                                                                     height="70px;"
                                                                     class="responsiveImage">
                                                            @endif
                                                        </td>
                                                        <td style="width: 160px;line-height: 16px;padding:10px 20px 10px 0;">
                                                            {{$value->service_name}}
                                                        </td>
                                                        <td style="padding:10px 0 10px 0;">{{number_format($value->new_price)}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                        <td align="left" class="footer-responsive"
                                            style="font-size: 13px; font-style: normal; font-weight: 100; color:{{'#'.$config_template->color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                            <table width="100%" style="margin-bottom: 10px;border-collapse: collapse;">
                                                <tbody>
                                                <tr style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                    <td style="padding-left:10px;"></td>
                                                    <td>{{__('Dịch vụ')}}</td>
                                                    <td>{{__('Giá')}}</td>
                                                </tr>
                                                @foreach($listService as $key=>$value)
                                                    <tr style="border-bottom: 1px dashed #ccc;width: 100%;">
                                                        <td style="padding:10px 0 10px 0;">
                                                            @if($value->service_avatar!=null)
                                                                <img src="{{asset($value->service_avatar)}}"
                                                                     width="100px"
                                                                     height="70px;"
                                                                     class="responsiveImage">
                                                            @else
                                                                <img src="{{asset('static/backend/images/template-email/Layer8.png')}}"
                                                                     width="100px"
                                                                     height="70px;"
                                                                     class="responsiveImage">
                                                            @endif
                                                        </td>
                                                        <td style="width: 160px;line-height: 16px;padding:10px 20px 10px 0;">
                                                            {{$value->service_name}}
                                                        </td>
                                                        <td style="padding:10px 0 10px 0;">{{number_format($value->new_price)}}</td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <!-- Row container for Intro/ Description -->
                                    <td align="left" class="font_res"
                                        style="font-size: 14px; font-style: normal; font-weight: 100; color: black; line-height: 1.8; text-align:center; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                        <span style="color:{{'#'.$config_template->color_body}}">{{__('Vui lòng nhấp vào link liên kết dưới đây để xác nhận lịch hẹn của bạn thành công')}}</span><br>
                                        <a href="{{route('admin.customer_appointment.confirm',$id)}}"
                                           style="background-color: #55ccb9;color: black;border: 2px solid #55ccb9;padding: 5px 40px;text-align: center;text-decoration: none;display: inline-block;font-size: 13px;margin-left: 20px;margin-bottom: 20px;margin-top: 10px;color: #fff;">{{__('XÁC NHẬN')}}</a>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
                <tr>
                    <!-- Introduction area -->
                    <td style="background: #f4f4f4;padding-right: 20px;padding-left: 20px;">
                        <table width="100%" align="left" cellpadding="0" cellspacing="0"
                               style="background: {{'#'.$config_template->background_footer}};">
                            <tr>
                                <!-- Row container for Intro/ Description -->
                                <td align="left"
                                    style="font-size: 14px; font-style: normal; font-weight: 100; line-height: 1.8; text-align:center; padding:0 20px 0 20px; font-family: sans-serif;background: #fff;background: {{'#'.$config_template->background_footer}};">
                                    <table class="border-footer" width="100%"
                                           style="margin-bottom: 15px;border-collapse: collapse;border-top: 1px dashed #ccc;color: {{'#'.$config_template->color_footer}};">
                                        <tr class="footer-pc">
                                            <td style="text-align: left;padding-top: 10px;width: 60%;">
                                                <span style="font-size:14px;font-weight: bold;">{{__('Địa chỉ')}}:</span><br>
                                                <span style="font-size: 14px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                <span style="font-size: 14px;"><strong>{{__('Điện thoại')}}:</strong> {{$spa_info->phone}}</span><br>
                                                {{--<span style="font-size: 14px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span>--}}
                                            </td>
                                            <td style="padding-top: 10px;text-align: left;width: 40%;padding-top: 10px;">
                                                <span style="font-size:14px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                @if (isset($timeWorking) && count($timeWorking) > 0)
                                                    @foreach($timeWorking as $t)
                                                        <span style="font-size: 14px;">
                                                            {{\Carbon\Carbon::parse($t->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($t->end_time)->format('H:i')}},
                                                            {{$t->vi_name}}
                                                        </span><br>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="footer-responsive">
                                            <td style="text-align: left;padding-top: 10px;">
                                                <span style="font-size:13px;font-weight: bold;">{{__('Địa chỉ')}}:</span>
                                                <span style="font-size: 13px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                <span style="font-size: 13px;"><strong>{{__('Điện thoại')}}:</strong> {{$spa_info->phone}}</span><br>
                                                {{--<span style="font-size: 13px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span><br>--}}
                                                <span style="font-size:13px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                @if (isset($timeWorking) && count($timeWorking) > 0)
                                                    @foreach($timeWorking as $t)
                                                        <span style="font-size: 13px;">
                                                            {{\Carbon\Carbon::parse($t->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($t->end_time)->format('H:i')}},
                                                            {{$t->vi_name}}
                                                        </span><br>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr bgcolor="#f4f4f4">
                    <td>
                        <table class="footer" width="100%" align="left" cellpadding="0" cellspacing="0">
                            <!-- First column of footer content -->
                            <tr>
                                <td><p align="center" class="font_res"
                                       style="font-size: 14px; font-weight:200; line-height: 2.5em; color: #929292; font-family: sans-serif;">
                                        {{__('@ copyright 2019 - Bản quyền thuộc về piospa')}}</p>
                                </td>
                            </tr>
                        </table>

                    </td>

                </tr>
                </tbody>
            </table>


        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
