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

        ul.ks-cboxtags li input[type="radio"]:checked + label {
            border: 2px solid #4fc4cb;
            background-color: #4fc4cb;
            color: #fff;
            transition: all 1s;
        }

        ul.ks-cboxtags li input[type="radio"] {
            display: absolute;
        }

        ul.ks-cboxtags li input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        ul.ks-cboxtags li input[type="radio"]:focus + label {
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
            <div class="m-portlet__head-tools" style="    margin-bottom: 50px;">
                <button type="button" onclick="create.saveGeneralConfig()"
                        class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"
                        style="float: right;    margin-left: 10px">
                        <span>
						    <i class="la la-check"></i>
							<span> {{__('LƯU')}}</span>
                        </span>
                </button>
                <a href="{{route('referral.generalConfig')}}"
                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"
                   style="float: right; background-color: silver">
                        <span>
						    <i class="la la-arrow-left"></i>
							<span> {{__('HỦY')}}</span>
                        </span>
                </a>
            </div>
            <div class="container">
                <form id="general-config">
                    <div class="row">
                        <form class="edit-general-config">
                            <div class="col-sm">
                                <span style="font-size: 15px;font-weight: bold">Nội dung cấu hình: <b
                                            class="text-danger"> *</b></span>
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-12">
                                        <div class="form-group m-form__group" style="margin-bottom: 5px">
                                            <div class="input-group">
                                                <input id="config_content" name="config_description" type="text"
                                                       class="form-control m-input class"
                                                       placeholder='Nhập nội dung cấu hình'
                                                       value="{{$info['config_description']}}"
                                                       aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span style="font-size: 15px;font-weight: bold">Định dạng mã giới thiệu: <b
                                            class="text-danger"> *</b></span>
                                <br>
                                <div style="display: flex;margin-bottom: 5px;">
                                    <div class="col-lg-3" style="flex: 0 0 40%;max-width: 40%;padding: 0">
                                        <select class="form-control select2 referral-code-1" id="config_code_type1"
                                                name="config_code_type1"
                                                onchange="change.referralCode(this)"
                                                style="width:200px">
                                            <option value="none"{{isset($info['config_code_type']) && $info['config_code_type'] == 'none' ? 'selected' : ''}}>
                                                Không có
                                            </option>
                                            <option value="phone" {{isset($info['config_code_type']) && $info['config_code_type'] == 'phone' ? 'selected' : ''}}>
                                                Số điện thoại
                                            </option>
                                            <option value="name" {{isset($info['config_code_type']) && $info['config_code_type'] == 'name' ? 'selected' : ''}}>
                                                Tên người giới thiệu
                                            </option>
                                            <option value="custom" {{isset($info['config_code_type']) && $info['config_code_type'] == 'custom' ? 'selected' : ''}}>
                                                Tùy chỉnh
                                            </option>
                                        </select>

                                        <div class="input-group referral-code-2" style="display:{{isset($info['config_code_type']) && $info['config_code_type'] == 'custom' ? 'block' : 'none'}};width:92.5%">
                                            <input id="config_content" name="config_code_type_custom" type="text"
                                                   class="form-control m-input class w-100"
{{--                                                   placeholder="{{__("Nhập định dạng")}}"--}}
                                                   value="{{isset($info['config_code']) ? $info['config_code'] : 'Nhập định dạng'}}"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    <b style="margin-top: 10px;"> + </b>
                                    <div class="form-group m-form__group pl-3" style="width: 100px">
                                        <div class="input-group">
                                            <input id="product-name" id="config_number_random"
                                                   name="config_number_random"
                                                   type="text"
                                                   class="form-control m-input class"
                                                   placeholder=""
                                                   value="{{$info['config_number_random']}}"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    &nbsp
                                    <span style="margin-top: 10px;">kí tự bao gồm (chữ và số) random</span>
                                </div>
                                <span style="font-size: 15px;font-weight: bold">Hệ thống tự động ghi nhận hoa hồng: <b
                                            class="text-danger"> *</b></span>
                                <div class="form-group m-form__group">
                                    <select class="form-control select2" id="date_auto_confirm"
                                            name="date_auto_confirm">
                                        <option value="none">Không có</option>
                                        <option value="after_1_day" {{isset($info['date_auto_confirm']) && $info['date_auto_confirm'] == 'after_1_day' ? 'selected' : ''}}>
                                            Sau 1 ngày thanh toán
                                        </option>
                                        <option value="after_2_day" {{isset($info['date_auto_confirm']) && $info['date_auto_confirm'] == 'after_2_day' ? 'selected' : ''}}>
                                            Sau 2 ngày thanh toán
                                        </option>
                                        <option value="after_3_day" {{isset($info['date_auto_confirm']) && $info['date_auto_confirm'] == 'after_3_day' ? 'selected' : ''}}>
                                            Sau 3 ngày thanh toán
                                        </option>
                                        <option value="after_7_day" {{isset($info['date_auto_confirm']) && $info['date_auto_confirm'] == 'after_7_day' ? 'selected' : ''}}>
                                            Sau 7 ngày thanh toán
                                        </option>
                                    </select>

                                </div>
                                <span style="font-size: 15px;font-weight: bold">Hệ thống tự động tạo chu kì thanh toán hoa hồng vào: <b
                                            class="text-danger"> *</b></span>

                                <form action="/action_page.php">
                                    <br>
                                    @if($info['payment_cycle_type'] == 'week' )
                                        <div style=" margin-top:10px">
                                            <input type="radio" id="week" name="payment_cycle_type"
                                                   value="week" {{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'week' ? 'checked' : ''}}>
                                              <label for="week">Hàng tuần</label><br>
                                            <div class="choice_day_on_week"
                                                 style="{{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'week' ? '' : 'display:none'}}">
                                                <ul class="ks-cboxtags" style="padding:0px">
                                                    @for($i = 2 ; $i<=8 ; $i++)
                                                        <li><input type="radio" id="radio.{{$i}}"
                                                                   name="payment_cycle_value"
                                                                   value="{{$i -2}}"
                                                                    {{isset($info['payment_cycle_value']) && $info['payment_cycle_value'] == ($i -2) ? 'checked' : ''}}>

                                                                <label for="radio.{{$i}}">{{$i < 8 ? 'T'.$i : 'CN'}}</label>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="radio" id="month" name="payment_cycle_type"
                                                   value="month" {{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'month' ? 'checked' : ''}}>
                                              <label for="month">Hàng tháng</label><br>
                                            <div class="choice_day_on_month"
                                                 style="{{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'month' ? '' : 'display:none'}}">
                                                <ul class="ks-cboxtags" style="padding:0px">
                                                    @for($i = 1 ; $i<=31 ; $i++)
                                                        <li><input type="radio" id="radio{{$i}}_-m"
                                                                   name="payment_cycle_value"
                                                                   value="{{$i}}">
                                                            <label for="radio{{$i}}_-m">{{$i < 10 ? '0'.$i : $i}}</label>
                                                        </li>
                                                    @endfor

                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div style=" margin-top:10px">
                                            <input type="radio" id="week" name="payment_cycle_type"
                                                   value="week" {{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'week' ? 'checked' : ''}}>
                                              <label for="week">Hàng tuần</label><br>
                                            <div class="choice_day_on_week"
                                                 style="{{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'week' ? '' : 'display:none'}}">
                                                <ul class="ks-cboxtags" style="padding:0px">
                                                    @for($i = 2 ; $i<=8 ; $i++)
                                                        <li><input type="radio" id="radio.{{$i}}"
                                                                   name="payment_cycle_value"
                                                                   value="{{$i -2}}">
                                                            <label for="radio.{{$i}}">{{$i < 8 ? 'T'.$i : 'CN'}}</label>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="radio" id="month" name="payment_cycle_type"
                                                   value="month" {{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'month' ? 'checked' : ''}}>
                                              <label for="month">Hàng tháng</label><br>
                                            <div class="choice_day_on_month"
                                                 style="{{isset($info['payment_cycle_type']) && $info['payment_cycle_type'] == 'month' ? '' : 'display:none'}}">
                                                <ul class="ks-cboxtags" style="padding:0px">
                                                    @for($i = 1 ; $i<=31 ; $i++)
                                                        <li><input type="radio" id="radio{{$i}}_-m"
                                                                   name="payment_cycle_value"
                                                                   value="{{$i}}"
                                                                    {{isset($info['payment_cycle_value']) && $info['payment_cycle_value'] == ($i) ? 'checked' : ''}}>
                                                            <label for="radio{{$i}}_-m">{{$i < 10 ? '0'.$i : $i}}</label>
                                                        </li>
                                                    @endfor

                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </form>
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
            <script>
                var create = {
                    saveGeneralConfig: function () {
                        $.ajax({
                            url: laroute.route("referral.saveGeneralConfig"),
                            method: "POST",
                            data: $("#general-config").serialize(),
                            success: function (res) {
                                if (res.error == false) {
                                    swal(res.message, "Nhấn OK để tiếp tục!", "success").then(function () {
                                        window.location.href = laroute.route("referral.generalConfig")
                                    });
                                } else {
                                    swal("Lưu cấu hình thất bại!", res.message, "error").then(function () {

                                    });
                                }
                            }
                        })
                    }
                }
                var change = {
                    referralCode: function (obj) {
                        if ($(obj).val() == "custom") {
                            // $(".referral-code-1").hide();
                            $('.referral-code-2').show();

                        } else {
                            $('.referral-code-2').hide();
                            // $(".referral-code-1").show();

                        }
                    },

                }
            </script>
@stop
