<div class="modal fade show" id="modal-view">
    <div class="modal-dialog modal-dialog-centered  modal-lg-email-auto" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-eye"></i> {{__('XEM TRƯỚC')}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body append_body">
                @if($email_provider['email_template_id'] == 1)
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
                            .border-top {
                                border-top: 5px solid #55ccb9 !important;
                            }

                            /*button {*/
                            /*width: 90%;*/
                            /*}*/

                            @media screen and (max-width: 600px) {
                                /*styling for objects with screen size less than 600px; */
                                body, table, td, p, a, li, blockquote {
                                    -webkit-text-size-adjust: none !important;

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
                                    font-family: sans-serif;
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
                                    font-size: 13px !important;
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
                                            <table bgcolor="#fff" class="top border-top" width="100%" height="100px" align="left" cellpadding="0" cellspacing="0"
                                                   style="padding:10px 10px 10px 10px; text-align:right;border-top: 10px solid #55ccb9;background-color:{{'#'.$background_header}};">
                                                <!-- Second header column with ISSUE|DATE -->
                                                <tbody>
                                                <tr style="text-align: center;">
                                                    <td style="font-size: 16px; color:#929292; text-align:center; font-family: sans-serif;">
                                                        @if($spa_info->logo!=null)
                                                            <img src="{{asset($spa_info->logo)}}" width="90px" height="70px"><br>
                                                        @else
                                                            <img src="{{asset('static/backend/images/template-email/logo-slider.png')}}" width="90px"
                                                                 height="70px"><br>
                                                        @endif
                                                        <span style="font-weight: bold; color:{{'#'.$color_header}};text-transform: uppercase;">{{$spa_info->name}}</span>
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
                                                   style="background: {{'#'.$background_body}}; border-top: 5px solid #55ccb9">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" class="font_res"
                                                        style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        {!! $content !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        <table class="border" width="100%" style="margin-bottom: 10px;border-collapse: collapse;border: 1px solid #cccccc;">
                                                            <tbody>
                                                            <tr style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                                <td style="padding-left:10px;">{{__('Thông tin đơn hàng')}}</td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Họ và tên')}}:</td>
                                                                            <td class="size_text">Nguyễn Ngọc Sơn</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Họ và tên')}}:</span>
                                                                                <span class="size_text">Nguyễn Ngọc Sơn</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Số điện thoại')}}:</td>
                                                                            <td class="size_text">0794212390</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Số điện thoại')}}:</span>
                                                                                <span class="size_text">01214212390</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Email')}}:</td>
                                                                            <td class="size_text">b2dontcry@gmail.com</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Email')}}:</span>
                                                                                <span class="size_text">b2dontcry@gmail.com</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Chi nhánh thực hiện')}}:</td>
                                                                            <td class="size_text">Nam Long quận 7</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Chi nhánh thực hiện')}}:</span>
                                                                                <span class="size_text">Nam Long quận 7</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Kỹ thuật viên phục vụ')}}:</td>
                                                                            <td class="size_text">Lê Đăng Sinh</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Kỹ thuật viên phục vụ')}}:</span>
                                                                                <span class="size_text">Lê Đăng Sinh</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Thời gian thực hiện')}}:</td>
                                                                            <td class="size_text">13:00 13/04/2019</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Thời gian thực hiện')}}:</span>
                                                                                <span class="size_text">13:00 13/04/2019</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Ghi chú')}}:</td>
                                                                            <td class="size_text">Dễ bị dị ứng, cơ địa sẹo lồi.</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Ghi chú')}}:</span>
                                                                                <span class="size_text">Dễ bị dị ứng, cơ địa sẹo lồi.</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>

                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <!-- Introduction area -->
                                        <td style="background: #f4f4f4;padding-right: 20px;padding-left: 20px;">
                                            <table width="100%" align="left" cellpadding="0" cellspacing="0"
                                                   style="background: {{'#'.$background_footer}};">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left"
                                                        style="font-size: 14px; font-style: normal; font-weight: 100; line-height: 1.8; text-align:center; padding:0 20px 0 20px; font-family: sans-serif;background: #fff;background: {{'#'.$background_footer}};">
                                                        <table class="border-footer" width="100%"
                                                               style="margin-bottom: 15px;border-collapse: collapse;border-top: 1px dashed #ccc;color: {{'#'.$color_footer}};">
                                                            <tr class="footer-pc">
                                                                <td style="text-align: left;padding-top: 10px;width: 60%;">
                                                                    <span style="font-size:14px;font-weight: bold;">{{__('Địa chỉ')}}:</span><br>
                                                                    <span style="font-size: 14px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                                    <span style="font-size: 14px;"><strong>{{__('Điện thoại')}}:</strong> {{$spa_info->phone}}</span><br>
                                                                    <span style="font-size: 14px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span>
                                                                </td>
                                                                <td style="padding-top: 10px;text-align: left;width: 40%;padding-top: 10px;">
                                                                    <span style="font-size:14px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                                    <span style="font-size: 14px;"> {{__('08h00 - 18h00, Thứ 2 - Thứ 6')}}</span><br>
                                                                    <span style="font-size: 14px;"> {{__('09h00 - 20h00, Thứ 7 - Chủ nhật')}}</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="footer-responsive">
                                                                <td style="text-align: left;padding-top: 10px;">
                                                                    <span style="font-size:13px;font-weight: bold;">{{__('Địa chỉ')}}:</span>
                                                                    <span style="font-size: 13px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                                    <span style="font-size: 13px;"><strong>{{__('Điện thoại')}}:</strong> {{$spa_info->phone}}</span><br>
                                                                    <span style="font-size: 13px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span><br>
                                                                    <span style="font-size:13px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                                    <span style="font-size: 13px;"> {{__('08h00 - 18h00, Thứ 2 - Thứ 6')}}</span><br>
                                                                    <span style="font-size: 13px;"> {{__('09h00 - 20h00, Thứ 7 - Chủ nhật')}}</span>
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

                @elseif($email_provider['email_template_id'] == 2)
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

                                font-style: normal;
                                /*	font-weight: 400;*/
                            }



                            .footer-responsive {
                                display: none;
                            }

                            .size {
                                width: 150px;
                            }

                            .size_text {
                                width: 350px;
                            }
                            .border-top {
                                border-top: 5px solid #55ccb9 !important;
                            }
                            @media screen and (max-width: 600px) {
                                /*styling for objects with screen size less than 600px; */
                                body, table, td, p, a, li, blockquote {
                                    -webkit-text-size-adjust: none !important;

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

                                .table-pc {
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
                                    font-size: 13px !important;
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
                                                   style="padding:10px 10px 10px 10px; text-align:right;border-top: 10px solid #55ccb9;background-color:{{'#'.$background_header}};">
                                                <!-- Second header column with ISSUE|DATE -->
                                                <tbody>
                                                <tr>
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
                                        <td style="background: #55ccb9;padding-right: 20px;padding-left: 20px;">
                                            <table width="100%" align="left" cellpadding="0" cellspacing="0" style="background: {{'#'.$background_body}};">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    @if($config_template->image!=null)
                                                        <td style="font-style: normal;  line-height: 1.8; text-align:center; padding:10px 20px 0px 20px; font-family: sans-serif;height: 100px;vertical-align: middle;background-image: url({{asset($config_template->image)}});background-size:  cover;">
                                                            <span style="font-size: 13px;color: #fff;font-weight: bold;text-transform: uppercase;">{{$title}}</span>
                                                        </td>
                                                    @else
                                                        <td style="font-style: normal;  line-height: 1.8; text-align:center; padding:10px 20px 0px 20px; font-family: sans-serif;height: 100px;vertical-align: middle;background-image: url({{asset('static/backend/images/template-email/slider2.jpg')}});background-size:  cover;">
                                                            <span style="font-size: 13px;color: #fff;font-weight: bold;text-transform: uppercase;">{{$title}}</span>
                                                        </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" class="font_res"
                                                        style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        {!! $content !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        <table class="border" width="100%" style="margin-bottom: 10px;border-collapse: collapse;border: 1px solid #cccccc;">
                                                            <tbody>
                                                            <tr style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                                <td style="padding-left:10px;">{{__('Thông tin đơn hàng')}}</td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Họ & tên')}}:</td>
                                                                            <td class="size_text">Nguyễn Ngọc Sơn</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Họ & tên')}}:</span>
                                                                                <span class="size_text">Nguyễn Ngọc Sơn</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Số điện thoại')}}:</td>
                                                                            <td class="size_text">0794212390</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Số điện thoại')}}:</span>
                                                                                <span class="size_text">01214212390</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Email')}}:</td>
                                                                            <td class="size_text">b2dontcry@gmail.com</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Email')}}:</span>
                                                                                <span class="size_text">b2dontcry@gmail.com</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Chi nhánh thực hiện')}}:</td>
                                                                            <td class="size_text">Nam Long quận 7</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Chi nhánh thực hiện')}}:</span>
                                                                                <span class="size_text">Nam Long quận 7</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Kỹ thuật viên phục vụ')}}:</td>
                                                                            <td class="size_text">Lê Đăng Sinh</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Kỹ thuật viên phục vụ')}}:</span>
                                                                                <span class="size_text">Lê Đăng Sinh</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Thời gian thực hiện')}}:</td>
                                                                            <td class="size_text">13:00 13/04/2019</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Thời gian thực hiện')}}:</span>
                                                                                <span class="size_text">13:00 13/04/2019</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Ghi chú')}}:</td>
                                                                            <td class="size_text">Dễ bị dị ứng, cơ địa sẹo lồi.</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Ghi chú')}}:</span>
                                                                                <span class="size_text">Dễ bị dị ứng, cơ địa sẹo lồi.</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>

                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- Introduction area -->
                                        <td style="background: #55ccb9;padding-right: 20px;padding-left: 20px;">
                                            <table width="100%" align="left" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left"
                                                        style="font-size: 14px; font-style: normal; font-weight: 100;  line-height: 1.8; text-align:center; padding:0 20px 0 20px; font-family: sans-serif;background: {{'#'.$background_footer}};">
                                                        <table class="border-footer" width="100%"
                                                               style="margin-bottom: 15px;border-collapse: collapse;border-top: 1px dashed #ccc;color: {{'#'.$color_footer}};">
                                                            <tr class="footer-pc">
                                                                <td style="text-align: left;padding-top: 10px;width: 60%;">
                                                                    <span style="font-size:14px;font-weight: bold;">{{__('Địa chỉ')}}:</span><br>
                                                                    <span style="font-size: 14px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                                    <span style="font-size: 14px;"><strong>Điện thoại:</strong> {{$spa_info->phone}}</span><br>
                                                                    <span style="font-size: 14px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span>
                                                                </td>
                                                                <td style="padding-top: 10px;text-align: left;width: 40%;padding-top: 10px;">
                                                                    <span style="font-size:14px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                                    <span style="font-size: 14px;"> 08h00 - 18h00, Thứ 2 - Thứ 6</span><br>
                                                                    <span style="font-size: 14px;"> 09h00 - 20h00, Thứ 7 - Chủ nhật</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="footer-responsive">
                                                                <td style="text-align: left;padding-top: 10px;">
                                                                    <span style="font-size:13px;font-weight: bold;">{{__('Địa chỉ')}}:</span>
                                                                    <span style="font-size: 13px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                                    <span style="font-size: 13px;"><strong>Điện thoại:</strong> {{$spa_info->phone}}</span><br>
                                                                    <span style="font-size: 13px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span><br>
                                                                    <span style="font-size:13px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                                    <span style="font-size: 13px;"> 08h00 - 18h00, Thứ 2 - Thứ 6</span><br>
                                                                    <span style="font-size: 13px;"> 09h00 - 20h00, Thứ 7 - Chủ nhật</span>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>


                                    <tr bgcolor="#55ccb9">
                                        <td>
                                            <table class="footer" width="100%" align="left" cellpadding="0" cellspacing="0">
                                                <!-- First column of footer content -->
                                                <tr>
                                                    <td><p align="center" class="font_res"
                                                           style="font-size: 14px; font-weight:200; line-height: 2.5em; color: #fff; font-family: sans-serif;">
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

                @elseif($email_provider['email_template_id'] == 3)
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
                                font-family: sans-serif;
                                font-style: normal;
                                /*	font-weight: 400;*/
                            }
                            .footer-responsive {
                                display: none;
                            }

                            .size {
                                width: 150px;
                            }

                            .size_text {
                                width: 350px;
                            }

                            @media screen and (max-width: 600px) {
                                /*styling for objects with screen size less than 600px; */
                                body, table, td, p, a, li, blockquote {
                                    -webkit-text-size-adjust: none !important;
                                    font-family: sans-serif;
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
                                    height: 15px !important;
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
                                    font-family: sans-serif;
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
                                    height: 10px !important;
                                    max-width: 100% !important;
                                    width: 100% !important;
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

                                .footer-responsive {
                                    display: block;


                                }

                                .footer-pc {
                                    display: none;
                                }

                                .table-pc {
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

                                .size_title {
                                    font-size: 0.55rem !important;
                                }
                                .font_res {
                                    font-size: 13px !important;
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
                                            <table bgcolor="#fff" class="top" width="100%" align="left" cellspacing="0" style="padding:10px 10px 10px 10px; text-align:right;background-image: url({{asset('static/backend/images/template-email/header-line.png')}});background-position: top;background-repeat: no-repeat;background-size: 100%;height: 15px">
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table bgcolor="{{'#'.$background_header}}" class="top" width="100%" align="left" cellpadding="0"
                                                   cellspacing="0"
                                                   style="padding:10px 10px 10px 10px; text-align:right;">
                                                <!-- Second header column with ISSUE|DATE -->
                                                <tbody>
                                                <tr>
                                                    <td style="font-size: 12px;text-align:center; font-family: sans-serif;width: 70%;text-align: left;padding-left: 10px;">
                                    <span class="size_title"
                                          style="font-size:14px;font-weight: bold; color:{{'#'.$color_header}};text-transform: uppercase;vertical-align: middle;">
                                        {{$title}}
                                    </span>
                                                    </td>
                                                    <td style="font-size: 12px; color:#929292;font-family: sans-serif;width: 30%;text-align: center;">
                                                        @if($spa_info->logo!=null)
                                                            <img src="{{asset($spa_info->logo)}}" width="90px" height="70px"><br>
                                                        @else
                                                            <img src="{{asset('static/backend/images/template-email/logo-slider.png')}}"
                                                                 width="90px"
                                                                 height="70px"><br>
                                                        @endif
                                                        <span class="size_title"
                                                              style="font-size:16px;font-weight: bold; color:{{'#'.$color_header}};text-transform: uppercase;">
                                                        {{$spa_info->name}}
                                    </span>
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
                                            <table width="100%" align="left" cellpadding="0" cellspacing="0"
                                                   style="background: {{'#'.$background_body}};">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    @if($config_template->image!=null)
                                                        <td style="font-style: normal;  line-height: 1.8; text-align:center; padding:10px 20px 0px 20px; font-family: sans-serif;height: 80px;vertical-align: middle;background-image: url({{asset($config_template->image)}});background-size:  cover;"></td>
                                                    @else
                                                        <td style="font-style: normal;  line-height: 1.8; text-align:center; padding:10px 20px 0px 20px; font-family: sans-serif;height: 80px;vertical-align: middle;background-image: url({{asset('static/backend/images/template-email/slider2.jpg')}});background-size:  cover;"></td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" class="font_res"
                                                        style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        {!! $content !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        <table class="border" width="100%" style="margin-bottom: 10px;border-collapse: collapse;border: 1px solid #cccccc;">
                                                            <tbody>
                                                            <tr style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                                <td style="padding-left:10px;">{{__('Thông tin đơn hàng')}}</td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">Họ &amp; tên:</td>
                                                                            <td class="size_text">Nguyễn Ngọc Sơn</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">Họ &amp; tên:</span>
                                                                                <span class="size_text">Nguyễn Ngọc Sơn</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Số điện thoại')}}:</td>
                                                                            <td class="size_text">0794212390</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Số điện thoại')}}:</span>
                                                                                <span class="size_text">01214212390</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Email')}}:</td>
                                                                            <td class="size_text">b2dontcry@gmail.com</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Email')}}:</span>
                                                                                <span class="size_text">b2dontcry@gmail.com</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Chi nhánh thực hiện')}}:</td>
                                                                            <td class="size_text">Nam Long quận 7</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Chi nhánh thực hiện')}}:</span>
                                                                                <span class="size_text">Nam Long quận 7</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Kỹ thuật viên phục vụ')}}:</td>
                                                                            <td class="size_text">Lê Đăng Sinh</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Kỹ thuật viên phục vụ')}}:</span>
                                                                                <span class="size_text">Lê Đăng Sinh</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Thời gian thực hiện')}}:</td>
                                                                            <td class="size_text">13:00 13/04/2019</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Thời gian thực hiện')}}:</span>
                                                                                <span class="size_text">13:00 13/04/2019</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Ghi chú')}}:</td>
                                                                            <td class="size_text">Dễ bị dị ứng, cơ địa sẹo lồi.</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Ghi chú')}}:</span>
                                                                                <span class="size_text">Dễ bị dị ứng, cơ địa sẹo lồi.</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>

                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- Introduction area -->
                                        <td style="background: #f4f4f4;padding-right: 20px;padding-left: 20px;">
                                            <table width="100%" align="left" cellpadding="0" cellspacing="0" style="background: #fff;">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left"
                                                        style="font-size: 13px; font-style: normal; font-weight: 100; color: black; line-height: 1.8; text-align:center; padding:0 20px 0 20px; font-family: sans-serif;background: {{'#'.$background_footer}};">
                                                        <table class="border-footer" width="100%"
                                                               style="margin-bottom: 15px;border-collapse: collapse;border-top: 1px dashed #ccc;;color: {{'#'.$color_footer}};">
                                                            <tr class="footer-pc">
                                                                <td style="text-align: left;padding-top: 10px;width: 60%;">
                                                                    <span style="font-size:14px;font-weight: bold;">{{__('Địa chỉ')}}:</span><br>
                                                                    <span style="font-size: 14px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                                    <span style="font-size: 14px;"><strong>Điện thoại:</strong> {{$spa_info->phone}}</span><br>
                                                                    <span style="font-size: 14px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span>
                                                                </td>
                                                                <td style="padding-top: 10px;text-align: left;width: 40%;padding-top: 10px;">
                                                                    <span style="font-size:14px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                                    <span style="font-size: 14px;"> 08h00 - 18h00, Thứ 2 - Thứ 6</span><br>
                                                                    <span style="font-size: 14px;"> 09h00 - 20h00, Thứ 7 - Chủ nhật</span>
                                                                </td>
                                                            </tr>
                                                            <tr class="footer-responsive">
                                                                <td style="text-align: left;padding-top: 10px;">
                                                                    <span style="font-size:13px;font-weight: bold;">{{__('Địa chỉ')}}:</span>
                                                                    <span style="font-size: 13px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                                    <span style="font-size: 13px;"><strong>Điện thoại:</strong> {{$spa_info->phone}}</span><br>
                                                                    <span style="font-size: 13px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span><br>
                                                                    <span style="font-size:13px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                                    <span style="font-size: 13px;"> 08h00 - 18h00, Thứ 2 - Thứ 6</span><br>
                                                                    <span style="font-size: 13px;"> 09h00 - 20h00, Thứ 7 - Chủ nhật</span>
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

                @elseif($email_provider['email_template_id'] == 4)
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

                                font-style: normal;
                                /*	font-weight: 400;*/
                            }

                            .footer-responsive {
                                display: none;
                            }

                            .size {
                                width: 150px;
                            }

                            .size_text {
                                width: 350px;
                            }

                            .table_info {

                                width: 70%;
                            }

                            .table_address {

                                width: 30%;
                            }
                            .info_res{
                                display: none;
                            }
                            @media screen and (max-width: 600px) {
                                /*styling for objects with screen size less than 600px; */
                                body, table, td, p, a, li, blockquote {
                                    -webkit-text-size-adjust: none !important;

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
                                    font-family: sans-serif;
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

                                .size_title {
                                    font-size: 0.55rem !important;
                                }

                                .table-pc {
                                    display: none;
                                }

                                .table_info {

                                    width: 100%;
                                }

                                .tb_res {
                                    display: none;
                                }
                                .info_res{
                                    display: block;
                                }
                                .font_res {
                                    font-size: 13px !important;
                                }
                            }
                        </style>
                    </head>
                    <body yahoo="yahoo">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td>
                                <table width="700" align="center" cellpadding="0" cellspacing="0" style="background: #ccc">
                                    <tbody>
                                    <tr style="width: 100%;background-color: #fff;">
                                        <td style="padding:10px 10px 10px 10px; text-align:right;background-image: url({{asset('static/backend/images/template-email/header-line.png')}});background-position: top;background-repeat: no-repeat;background-size: 100%;">

                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <table width="700" align="center" cellpadding="0" cellspacing="0" style="background: #ccc">
                                    <!-- Main Wrapper Table with initial width set to 60opx -->
                                    <tbody>
                                    <tr style="width: 100%;">
                                        <!-- Introduction area -->
                                        <td class="table_info" valign="top"
                                            style="background: {{'#'.$background_body}};padding-right: 20px;padding-left: 20px;padding-top: 10px;padding-bottom: 20px;border-right: 1px dashed #ccc;">
                                            <table  width="100%" cellpadding="0" cellspacing="0"
                                                   style="float: left;">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td class="footer-responsive"
                                                        style="font-size: 16px; font-style: normal; font-weight: 100; color: black; line-height: 1.8;text-align:center; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        @if($spa_info->logo!=null)
                                                            <img src="{{asset($spa_info->logo)}}" width="90px" height="70px"><br>
                                                        @else
                                                            <img src="{{asset('static/backend/images/template-email/logo-slider.png')}}"
                                                                 width="90px"
                                                                 height="70px"><br>
                                                        @endif
                                                        <span style="font-weight: bold; color:{{'#'.$color_body}};text-transform: uppercase;">{{$spa_info->name}}</span>
                                                    </td>

                                                </tr>
`                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left"
                                                        style="font-size:14px; font-style: normal; font-weight: 100; color: black; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        <span class="font_res" style="margin-bottom: 10px;font-weight: bold;color:{{'#'.$config_template->color_body}};text-transform: uppercase;">{{$title}}</span><br>
                                                        @if($config_template->image!=null)
                                                            <img src="{{asset($config_template->image)}}" width="100%" height="100px">
                                                        @else

                                                            <img src="{{asset('static/backend/images/template-email/slider2.jpg')}}"
                                                                 width="100%" height="100px">
                                                        @endif
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" class="font_res"
                                                        style="font-size: 14px; font-style: normal; font-weight: 100; color:{{'#'.$config_template->color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        {!! $content !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td align="left" style="font-size: 14px; font-style: normal; font-weight: 100; color: {{'#'.$color_body}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        <table class="border" width="100%" style="margin-bottom: 10px;border-collapse: collapse;border: 1px solid #cccccc;">
                                                            <tbody>
                                                            <tr class="font_res" style="background: #ceedef;text-transform: uppercase;font-size: 14px;height: 40px;font-weight: bold;color:#464646;margin-bottom: 10px;">
                                                                <td style="padding-left:10px;">{{__('Thông tin đơn hàng')}}</td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">Họ &amp; tên:</td>
                                                                            <td class="">Nguyễn Ngọc Sơn</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">Họ &amp; tên:</span>
                                                                                <span class="">Nguyễn Ngọc Sơn</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Số điện thoại')}}:</td>
                                                                            <td class="">0794212390</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Số điện thoại')}}:</span>
                                                                                <span class="">01214212390</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Email')}}:</td>
                                                                            <td class="">b2dontcry@gmail.com</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Email')}}:</span>
                                                                                <span class="">b2dontcry@gmail.com</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Chi nhánh')}}:</td>
                                                                            <td class="">Nam Long quận 7</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Chi nhánh')}}:</span>
                                                                                <span class="">Nam Long quận 7</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Kỹ thuật viên phục vụ')}}:</td>
                                                                            <td class="">Lê Đăng Sinh</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Kỹ thuật viên phục vụ')}}:</span>
                                                                                <span class="">Lê Đăng Sinh</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Thời gian thực hiện')}}:</td>
                                                                            <td class="">13:00 13/04/2019</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Thời gian thực hiện')}}:</span>
                                                                                <span class="">13:00 13/04/2019</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="padding-left:10px;">
                                                                    <table>
                                                                        <tbody><tr class="footer-pc">
                                                                            <td class="size">{{__('Ghi chú')}}:</td>
                                                                            <td class="">Dễ bị dị ứng, cơ địa sẹo lồi.</td>
                                                                        </tr>
                                                                        <tr class="footer-responsive font_res">
                                                                            <td>
                                                                                <span class="size">{{__('Ghi chú')}}:</span>
                                                                                <span class="">Dễ bị dị ứng, cơ địa sẹo lồi.</span>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody></table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                    </td>

                                                </tr>
                                                <tr class="info_res">
                                                    <!-- Introduction area -->
                                                    <td style="background: #fff;">
                                                        <table width="100%" align="left" cellpadding="0" cellspacing="0"
                                                               style="background: #fff;">
                                                            <tr>
                                                                <!-- Row container for Intro/ Description -->
                                                                <td align="left"
                                                                    style="font-size: 13px; font-style: normal; font-weight: 100; color: black; line-height: 1.8; text-align:center; padding:0 20px 0 20px; font-family: sans-serif;background: {{'#'.$background_footer}};">
                                                                    <table class="border-footer" width="100%"
                                                                           style="margin-bottom: 15px;border-collapse: collapse;border-top: 1px dashed #ccc;color: {{'#'.$color_footer}};">
                                                                        <tr class="footer-responsive">
                                                                            <td style="text-align: left;padding-top: 10px;">
                                                                                <span style="font-size:13px;font-weight: bold;">{{__('Địa chỉ')}}:</span>
                                                                                <span style="font-size: 13px;">{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                                                <span style="font-size: 13px;"><strong>Điện thoại:</strong> {{$spa_info->phone}}</span><br>
                                                                                <span style="font-size: 13px;"><strong>{{__('Website')}}:</strong> www.piospa.com</span><br>
                                                                                <span style="font-size:13px;font-weight: bold;">{{__('Thời gian làm việc')}}:</span><br>
                                                                                <span style="font-size: 13px;"> 08h00 - 18h00, Thứ 2 - Thứ 6</span><br>
                                                                                <span style="font-size: 13px;"> 09h00 - 20h00, Thứ 7 - Chủ nhật</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>

                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="table_address" style="background: #fff;" valign="top">
                                            <table class="tb_res" width="100%" cellpadding="0" cellspacing="0"
                                                   style="background: {{'#'.$background_header}};float: left;">
                                                <tr>
                                                    <!-- Row container for Intro/ Description -->
                                                    <td style="font-size: 16px; font-style: normal; font-weight: 100; color: black; line-height: 1.8; text-align:center; padding:10px 20px 0px 20px; font-family: sans-serif;border-bottom: 1px dashed #ccc;">
                                                        @if($spa_info->logo!=null)
                                                            <img src="{{asset($spa_info->logo)}}" width="90px" height="70px"><br>
                                                        @else
                                                            <img src="{{asset('static/backend/images/template-email/logo-slider.png')}}"
                                                                 width="90px"
                                                                 height="70px"><br>
                                                        @endif
                                                        <span class="size_title"
                                                              style="font-weight: bold; color:{{'#'.$color_header}};text-transform: uppercase;">
                                        {{$spa_info->name}}
                                    </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left"
                                                        style="font-size: 13px; font-style: normal; font-weight: 100; color:{{'#'.$color_header}}; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;">
                                                        <span><strong>{{__('Địa chỉ')}}:</strong></span><br>
                                                        <span>{{$spa_info->address.', '.$spa_info->district_type.' '.$spa_info->district_name.', '.$spa_info->province_type.' '.$spa_info->province_name}}</span><br>
                                                        <span>Điện thoại: <strong>{{$spa_info->phone}}</strong></span><br>
                                                        <span>{{__('Website')}}: <strong>piospa.com</strong></span><br>
                                                        <span><strong>{{__('Thời gian làm việc')}}:</strong></span><br>
                                                        <span>Thứ 2: 08h00 - 18h00</span><br>
                                                        <span>Thứ 3: 08h00 - 18h00</span><br>
                                                        <span>Thứ 4: 08h00 - 18h00</span><br>
                                                        <span>Thứ 5: 08h00 - 18h00</span><br>
                                                        <span>Thứ 6: 08h00 - 18h00</span><br>
                                                        <span>Thứ 7: 08h00 - 18h00</span><br>
                                                        <span>Chủ nhật: 08h00 - 18h00</span><br>
                                                        <!-- <span><strong>Kết nối với chúng tôi</strong></span> -->
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
                            <td>
                                <table width="700" align="center" cellpadding="0" cellspacing="0" style="background: #ccc">
                                    <!-- Main Wrapper Table with initial width set to 60opx -->
                                    <tbody>
                                    <tr>
                                        <td style="text-align: center;">
                                            <!-- First column of footer content -->
                                            <p class="font_res" style="font-size: 13px; font-weight:200; line-height: 2.5em; color: #929292; font-family: sans-serif;">
                                                {{__('@ copyright 2019 - Bản quyền thuộc về piospa')}}
                                            </p>

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

                @endif

            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang('HỦY')</span>
						</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>