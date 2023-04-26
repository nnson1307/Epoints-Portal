<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">
        * {
            font-family: "Times New Roman", Times, serif;
        }

        .position-relative-zindex {
            padding: 5px;
            padding-left: 0;
            background: #fff;
        }

        .position-relative-zindex-2 {
            z-index: 2;
        }

        .position-absolute-line {
            z-index: 1;
            width: 100%;
            border-top: 1px dotted black;
        }

        .position-absolute-line-1-update {
            width: 80%;
            right: 0;
        }

        .position-absolute-line-1 {
            top: 5px;
        }

        .position-absolute-line-2 {
            top: 25px;
        }

        .position-absolute-line-3 {
            top: 52px;
        }

        .p-left-5 {
            padding-left: 5px;
        }

        .w-24 {
            width: 24% !important;
        }

        .p-left-70 {
            padding-left: 70px;
        }

        .width-20 {
            width: 20px;
            display: inline-block;
        }

        .line-page {
            border-top: 3px solid rgb(0 0 0 / 42%);
        }

        .mt-60 {
            margin-top: 60px !important;
        }

        .line-header-1, .line-header-2 {
            margin-top: 0;
            margin-bottom: 4px;
            width: 20%;
            border-top: 2px solid rgb(0 0 0 / 42%);
        }

        .line-header-2 {
            width: 50%;
        }

        .mb-150 {
            margin-bottom: 150px;
        }

        .div_button_print {
            display: none;
        }

        @media not print {
            .div_button_print {
                display: block;
            }
        }
    </style>
</head>
<body class="mt-60">

<div class="form-group text-right div_button_print">
    <a onclick="window.print();" class="btn btn-primary">
        <i class="la la-calendar-check-o"></i>
        {{__('IN')}}
    </a>
</div>

@if (count($arrayData) > 0)
    @foreach($arrayData as $item)
        <div class="container-fluid">
            <div class="row">
                <div class="col-10 offset-1">
                    <div class="row">
                        <div class="col-6 text-center">
                            <p class="mb-0">BAN CHỈ HUY QUÂN SỰ QUẬN 10</p>
                            <p class="mb-0"><strong>BAN CHỈ HUY QUÂN SỰ</strong></p>
                            <p class="mb-0"><strong>PHƯỜNG 12</strong></p>
                            <hr class="line-header-1">
                            <p class="mb-2">Số: &nbsp &nbsp /GGT</p>
                        </div>
                        <div class="col-6 text-center">
                            <p class="mb-0"><strong>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</strong></p>
                            <p class="mb-0"><strong>Độc lập - Tự do - Hạnh phúc</strong></p>
                            <hr class="line-header-2">
                            <div class="position-relative" style="font-style: italic;">
                                <p class="mb-0"><span
                                            class="position-relative position-relative-zindex position-relative-zindex-2">Phường 12, ngày </span>
                                    <span class="width-20">.........</span>
                                    <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5">tháng </span>
                                    <span class="width-20">.........</span>
                                    <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5 pr-0">năm 20</span>
                                    <span class="width-20">.........</span>
                            </div>

                            <div class="row">
                                <div class="col-6 offset-6 text-left">
                                    <p class="mb-0 mt-2">Biểu số: 05/GNN - 2016</p>
                                    <p class="mb-2">Khổ biểu: 21x29</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-10 offset-1 text-center mt-4">
                    <p class="mb-2"><strong>GIẤY GIỚI THIỆU</strong></p>
                    <p class="mb-2"><strong>Di chuyển nghĩa vụ quân sự</strong></p>
                </div>
                <div class="col-8 offset-2 mb-4">
                    <div class="position-relative">
                        <p><span class="position-relative position-relative-zindex position-relative-zindex-2">Kính gửi :</span>
                        </p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                        <hr class="position-absolute position-absolute-line position-absolute-line-2">
                    </div>
                </div>

                <div class="col-10 offset-1">
                    <!-- Họ tên  -->
                    <div class="position-relative mb-3">
                        <p class="w-75 mb-2 d-inline-block"><span
                                    class="position-relative position-relative-zindex position-relative-zindex-2">Họ, chữ đệm và tên khai sinh:</span>
                            {{$item['item']['full_name']??''}} </p>
                        <p class="w-24 mb-2 d-inline-block">
                            <span class="position-relative position-relative-zindex position-relative-zindex-2">Sinh ngày</span><span
                                    class="width-20">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('d')}}</span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2">/</span><span
                                    class="width-20">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('m')}}</span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2">/</span><span
                                    class="width-20">{{Carbon\Carbon::parse($item['item']['birthday']??'1995-01-01')->format('Y')}}</span>
                        </p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                    </div>

                    <!-- Nơi thường trú -->
                    <div class="position-relative mb-4">
                        <p class="mb-2"><span class="position-relative position-relative-zindex position-relative-zindex-2">Nơi thường trú:</span>
                            {{$item['item']['permanent_address']??''}}</p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                        <hr class="position-absolute position-absolute-line position-absolute-line-2">
                    </div>

                    <!-- Nơi ở hiện tại -->
                    <div class="position-relative mb-4">
                        <p class="mb-2"><span class="position-relative position-relative-zindex position-relative-zindex-2">Nơi ở hiện tại:</span>
                            {{$item['item']['temporary_address']??''}}</p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                        <hr class="position-absolute position-absolute-line position-absolute-line-2">
                    </div>

                    <!-- Ngày đăng ký -->

                    <div class="position-relative mb-2">
                        <p class="mb-2">
                            <span class="position-relative position-relative-zindex position-relative-zindex-2">Đã đăng ký NVQS tại ỦY BAN NHÂN DÂN PHƯỜNG 12 và được cấp Giấy chứng nhận đăng ký NVQS số: &nbsp &nbsp</span>

                            <span class="position-relative position-relative-zindex position-relative-zindex-2">/CN - ĐK ngày</span>
                            <span class="width-20"></span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2">/</span>
                            <span class="width-20"></span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2">/</span>
                            <span class="width-20"></span>
                        </p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                    </div>

                    <!-- Nay chuyển đến -->
                    <div class="position-relative mb-4">
                        <p class="mb-2"><span class="position-relative position-relative-zindex position-relative-zindex-2">Nay chuyển đến:</span>
                        </p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                        <hr class="position-absolute position-absolute-line position-absolute-line-2">
                    </div>

                    <!-- Lý do -->
                    <div class="position-relative mb-4">
                        <p class="mb-2"><span class="position-relative position-relative-zindex position-relative-zindex-2">Lý do chuyển đến:</span>
                        </p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                        <hr class="position-absolute position-absolute-line position-absolute-line-2">
                    </div>

                    <!-- Giá trị -->
                    <div class="position-relative mb-4">
                        <p class="mb-2">
                            <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-70">Giấy này có giá trị đến ngày</span>
                            <span class="width-20"></span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5">tháng</span>
                            <span class="width-20"></span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5 pr-0 ">năm 20</span>
                            <span class="width-20"></span>
                        </p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                    </div>
                </div>
                <div class="col-10 text-right mb-150">
                    <p class="mb-2"><strong>CHỈ HUY TRƯỞNG</strong></p>
                </div>
                <div class="col-10 offset-1">
                    <hr class="line-page">
                    <p class="text-center mb-1"><strong>XÁC NHẬN CỦA CƠ QUAN, TỔ CHỨC (HOẶC BAN CHQS CẤP XÃ)</strong></p>
                    <p class="text-center mb-1">---o0o---</p>

                    <!-- Cơ quan  -->
                    <div class="position-relative mb-3">
                        <p><span class="position-relative position-relative-zindex position-relative-zindex-2">Cơ quan:</span>
                            Hiển thị thêm text ở đây nó sẽ tự xuống dòng dấu chấm k thay đổi</p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                    </div>

                    <!-- Đã tiếp nhận -->
                    <div class="position-relative mb-4">
                        <p>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2">Đã tiếp nhận giới thiệu di chuyển đăng ký NVQS số</span>

                            <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5">/ngày</span>
                            <span class="width-20"></span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5">/</span>
                            <span class="width-20"></span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5 pr-0 ">/20</span>
                            <span class="width-20"></span>
                            <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5">của công dân </span>
                        </p>
                        <hr class="position-absolute position-absolute-line position-absolute-line-1">
                        <hr class="position-absolute position-absolute-line position-absolute-line-2">
                    </div>
                </div>

                <div class="col-5 offset-6">
                    <div>
                        <div class="position-relative mb-1">
                            <p class="mb-2">
                                TPHCM<span
                                        class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5">,Ngày</span>
                                <span class="width-20">.........</span>
                                <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5">tháng</span>
                                <span class="width-20">.........</span>
                                <span class="position-relative position-relative-zindex position-relative-zindex-2 p-left-5 pr-0">năm 20</span>
                                <span class="width-20">.........</span>
                            </p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="m-2"><strong>Ký nhận</strong></p>
                        <p class="mb-2"><i>(Ký tên, ghi rõ họ tên, đóng dấu)</i></p>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
@endif
</body>
</html>