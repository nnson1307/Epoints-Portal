<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        * {
            font-family: "Times New Roman";
        }

        .h5 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .h5-weight {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 0px;
        }

        .h6 {
            font-size: 12px;
            margin-bottom: 0px;
        }

        .h6-weight {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 0px;
        }

        p strong {
            font-size: 15px;
        }

        body {
            font-size: 15px;
        }

        @media print {
            @page {
                size: landscape;
                margin: 0;
            }

            html, body {
                overflow: hidden;
            }
        }

        .line_dot {
            border: none;
            border-bottom: 1px dotted #000;
            width: 100%;
            text-align: left !important;
        }

        .w-40px {
            width: 15px !important;
        }

        .w-100px {
            width: 70px !important;
        }

        .w-200px {
            width: 150px !important;
        }

        .w-300px {
            width: 200px !important;
        }

        .w-600px {
            width: 300px !important;
        }

        .text-underline {
            text-decoration: underline;
        }

        .border-image {
            width: 100px;
            height: 130px;
            text-align: center;
            border: 1px solid;
            font-size: 13px;
            margin-top: -33px;
        }

        .position-absoblute-text {
            /* position: absolute; */
            bottom: 0;
            background: #fff;
            margin-bottom: 0;
            width: max-content;
            display: inline-block;
            position: relative;
            z-index: 2;
        }

        .text-fix {
            /* padding-left: 30%; */
            margin-bottom: 0 !important;
            width: max-content;
            display: inline-block;
        }

        .block-content-border:after {
            content: '';
            /* width: 100%; */
            border-bottom: 1px dotted;
            width: 100%;
            position: absolute;
            left: 0;
            bottom: 1px;
            z-index: 1;
        }

        html {
            width: 297mm;
            height: 200mm;
        }

        .div_button_print {
            display: none;
        }

        .a4-page {
            width: 297mm;
            height: 210mm;
            max-width: 297mm;
            max-height: 210mm;
            overflow: hidden;
        }

        #print, .print {
            height: 73vh;
        }
    </style>
    <style>
        @media not print {
            html {
                margin: 0 auto;
            }

            .a4-page {
                width: 297mm;
                height: 210mm;
                max-width: 297mm;
                max-height: 210mm;
                border: solid 1px;
                box-sizing: border-box;
                margin-top: 15px;
                overflow: hidden;
            }

            .div_button_print {
                display: block;
            }

        }
    </style>
    <title>In giấy đăng ký NVQS!</title>
</head>
<body style="overflow : hidden;">
<div class="form-group text-right div_button_print">
    <a onclick="window.print();" class="btn btn-primary">
        <i class="la la-calendar-check-o"></i>
        {{__('IN')}}
    </a>
</div>

@if (count($arrayData) > 0)
    @foreach($arrayData as $item)
        <div class="a4-page page-1">
            <div class="container-fluid mt-2">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6 p-3">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <p class="h6">BAN CHỈ HUY QUÂN SỰ QUẬN 10</p>
                                        <p class="h6-weight">BAN CHỈ HUY QUÂN SỰ</p>
                                        <p class="h6-weight text-underline">PHƯỜNG 12</p>
                                        <p style="font-size: 13px;">Số: &nbsp &nbsp /QS</p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <p class="h6-weight">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                                        <p class="h6 text-underline" style="font-weight: bold;">Độc lập - Tự do - Hạnh
                                            phúc</p>
                                        <p style="font-size: 14px; font-style: italic;">Phường 12,ngày &nbsp &nbsp tháng
                                            &nbsp &nbsp năm</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="h5-weight">GIẤY XÁC NHẬN</p>
                                    <p class="h5-weight" style="font-size: 17px !important;">Đăng ký Nghĩa vụ Quân sự
                                        lần đầu</p>
                                    <p class="h5">------------------</p>
                                    <p class="h5-weight" style="font-size: 15px !important;">BAN CHỈ HUY QUÂN SỰ PHƯỜNG
                                        12 QUẬN 10, XÁC NHẬN</p>
                                </div>

                                <div class="col-12 mt-2" style="font-size: 16px;">
                                    <div class="w-100 position-relative mb-0 block-content-border">
                                        <p class="position-absoblute-text mb-0">Công dân:</p>
                                        <div class="text-fix">
                                            <p class="mb-0 font-weight-bold text-uppercase">{{$item['item']['full_name']??''}}</p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-0 block-content-border">
                                        <p class="position-absoblute-text mb-0">Sinh ngày:</p>
                                        <div class="text-fix w-100px">
                                            <p class="mb-0 font-weight-bold">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('d')}}</p>
                                        </div>
                                        <p class="position-absoblute-text mb-0">tháng</p>
                                        <div class="text-fix w-100px">
                                            <p class="mb-0 font-weight-bold">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('m')}}</p>
                                        </div>
                                        <p class="position-absoblute-text mb-0">năm</p>
                                        <div class="text-fix w-100px">
                                            <p class="mb-0 font-weight-bold">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('Y')}}</p>
                                        </div>
                                    </div>

                                    <div class="w-100 position-relative mb-0 block-content-border">
                                        <p class="position-absoblute-text mb-0">Căn cước công dân số:</p>
                                        <div class="text-fix">
                                            <p class="mb-0 font-weight-bold">{{$item['item']['id_number']??''}}</p>
                                        </div>
                                    </div>

                                    <div class="w-100 position-relative mb-0"
                                         style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Nơi thường trú:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">
                                                {{$item['item']['permanent_address']??''}}
                                            </div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>

                                    <div class="w-100 position-relative mb-0"
                                         style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Nơi ở hiện tại:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">
                                                {{$item['item']['temporary_address']??''}}
                                            </div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>

                                    <div>
                                        Đã đăng ký NVQS tại Ủy ban nhân dân Phường 12 và được cấp giấy chứng nhận
                                        NVQS số:................../CN-DK ngày....../....../........
                                    </div>
                                    <div>
                                        Giấy này có giá đến hết ngày &nbsp &nbsp tháng &nbsp &nbsp năm &nbsp &nbsp &nbsp
                                        ./.
                                    </div>

                                    <div class="row">
                                        <div class="col-6 text-center" style="margin-left: 41.333333%;">
                                            <p class="mt-0 mb-0" style="font-style: italic;">Phường 12,ngày &nbsp tháng
                                                &nbsp năm</p>
                                            <p class="mt-0 mb-5"><strong>CHỈ HUY TRƯỞNG</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-3">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <p class="h6">BAN CHỈ HUY QUÂN SỰ QUẬN 10</p>
                                        <p class="h6-weight">BAN CHỈ HUY QUÂN SỰ</p>
                                        <p class="h6-weight text-underline">PHƯỜNG 12</p>
                                        <p style="font-size: 13px;">Số: &nbsp &nbsp /QS</p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <p class="h6-weight">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
                                        <p class="h6 text-underline" style="font-weight: bold;">Độc lập - Tự do - Hạnh
                                            phúc</p>
                                        <p style="font-size: 14px; font-style: italic;">Phường 12,&nbsp ngày &nbsp tháng
                                            &nbsp năm</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="h5-weight">GIẤY XÁC NHẬN</p>
                                    <p class="h5-weight" style="font-size: 17px !important;">Đăng ký Nghĩa vụ Quân sự
                                        lần đầu</p>
                                    <p class="h5">------------------</p>
                                    <p class="h5-weight" style="font-size: 15px !important;">BAN CHỈ HUY QUÂN SỰ PHƯỜNG
                                        12 QUẬN 10, XÁC NHẬN</p>
                                </div>

                                <div class="col-12 mt-2" style="font-size: 16px;">
                                    <div class="w-100 position-relative mb-0 block-content-border">
                                        <p class="position-absoblute-text mb-0">Công dân:</p>
                                        <div class="text-fix">
                                            <p class="mb-0 font-weight-bold text-uppercase">{{$item['item']['full_name']??'Phạm Nguyễn Hưng'}}</p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-0 block-content-border">
                                        <p class="position-absoblute-text mb-0">Sinh ngày:</p>
                                        <div class="text-fix w-100px">
                                            <p class="mb-0 font-weight-bold">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('d')}}</p>
                                        </div>
                                        <p class="position-absoblute-text mb-0">tháng</p>
                                        <div class="text-fix w-100px">
                                            <p class="mb-0 font-weight-bold">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('m')}}</p>
                                        </div>
                                        <p class="position-absoblute-text mb-0">năm</p>
                                        <div class="text-fix w-100px">
                                            <p class="mb-0 font-weight-bold">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('Y')}}</p>
                                        </div>
                                    </div>

                                    <div class="w-100 position-relative mb-0 block-content-border">
                                        <p class="position-absoblute-text mb-0">Căn cước công dân số:</p>
                                        <div class="text-fix">
                                            <p class="mb-0 font-weight-bold">{{$item['item']['id_number']??''}}</p>
                                        </div>
                                    </div>

                                    <div class="w-100 position-relative mb-0"
                                         style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Nơi thường trú:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">
                                                {{$item['item']['permanent_address']??''}}
                                            </div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>

                                    <div class="w-100 position-relative mb-0"
                                         style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Nơi ở hiện tại:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">
                                                {{$item['item']['temporary_address']??''}}
                                            </div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>

                                    <div>
                                        Đã đăng ký NVQS tại Ủy ban nhân dân Phường 12 và được cấp giấy chứng nhận
                                        NVQS số:................../CN-DK ngày....../....../.........
                                    </div>
                                    <div>
                                        Giấy này có giá đến hết ngày &nbsp &nbsp tháng &nbsp &nbsp năm &nbsp &nbsp &nbsp
                                        ./.
                                    </div>

                                    <div class="row">
                                        <div class="col-6 text-center" style="margin-left: 41.333333%;">
                                            <p class="mt-0 mb-0" style="font-style: italic;">Phường 12,ngày &nbsp tháng
                                                &nbsp năm</p>
                                            <p class="mt-0 mb-5"><strong>CHỈ HUY TRƯỞNG</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif





<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

