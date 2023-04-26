@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link href="toggle-radios.css" rel="stylesheet"/>
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ REFERRAL')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .nav-item:hover {
            background-color: #4fc4cb;
            transition: 1s
        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }

        ul.ks-cboxtags {
            list-style: none;
            padding: 20px;
        }

        ul.ks-cboxtags li {
            display: inline;
        }

        ul.ks-cboxtags li label {
            display: inline-block;
            background-color: rgba(255, 255, 255, .9);
            border: 2px solid rgba(139, 139, 139, .3);
            color: #adadad;
            border-radius: 25px;
            white-space: nowrap;
            margin: 3px 0px;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: all .2s;
        }

        ul.ks-cboxtags li label {
            padding: 8px 12px;
            cursor: pointer;
        }

        /*ul.ks-cboxtags li label::before {*/
        /*    display: inline-block;*/
        /*    font-style: normal;*/
        /*    font-variant: normal;*/
        /*    text-rendering: auto;*/
        /*    -webkit-font-smoothing: antialiased;*/
        /*    font-family: "Font Awesome 5 Free";*/
        /*    font-weight: 900;*/
        /*    font-size: 12px;*/
        /*    padding: 2px 6px 2px 2px;*/
        /*    content: "\f067";*/
        /*    transition: transform .3s ease-in-out;*/
        /*}*/

        /*ul.ks-cboxtags li input[type="checkbox"]:checked + label::before {*/
        /*    content: "\f00c";*/
        /*    transform: rotate(-360deg);*/
        /*    transition: transform .3s ease-in-out;*/
        /*}*/

        /*ul.ks-cboxtags li input[type="checkbox"]:checked + label {*/
        /*    border: 2px solid #1bdbf8;*/
        /*    background-color: #12bbd4;*/
        /*    color: #fff;*/
        /*    transition: all .2s;*/
        /*}*/
        ul.ks-cboxtags li input[type="checkbox"]:checked + label {
            border: 2px solid #4fc4cb;
            background-color: #4fc4cb;
            color: #fff;
            transition: all 1s;
        }

        ul.ks-cboxtags li input[type="checkbox"] {
            display: absolute;
        }

        ul.ks-cboxtags li input[type="checkbox"] {
            position: absolute;
            opacity: 0;
        }

        ul.ks-cboxtags li input[type="checkbox"]:focus + label {
            border: 2px solid #00FFFF;
        }

        #week {
            border: 2px solid white;
            box-shadow: 0 0 0 1px #4fc4cb;
            appearance: none;
            border-radius: 50%;
            width: 12px;
            height: 12px;
            background-color: #fff;
            transition: all ease-in 0.2s;
        }

        #week:checked {
            background-color: #4fc4cb;
        }

        #month {
            border: 2px solid white;
            box-shadow: 0 0 0 1px #4fc4cb;
            appearance: none;
            border-radius: 50%;
            width: 12px;
            height: 12px;
            background-color: #fff;
            transition: all ease-in 0.2s;
        }

        #month:checked {
            background-color: #4fc4cb;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('QUẢN LÝ REFERRAL')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            @include('referral::layouts.tab-header')

            <div class="text-right">
                <div class="m-portlet__head-tools" style="    margin-bottom: 50px;">
                    @if(isset($info['final_id']) &&  $info['final_id'] > $info['referral_config_id'] )
                        <a href="{{route('referral.editGeneralConfig')}}"
                           class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"
                           style="float: right;display:none">
                        <span>
						    <i class="la la-edit"></i>
							<span> {{__('CHỈNH SỬA')}}</span>
                        </span>
                        </a>
                    @else
                        <a href="{{route('referral.editGeneralConfig')}}"
                           class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"
                           style="float: right">
                        <span>
						    <i class="la la-edit"></i>
							<span> {{__('CHỈNH SỬA')}}</span>
                        </span>
                        </a>
                    @endif

                    <a href="{{route('referral.historyGeneralConfig')}}"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"
                       style="float: right;    margin-right: 10px">
                        <span>
						    <i class="la la-th-list"></i>
							<span> {{__('LỊCH SỬ CẤU HÌNH')}}</span>
                        </span>
                    </a>
                    &nbsp

                </div>
            </div>
            <div class="container">
                <form id="general-config">
                    <div class="row">
                        <div class="col-sm">
                            <span style="font-size: 15px;font-weight: bold">Nội dung cấu hình: <b
                                        class="text-danger"> *</b></span>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-12">
                                    <div class="form-group m-form__group" style="margin-bottom: 5px">
                                        <div class="input-group">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p>{{$info['config_description']}}</p>
                                                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span style="font-size: 15px;font-weight: bold">Định dạng mã giới thiệu: <b
                                        class="text-danger"> *</b></span>
                            <br>
                            <div style="display: flex;margin-bottom: 5px;">
                                <div style="width: 210px;display: flex">
                                    <span id="config_content" name="fomat_code" type="text"
                                          class="form-control m-input class">
                                            @switch($info['config_code_type'])
                                            @case('phone')
                                            <p>{{__('Số điện thoại')}}</p>
                                            @break
                                            @case('name')
                                            <p>{{__('Tên người giới thiệu')}}</p>
                                            @break
                                            @case('none')
                                            <p>{{__('Không có')}}</p>
                                            @break
                                            @case('custom')
                                            <p>{{__('Tùy chỉnh')}}</p>
                                            @break
                                        @endswitch

                                     </span>
                                    &nbsp
                                    <b style="margin-top: 10px;"> +</b>
                                </div>
                                &nbsp
                                <div class="form-group m-form__group" style="width: 75px">
                                    <div class="input-group">
                                            <span id="config_content" name="character_random" type="text"
                                                  class="form-control m-input class">
                                                    <p>{{$info['config_number_random']}}</p>
                                            </span>
                                    </div>
                                </div>
                                &nbsp
                                <span style="margin-top: 10px;">kí tự bao gồm (chữ và số) random</span>
                            </div>
                            @if(isset($info['config_code']))
                            <div style="width: 197px">
                                    <span id="config_content" name="fomat_code" type="text"
                                          class="form-control m-input class">
                                        <p>{{$info['config_code']}}</p>
                                     </span>
                            </div>
                            @else
                                @endif
                            <span style="font-size: 15px;font-weight: bold">Hệ thống tự động ghi nhận hoa hồng: <b
                                        class="text-danger"> *</b></span>
                            <div class="form-group m-form__group">
                                    <span id="config_content" name="date_auto_confirm" type="text"
                                          class="form-control m-input class">
                                         @switch($info['date_auto_confirm'])
                                            @case('after_1_day')
                                            <p>{{__("Sau 1 ngày thanh toán")}}</p>
                                            @break
                                            @case('after_2_day')
                                            <p>{{__("Sau 2 ngày thanh toán")}}</p>
                                            @break
                                            @case('after_3_day')
                                            <p>{{__("Sau 3 ngày thanh toán")}}</p>
                                            @break
                                            @case('after_7_day')
                                            <p>{{__("Sau 7 ngày thanh toán")}}</p>
                                            @break
                                        @endswitch
                                     </span>
                            </div>
                            <span style="font-size: 15px;font-weight: bold">Hệ thống tự động tạo chu kì thanh toán hoa hồng vào: <b
                                        class="text-danger"> *</b></span>
                            @if($info['payment_cycle_type'] == "week" )
                                <span id="config_content" name="config_content" type="text"
                                      class="form-control m-input class">
                                    @switch($info['payment_cycle_value'])
                                        @case(0)
                                        <p>{{__("Thứ 2 hàng tuần")}}</p>
                                        @break
                                        @case(1)
                                        <p>{{__("Thứ 3 hàng tuần")}}</p>
                                        @break
                                        @case(2)
                                        <p>{{__("Thứ 4 hàng tuần")}}</p>
                                        @break
                                        @case(3)
                                        <p>{{__("Thứ 5 hàng tuần")}}</p>
                                        @break
                                        @case(4)
                                        <p>{{__("Thứ 6 hàng tuần")}}</p>
                                        @break
                                        @case(5)
                                        <p>{{__("Thứ 7 hàng tuần")}}</p>
                                        @break
                                        @case(6)
                                        <p>{{__("Chủ nhật hàng tuần")}}</p>
                                        @break
                                    @endswitch
                                     </span>
                            @elseif($info['payment_cycle_type'] == "month" )
                                <span id="config_content" name="cycle_type" type="text"
                                      class="form-control m-input class">
                                 <p>{{__("Ngày ").$info['payment_cycle_value'].(" hàng tháng")}}</p>
                                     </span>
                            @else
                                <span id="config_content" name="config_content" type="text"
                                      class="form-control m-input class">
                                 <p>{{__(" ")}}</p>
                                     </span>
                            @endif

                        </div>
                        <span class="col-sm" style="background-color:#FFEFD5">
                        <br>
                        <span style="font-size: 15px;font-weight: bold">Giải thích:</span>
                        <li>
                            <span style="font-weight: bold">Định dạng mã giới thiệu:</span>
                            khi một khách hàng đăng kí thông tin thành công sẽ được cấp một mã giới thiệu theo định dạng đã cấu hình. Mã này là mã duy nhất và không cho phép chỉnh sửa.
                        </li>
                        <br>
                        <li>
                            <span style="font-weight: bold">Hệ thống tự động ghi nhận hoa hồng:</span>
                            Hệ thống tự động ghi nhận hoa hồng cho người giới thiệu sau N ngày đơn hàng đã được thanh toán(toàn phần) thành công.
                        </li>
                        <br>
                        <li><span style="font-weight: bold">Hệ thống tự động tạo chu kì thanh toán hoa hồng vào mỗi:</span>
                            <span>
                                <li style="    margin-left: 30px" ;>
                                    <span style="font-weight: bold;text-indent: 10px">Nếu user cấu hình hàng tuần:</span>
                                    Hệ thống sẽ tự động tạo chu kì thanh toán hoa hồng vào mỗi 23:59 phút từ thứ {thứ đã chọn} tuần thứ N-1 đến 23:59 phút thứ {thứ đã chọn} tuần thứ N.
                                </li>
                                <li style="    margin-left: 30px;">
                                    <span style="font-weight: bold;text-indent: 10px">Nếu user cấu hình hàng tháng:</span>
                                    Hệ thống sẽ tự động tạo chu kì thanh toán hoa hồng vào mỗi 23:59 phút vào ngày {ngày đã chọn} của tháng thứ N-1 đến 23:59 phút vào ngày {ngày đã chọn} của tháng thứ N.
                                </li>
                        <p> &nbsp</p>
                            </span>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        @include('admin::product.modal.excel-image')
        @endsection
        @section('after_script')

            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
            <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}"
                    type="text/javascript"></script>
            <script>
                $('.select2').select2();
            </script>
            <script>
                document.getElementById("week").onclick = function () {
                    $(".choice_day_on_month").hide();
                    $(".choice_day_on_week").show();
                }
                document.getElementById("month").onclick = function () {
                    $(".choice_day_on_month").show();
                    $(".choice_day_on_week").hide();
                }

            </script>


@stop
