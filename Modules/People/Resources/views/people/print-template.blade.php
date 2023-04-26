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

        .h5, h5 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
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
                margin: 0
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
            height: 420mm;
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
    <title>In lý lịch!</title>
</head>
<body>
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
                                <div class="w-100 text-center mb-4">
                                    <h5>V. Ý KIẾN CỦA CÁN BỘ THẨM TRA</h5>
                                    <p style="font-size: 14px;">(Về lai lịch, chính trị gia đình, tiêu chuẩn của công
                                        dân đủ điều kiện nhập ngũ hay không)</p>
                                    <p class="mt-3 line_dot"></p>
                                    <p class="mt-3 line_dot"></p>
                                    <p class="mt-3 line_dot"></p>
                                    <div class="row" style="margin-top: -10px;">
                                        <div class="col-7 offset-5">
                                            <p class="d-inline-block mb-0">Ngày </p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="d-inline mb-0"> Tháng </p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="d-inline-block mb-0"> Năm 20</p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="mt-0 mb-5"><strong>CÁN BỘ THẨM TRA</strong></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-100 text-center mb-4">
                                    <h5 class="pt-3">KẾT LUẬN CỦA CƠ QUAN QUÂN SỰ CẤP QUẬN</h5>
                                    <p class="mt-3 line_dot"></p>
                                    <p class="mt-3 line_dot"></p>
                                    <p class="mt-3 line_dot"></p>
                                    <div class="row" style="margin-top: -10px;">
                                        <div class="col-7 offset-5">
                                            <p class="d-inline-block mb-0">Ngày </p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="d-inline mb-0"> Tháng </p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="d-inline-block mb-0"> Năm 20</p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="mt-0 mb-5"><strong>CHỈ HUY TRƯỞNG</strong></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-100 text-center mb-4">
                                    <h5 class="pt-3">*KẾT LUẬN CỦA HỘI ĐỒNG NVQS CẤP QUẬN</h5>
                                    <h5>TRƯỚC KHI CÔNG DÂN NHẬP NGŨ</h5>
                                    <p class="mt-3 line_dot"></p>
                                    <p class="mt-3 line_dot"></p>
                                    <p class="mt-3 line_dot"></p>
                                    <div class="row" style="margin-top: -10px;">
                                        <div class="col-7 offset-5">
                                            <p class="d-inline-block mb-0">Ngày </p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="d-inline mb-0"> Tháng </p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="d-inline-block mb-0"> Năm 20</p>
                                            <p class="line_dot w-40px d-inline-block mb-0"></p>
                                            <p class="mt-0 mb-5">
                                                <strong style="display:inline-block">TM.HỘI ĐỒNG NGHĨA VỤ QUÂN SỰ</strong>
                                                <strong style="display:inline-block">PHÓ CHỦ TỊCH THƯỜNG TRỰC</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-3">
                                <div class="w-100 text-center">
                                    <h5>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</h5>
                                    <p class="text-underline"><strong>Độc lập - Tự do - Hạnh phúc</strong></p>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="border-image">
                                                    <p class="mb-0 pt-3">Ảnh 3x4</p>
                                                    <p class="mb-0">đóng dấu giáp</p>
                                                    <p class="mb-0">lai</p>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="w-100 text-center d-flex align-items-end h-100">
                                                    <div class="w-100">
                                                        <h5>LÝ LỊCH</h5>
                                                        <h5>NGHĨA VỤ QUÂN SỰ</h5>
                                                        <h5>I. SƠ YẾU LÝ LỊCH</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4" style="font-size: 14px;">
                                                <p class="mb-0">Biểu số 08/GNN-2016</p>
                                                <p class="mb-0">Khổ biểu: 29x42</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Họ đệm tên khai sinh (Viết in
                                                hoa):</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold text-uppercase">{{$item['item']['full_name']??'Phạm Nguyễn Hưng'}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Họ đệm tên thường dùng:</p>
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
                                            <p class="position-absoblute-text mb-0">Giới tính (Nam, nữ):
                                                <strong>@switch($item['item']['gender']??'male') @case('male')
                                                    Nam @break @case('female') Nữ @break @default
                                                    Khác @endswitch</strong></p>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Giấy CM hoặc số thẻ căn cước số:</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['id_number']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Nơi đăng ký khai sinh:</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['birthplace']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Quê quán:</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['hometown']??''}}</p>
                                            </div>
                                        </div>

                                        <div class="w-100 position-relative mb-0" style="padding-top: 20px;overflow: hidden;">
                                            <div style="position: absolute;top:0;left: 0;right: 0;">
                                                <p class="position-absoblute-text mb-0">Nơi thường trú của gia đình:</p>
                                                <div style="font-weight:bold;display: contents;position: absolute;">
                                                    {{$item['item']['permanent_address']??''}}
                                                </div>
                                                <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                            </div>
                                            <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        </div>

                                        <div class="w-100 position-relative mb-0" style="padding-top: 20px;overflow: hidden;">
                                            <div style="position: absolute;top:0;left: 0;right: 0;">
                                                <p class="position-absoblute-text mb-0">Nơi ở hiện tại của bản thân:</p>
                                                <div style="font-weight:bold;display: contents;position: absolute;">
                                                    {{$item['item']['temporary_address']??''}}
                                                </div>
                                                <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                            </div>
                                            <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        </div>


                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Thành phần gia đình:</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['people_family_type_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Bản thân:</p>
                                            <div class="text-fix">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Dân tộc</p>
                                            <div class="text-fix w-200px">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['ethnic_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Tôn giáo:</p>
                                            <div class="text-fix w-200px">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['religion_name']??'Không'}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Quốc tịch:</p>
                                            <div class="text-fix">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Trình độ văn hóa</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['educational_level_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Năm tốt nghiệp:</p>
                                            <div class="text-fix">
                                                <p class="mb-0">{{$item['item']['graduation_year']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Chuyên ngành đào tạo</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['specialized']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Ngoại ngữ:</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['foreign_language']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Ngày vào Đảng CSVN:</p>
                                            <div class="text-fix">
                                                <p class="mb-0">
                                                    @if($item['item']['group_join_date']??false)
                                                        <strong>{{ \Carbon\Carbon::parse($item['item']['group_join_date']??'')->format('d/m/Y') }}</strong>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Ngày vào Đoàn TNCS Hồ Chí Minh:</p>
                                            <div class="text-fix">
                                                <p class="mb-0">
                                                    @if($item['item']['union_join_date']??false)
                                                        <strong>{{ \Carbon\Carbon::parse($item['item']['union_join_date']??'')->format('d/m/Y') }}</strong>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Khen thưởng:</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0"></p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Kỷ luật:</p>
                                            <div class="text-fix">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Nghề nghiệp:</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['people_job_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Lương:Ngạch</p>
                                            <div class="text-fix w-100px">
                                                <p class="mb-0"></p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">bậc</p>
                                            <div class="text-fix">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Nơi làm việc, (học tập):</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['item']['workplace']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Họ tên cha:</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold text-uppercase">{{$item['father']['full_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">(Sống,chết)</p>
                                            <div class="text-fix">
                                                <p class="mb-0">
                                                    <strong>@if($item['father']['full_name']??false) @if( $item['father']['is_dead']??false )
                                                            Chết @else Sống @endif @endif</strong></p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Sinh năm:</p>
                                            <div class="text-fix w-200px">
                                                <p class="mb-0 font-weight-bold">{{$item['father']['birth_year']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Nghề nghiệp:</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['father']['people_job_name']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Họ tên mẹ:</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold text-uppercase">{{$item['mother']['full_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">(Sống,chết)</p>
                                            <div class="text-fix">
                                                <p class="mb-0">
                                                    <strong>@if($item['father']['full_name']??false) @if($item['mother']['is_dead']??false)
                                                            Chết @else Sống @endif @endif</strong></p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Sinh năm:</p>
                                            <div class="text-fix w-200px">
                                                <p class="mb-0 font-weight-bold">{{$item['mother']['birth_year']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Nghề nghiệp:</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['mother']['people_job_name']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Họ tên vợ(chồng):</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold text-uppercase">{{$item['partner']['full_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Sinh năm</p>
                                            <div class="text-fix">
                                                <p class="mb-0 font-weight-bold">{{$item['partner']['birth_year']??''}}</p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Nghề nghiệp:</p>
                                            <div class="text-fix w-300px">
                                                <p class="mb-0 font-weight-bold">{{$item['partner']['people_job_name']??''}}</p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">Bản thân đã có</p>
                                            <div class="text-fix w-100px">
                                                <p class="mb-0"></p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">con</p>
                                            <div class="text-fix">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                        <div class="w-100 position-relative mb-0 block-content-border">
                                            <p class="position-absoblute-text mb-0">Cha mẹ có:</p>
                                            <div class="text-fix w-100px">
                                                <p class="mb-0"></p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">người con,</p>
                                            <div class="text-fix w-100px">
                                                <p class="mb-0"></p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">trai</p>
                                            <div class="text-fix w-100px">
                                                <p class="mb-0"></p>
                                            </div>
                                            <p class="position-absoblute-text mb-0">gái;bản thân là con thứ</p>
                                            <div class="text-fix">
                                                <p class="mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="a4-page page-2">
            <div class="container-fluid mt-3">
                <div class="row">
                    <div class="col-12 ">
                        <div class="row">
                            <div class="col-6 p-3">
                                <div class="w-100 text-center mb-2">
                                    <h5>II. TÌNH HÌNH KINH TẾ, CHÍNH TRỊ CỦA GIA ĐÌNH</h5>
                                    <p style="font-size:14px;">(Ghi đầy đủ của cha, mẹ đẻ hoặc người trực tiếp nuôi
                                        dưỡng của bản thân và của vợ hoặc chồng; anh, chị, em ruột; con đẻ, con nuôi
                                        theo quy định của pháp luật; Họ tên, năm sinh, nghề nghiệp, tình hình kinh tế,
                                        chính trị, của từng người qua các thời kỳ đến nay).</p>
                                </div>
                                <div class="w-100">
                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">*Cha:</p>
                                        <div class="text-fix w-600px">
                                            <p class="mb-0 font-weight-bold text-uppercase">{{$item['father']['full_name']??''}}</p>
                                        </div>
                                        <p class="position-absoblute-text mb-0">Sinh năm:</p>
                                        <div class="text-fix">
                                            <p class="mb-0 font-weight-bold">{{$item['father']['birth_year']??''}}</p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-1" style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Trước 30/04/1975:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">{{$item['father']['before_30041975']??''}}</div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>
                                    <div class="w-100 position-relative mb-1" style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Sau 30/04/1975:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">{{$item['father']['after_30041975']??''}}</div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>
                                    <div class="w-100 position-relative mb-1" style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Hiện nay:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">{{$item['father']['current']??''}}</div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>

                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">*Mẹ:</p>
                                        <div class="text-fix w-600px">
                                            <p class="mb-0 font-weight-bold text-uppercase">{{$item['mother']['full_name']??''}}</p>
                                        </div>
                                        <p class="position-absoblute-text mb-0">Sinh năm:</p>
                                        <div class="text-fix">
                                            <p class="mb-0 font-weight-bold">{{$item['mother']['birth_year']??''}}</p>
                                        </div>
                                    </div>

                                    <div class="w-100 position-relative mb-1" style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Trước 30/04/1975:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">{{$item['mother']['before_30041975']??''}}</div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>
                                    <div class="w-100 position-relative mb-1" style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Sau 30/04/1975:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">{{$item['mother']['after_30041975']??''}}</div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>
                                    <div class="w-100 position-relative mb-1" style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">Hiện nay:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">{{$item['mother']['current']??''}}</div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>

                                    <div class="w-100 position-relative mb-1" style="padding-top: 20px;overflow: hidden;">
                                        <div style="position: absolute;top:0;left: 0;right: 0;">
                                            <p class="position-absoblute-text mb-0">* Anh, chị, em ruột:</p>
                                            <div style="font-weight:bold;display: contents;position: absolute;">
                                                @foreach( ($item['member']??[]) as $member)
                                                    Tên: <strong>{{$member['full_name']??''}}</strong> ; Năm sinh:
                                                    <strong>{{$member['birth_year']??''}}</strong> ; Nghề nghiệp:
                                                    <strong>{{$member['people_job_name']??''}}</strong> ; Địa chỉ cư
                                                    trú: <strong>{{$member['address']??''}}</strong>.
                                                @endforeach

                                            </div>
                                            <div style="border-top: dotted 1px black;position: absolute;top: 20px;left: 0;right: 0;"></div>
                                        </div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                        <div style="border-top: dotted 1px black;margin-top: 23px;"></div>
                                    </div>





                                </div>
                            </div>
                            <div class="col-6 p-3">
                                <div class="w-100 text-center mb-2">
                                    <h5>III. TÌNH HÌNH KINH TẾ, CHÍNH TRỊ, QUÁ TRÌNH CÔNG TÁC CỦA BẢN THÂN</h5>
                                    <p>(Nếu thời gian, kết quả học tập, rèn luyện phấn đấu từ nhỏ đến thời điểm nhập
                                        ngũ)</p>
                                </div>
                                <div class="w-100 mb-4">
                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">Từ nhỏ đến 06 tuổi:</p>
                                        <div class="text-fix ">
                                            <p class="mb-0"><strong>Còn nhỏ, sống phụ thuộc gia đình</strong></p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">Từ 06 tuổi đến 11 tuổi:</p>
                                        <div class="text-fix ">
                                            <p class="mb-0"><strong>{{$item['item']['elementary_school']??''}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">Từ 11 tuổi đến 15 tuổi:</p>
                                        <div class="text-fix ">
                                            <p class="mb-0"><strong>{{$item['item']['middle_school']??''}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">Từ 15 tuổi đến 18 tuổi:</p>
                                        <div class="text-fix ">
                                            <p class="mb-0"><strong>{{$item['item']['high_school']??''}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">Từ 18 tuổi đến 21 tuổi:</p>
                                        <div class="text-fix ">
                                            <p class="mb-0"><strong>{{$item['item']['from_18_to_21']??''}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="w-100 position-relative mb-1 block-content-border">
                                        <p class="position-absoblute-text mb-0">Từ 21 tuổi đến nay tuổi:</p>
                                        <div class="text-fix ">
                                            <p class="mb-0"><strong>{{$item['item']['from_21_to_now']??''}}</strong></p>
                                        </div>
                                    </div>
                                    <p class="line_dot d-inline-block mb-0"></p>
                                    <p class="line_dot d-inline-block mb-0"></p>
                                    <p class="line_dot d-inline-block mb-0"></p>

                                    <div class="row">
                                        <div class="col-5 offset-7 text-center">
                                            <p class="mt-0 mb-0"><strong>CHỮ KÝ CỦA CÔNG DÂN</strong></p>
                                            <p class="mt-0 mb-5">(Ký, ghi rõ họ tên)</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-100 text-center mb-2">
                                    <h5>IV. NHẬN XÉT VÀ ĐỀ NGHỊ CỦA CHÍNH QUYỀN CƠ SỞ</h5>
                                    <h5>(HOẶC CƠ QUAN, TỔ CHỨC, DOANH NGHIỆP)</h5>
                                </div>
                                <div class="w-100">
                                    <p class="line_dot d-inline-block mb-0"></p>
                                    <p class="line_dot d-inline-block mb-0"></p>
                                    <p class="line_dot d-inline-block mb-0"></p>
                                    <p class="line_dot d-inline-block mb-0"></p>
                                    <p class="line_dot d-inline-block mb-0"></p>
                                    <p class="line_dot d-inline-block mb-0"></p>
                                </div>
                                <div class="row mt-0">
                                    <div class="col-4 pl-0 pr-0">
                                        <h5 style="padding-top:23px">TRƯỞNG CÔNG AN</h5>
                                    </div>
                                    <div class="col-4 pl-0 pr-0">
                                        <h5 style="padding-top:23px">CHỈ HUY TRƯỞNG QS</h5>
                                    </div>
                                    <div class="col-4 pl-0 pr-0">
                                        <p class="d-inline-block mb-0">Ngày </p>
                                        <p class="line_dot w-40px d-inline-block mb-0"></p>
                                        <p class="d-inline mb-0"> Tháng </p>
                                        <p class="line_dot w-40px d-inline-block mb-0"></p>
                                        <p class="d-inline-block mb-0"> Năm 20</p>
                                        <p class="line_dot w-40px d-inline-block mb-0"></p>
                                        <h5 class="mt-0 mb-5 text-center">
                                            CHỦ TỊCH UBND
                                        </h5>
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

