@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@stop
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        input[type=file] {
            padding: 10px;
            background: #fff;
        }

        .m-widget5 .m-widget5__item .m-widget5__pic > img {
            width: 100%
        }

        .m-image {
            /*padding: 5px;*/
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('THÊM NHÂN VIÊN')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--<div onmouseover="onmouseoverAddNew()" onmouseout="onmouseoutAddNew()"--}}
                {{--class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new"--}}
                {{--m-dropdown-toggle="hover" aria-expanded="true">--}}
                {{--<a href="#"--}}
                {{--class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">--}}
                {{--<i class="la la-plus m--hide"></i>--}}
                {{--<i class="la la-ellipsis-h"></i>--}}
                {{--</a>--}}
                {{--<div class="m-dropdown__wrapper dropdow-add-new" style="z-index: 101;display: none">--}}
                {{--<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"--}}
                {{--style="left: auto; right: 21.5px;"></span>--}}
                {{--<div class="m-dropdown__inner">--}}
                {{--<div class="m-dropdown__body">--}}
                {{--<div class="m-dropdown__content">--}}
                {{--<ul class="m-nav">--}}
                {{--<li class="m-nav__item">--}}
                {{--<a data-toggle="modal"--}}
                {{--data-target="#modalAdd" href="" class="m-nav__link">--}}
                {{--<i class="m-nav__link-icon la la-users"></i>--}}
                {{--<span class="m-nav__link-text">{{__('Thêm chức vụ')}} </span>--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li class="m-nav__item">--}}
                {{--<a data-toggle="modal"--}}
                {{--data-target="#modalAddPartment" href="" class="m-nav__link">--}}
                {{--<i class="m-nav__link-icon la la-users"></i>--}}
                {{--<span class="m-nav__link-text">{{__('Thêm phòng ban')}} </span>--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
        <form id="form-add">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <input type="hidden" id="staff_avatar" name="staff_avatar" value="">
                            <div class="form-group m-widget19">
                                <div class="m-widget19__pic">
                                    <img class="m--bg-metal m-image  img-sd" id="blah"
                                         src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                                         alt="Hình ảnh" width="220px" height="220px">
                                </div>
                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                       data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                       id="getFile" type="file" onchange="uploadImage(this);" class="form-control"
                                       style="display:none">


                                <div class="m-widget19__action" style="max-width: 155px">
                                    <a href="javascript:void(0)" onclick="document.getElementById('getFile').click()"
                                       class="btn btn-sm m-btn--icon color w-100">
                                    <span class="m--margin-left-20">
                                    <i class="fa fa-camera"></i>
                                    <span>
                                    {{__('Tải ảnh lên')}}
                                    </span>
                                    </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="row clearfix">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Họ tên')}}:<b class="text-danger">*</b></label>
                                    <input type="text" name="full_name" class="form-control m-input"
                                           id="full_name"
                                           placeholder="{{__('Hãy nhập họ tên')}}">
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__('Ngày sinh')}}:
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <select class="form-control op_day width-select" title="{{__('Ngày')}}"
                                                    id="day"
                                                    name="day">
                                                <option></option>
                                                @for($i=1;$i<=31;$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-4 m">
                                            <select class="form-control width-select" title="{{__('Tháng')}}" id="month"
                                                    name="month">
                                                <option></option>
                                                @for($i=1;$i<=12;$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-lg-4 y">
                                            <select class="form-control width-select " title="{{__('Năm')}}"
                                                    id="year" name="year">
                                                <option></option>
                                                @for($i=1970;$i<= date("Y");$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <span class="error_birthday" style="color: #ff0000"></span>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Giới tính')}}:</label>
                                    <div class="m-radio-inline">
                                        <label class="m-radio cus">
                                            <input type="radio" name="gender" value="male"> {{__('Nam')}}
                                            <span></span>
                                        </label>
                                        <label class="m-radio cus">
                                            <input type="radio" name="gender" value="female"> {{__('Nữ')}}
                                            <span></span>
                                        </label>
                                        <label class="m-radio cus">
                                            <input type="radio" name="gender" value="other"> {{__('Khác')}}
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Số điện thoại')}}:<b
                                                class="text-danger">*</b></label>
                                    <input type="number" name="phone1" class="form-control m-input" id="phone1"
                                           placeholder="{{__('Hãy nhập số điện thoại')}}"
                                           onkeydown="javascript: return event.keyCode == 69 ? false : true">
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Địa chỉ')}}:<b class="text-danger">*</b></label>
                                    <input type="text" name="address" class="form-control m-input" id="address"
                                           placeholder="{{__('Hãy nhập địa chỉ')}}">
                                    {{--<span class="error-name"></span>--}}
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Email')}}:</label>
                                    <input type="text" name="email" class="form-control m-input" id="email"
                                           placeholder="{{__('Hãy nhập email')}}">
                                    <span class="error_email" style="color: #ff0000"></span>
                                </div>
                                <div class="row">
                                    <div class="form-group m-form__group col-lg-6">
                                        <label class="black-title">{{__('Số tài khoản')}}:</label>
                                        <input type="text" class="form-control m-input" id="bank_number"
                                               name="bank_number"
                                               placeholder="{{__('Hãy nhập số tài khoản')}}">
                                               <span class="error_bank_number" style="color: #ff0000"></span>
                                    </div>
                                    <div class="form-group m-form__group col-lg-6">
                                        <label class="black-title">{{__('Ngân hàng')}}:</label>
                                        <input type="text" class="form-control m-input" id="bank_name" name="bank_name"
                                               placeholder="{{__('Hãy nhập ngân hàng')}}">
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Chi nhánh ngân hàng')}}:</label>
                                    <input type="text" class="form-control m-input" id="bank_branch_name"
                                           name="bank_branch_name"
                                           placeholder="{{__('Hãy nhập chi nhánh ngân hàng')}}">
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Quyền hạn')}}:</label>
                                    <select name="is_admin" class="form-control" id="is_admin">
                                        <option value="0">{{__('Quản lý')}}</option>
                                        @if(Auth::user()->is_admin==1)
                                            <option value="1">Admin</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group m-form__group ">
                                    <label class="black-title">{{__('Nhóm quyền')}}:</label>
                                    <select name="role-group-id" class="form-control js-example-data-ajax"
                                            id="role-group-id" multiple="multiple" style="width: 100%">
                                        @foreach($roleGroup as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Chi nhánh')}}:<b class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select name="branch_id" id="branch_id" class="form-control m-input">
                                            <option></option>
                                            @foreach($branch as $key=>$value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group" {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                    <label class="black-title">{{__('Chức vụ')}}:<b class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select name="staff_title_id" id="staff_title_id"
                                                class="form-control m-input">
                                            <option></option>
                                            @foreach($title as $key=>$value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Phòng ban')}}:<b class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select name="department_id" id="department_id" onchange="staff.changeDepartment()"
                                                class="form-control m-input">
                                            <option value="">{{__('Hãy chọn phòng ban')}}</option>
                                            @foreach($depart as $key=>$value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Nhóm')}}:<b class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select name="team_id" id="team_id"
                                                class="form-control m-input">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Loại hợp đồng nhân viên')}}:<b class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select name="staff_type" id="staff_type"
                                                class="form-control m-input">
                                            <option value="staff">{{__('Chính thức')}}</option>
                                            <option value="probationers">{{__('Thử việc')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Lương cứng')}}:</label>
                                    <div class="input-group">
                                        <input type="text" name="salary" class="form-control m-input" id="salary"
                                               placeholder="{{__('Hãy nhập lương cứng')}}">
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Trợ cấp')}}:</label>
                                    <div class="input-group">
                                        <input type="text" name="subsidize" class="form-control m-input" id="subsidize"
                                               placeholder="{{__('Hãy nhập trợ cấp')}}">
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Tỉ lệ hoa hồng')}}:</label>
                                    <div class="input-group">
                                        <input type="text" name="commission_rate" class="form-control m-input"
                                               id="commission_rate"
                                               placeholder="{{__('Hãy nhập tỉ lệ hoa hồng')}}">
                                    </div>
                                </div>

                                <div class="form-group m-form__group">
                                    <label class="black-title">{{__('Tên tài khoản')}}:<b
                                                class="text-danger">*</b></label>
                                    <input type="text" name="user_name" class="form-control m-input"
                                           id="user_name"
                                           placeholder="{{__('Hãy nhập tên tài khoản')}}">
                                    <span class="error_user" style="color: #ff0000"></span>
                                </div>
                                <div class="form-group m-form__group" {{ $errors->has('password') ? ' has-danger' : '' }}>
                                    <label class="black-title">{{__('Mật khẩu')}}:<b class="text-danger">*</b></label>
                                    <input type="password" name="password" class="form-control m-input"
                                           id="password"
                                           placeholder="{{__('Hãy nhập mật khẩu')}}">
                                    {{--<span class="error-name"></span>--}}
                                </div>
                                <div class="form-group m-form__group" {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}>
                                    <label class="black-title">{{__('Nhập lại mật khẩu')}}:<b class="text-danger">*</b></label>
                                    <input type="password" name="repass" class="form-control m-input"
                                           id="repass" placeholder="{{__('Nhập lại mật khẩu')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.staff')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="submit"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                        <button type="submit"
                                class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 btn_add">
							<span>
							<i class="fa fa-plus-circle"></i>
							<span>{{__('LƯU & TẠO MỚI')}}</span>
							</span>
                        </button>

                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
        $('#bank_number').on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
            event.preventDefault();
            return false;
            }
        });
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/staff/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/staff/dropzone.js?v='.time())}}" type="text/javascript"></script>
@stop
