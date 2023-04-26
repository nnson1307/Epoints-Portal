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

        .m-image {
            /*padding: 5px;*/
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show > .nav-link {
            color: #fff;
            background-color: #0067AC;
        }

        .btn-primary, .btn-primary:hover {
            color: #fff;
            background-color: #0067AC !important;
            border-color: #0067AC !important;
        }

        .btn.btn-default, .btn.btn-secondary.active {
            color: #fff !important;
            background: #0067AC !important;
            border-color: #0067AC !important;
        }

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA NHÂN VIÊN')}}
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
        <div class="m-portlet">
            <div class="m-portlet__body">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#m_tabs_3_1">
                            @lang('Thông tin nhân viên')
                        </a>
                    </li>

                    @if(in_array('salary-setting',session('routeList')))
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#m_tabs_3_2">
                                @lang('Thiết lập lương')
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="m_tabs_3_1" role="tabpanel">
                        <form id="form-edit">
                            <input type="hidden" id="staff_salary_config_id"
                                   value="{{ $staffSalaryConfig['staff_salary_config_id'] ?? $staff_salary_config_id ?? '' }}">
                            <input type="hidden" id="staff_id" name="staff_id" value="{{$item['staff_id']}}">
                            <div class="m-portlet__body">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="form-group m-form__group">
                                            <input type="hidden" id="staff_avatar" name="staff_avatar"
                                                   value="{{$item['staff_avatar']}}">
                                            <input type="hidden" id="staff_avatar_upload" name="staff_avatar_upload"
                                                   value="">
                                            <div class="form-group m-widget19">
                                                <div class="m-widget19__pic">
                                                    @if($item['staff_avatar']!=null)
                                                        <img class="m--bg-metal m-image img-sd" id="blah"
                                                             src="{{$item['staff_avatar']}}"
                                                             alt="Hình ảnh" width="220px" height="220px">
                                                    @else
                                                        <img class="m--bg-metal m-image img-sd" id="blah"
                                                             src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"
                                                             alt="Hình ảnh" width="220px" height="220px">
                                                    @endif
                                                </div>
                                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                                       data-msg-accept="Hình ảnh không đúng định dạng"
                                                       id="getFile" type="file" onchange="uploadImage(this);"
                                                       class="form-control"
                                                       style="display:none">

                                                ˚
                                                <div class="m-widget19__action" style="max-width: 155px">
                                                    <a href="javascript:void(0)"
                                                       onclick="document.getElementById('getFile').click()"
                                                       class="btn  btn-sm m-btn--icon color w-100">
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
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Họ tên')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <input type="text" name="full_name" class="form-control m-input"
                                                           id="full_name"
                                                           placeholder="{{__('Hãy nhập họ tên')}}"
                                                           value="{{$item['full_name']}}">
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">
                                                        {{__('Ngày sinh')}}:
                                                    </label>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <select class="form-control op_day width-select"
                                                                    title="{{__('Ngày')}}" id="day"
                                                                    name="day">
                                                                <option></option>
                                                                @for($i=1;$i<=31;$i++)
                                                                    {{--<option value="{{$i}}">{{$i}}</option>--}}
                                                                    @if($day==$i)
                                                                        <option value="{{$i}}" selected>{{$i}}</option>
                                                                    @else
                                                                        <option value="{{$i}}">{{$i}}</option>
                                                                    @endif
                                                                @endfor

                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 m">
                                                            <select class="form-control width-select"
                                                                    title="{{__('Tháng')}}"
                                                                    style="text-align-last:center;" id="month"
                                                                    name="month">
                                                                <option></option>
                                                                @for($i=1;$i<=12;$i++)

                                                                    @if($month==$i)
                                                                        <option value="{{$i}}" selected>{{$i}}</option>
                                                                    @else
                                                                        <option value="{{$i}}">{{$i}}</option>
                                                                    @endif
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 y">
                                                            <select class="form-control width-select"
                                                                    title="{{__('Năm')}}"
                                                                    id="year" name="year">
                                                                <option></option>
                                                                @for($i=1970;$i<= date("Y");$i++)

                                                                    @if($year==$i)
                                                                        <option value="{{$i}}" selected>{{$i}}</option>
                                                                    @else
                                                                        <option value="{{$i}}">{{$i}}</option>
                                                                    @endif
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
                                                            <input type="radio" checked name="gender" value="male"
                                                                    {{$item['gender']=='male'?'checked':''}}> {{__('Nam')}}
                                                            <span></span>
                                                        </label>
                                                        <label class="m-radio cus">
                                                            <input type="radio" name="gender" value="female"
                                                                    {{$item['gender']=='female'?'checked':''}}> {{__('Nữ')}}
                                                            <span></span>
                                                        </label>
                                                        <label class="m-radio cus">
                                                            <input type="radio" name="gender" value="other"
                                                                    {{$item['gender']=='other'?'checked':''}}> {{__('Khác')}}
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group ">
                                                    <label class="black-title">{{__('Số điện thoại')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <input type="number" name="phone1" class="form-control m-input"
                                                           id="phone1"
                                                           placeholder="{{__('Hãy nhập số điện thoại')}}"
                                                           onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                                           value="{{$item['phone1']}}">


                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Địa chỉ')}}:<b class="text-danger">*</b></label>
                                                    <input type="text" name="address" class="form-control m-input"
                                                           id="address"
                                                           placeholder="{{__('Hãy nhập địa chỉ')}}"
                                                           value="{{$item['address']}}">
                                                    {{--<span class="error-name"></span>--}}
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Email')}}:</label>
                                                    <input type="text" name="email" class="form-control m-input"
                                                           id="email"
                                                           placeholder="{{__('Hãy nhập email')}}"
                                                           value="{{$item['email']}}">
                                                    <span class="error_email" style="color: #ff0000"></span>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group m-form__group col-lg-6">
                                                        <label class="black-title">{{__('Số tài khoản')}}:</label>
                                                        <input type="text" class="form-control m-input" id="bank_number"
                                                               name="bank_number" value="{{$item['bank_number']}}"
                                                               placeholder="{{__('Hãy nhập số tài khoản')}}">
                                                        <span class="error_bank_number" style="color: #ff0000"></span>
                                                    </div>
                                                    <div class="form-group m-form__group col-lg-6">
                                                        <label class="black-title">{{__('Ngân hàng')}}:</label>
                                                        <input type="text" class="form-control m-input" id="bank_name"
                                                               name="bank_name" value="{{$item['bank_name']}}"
                                                               placeholder="{{__('Hãy nhập ngân hàng')}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Chi nhánh ngân hàng')}}:</label>
                                                    <input type="text" class="form-control m-input"
                                                           id="bank_branch_name"
                                                           name="bank_branch_name" value="{{$item['bank_branch_name']}}"
                                                           placeholder="{{__('Hãy nhập chi nhánh ngân hàng')}}">
                                                </div>
                                                <div class="form-group m-form__group ">
                                                    <label class="black-title">{{__('Quyền hạn')}}:</label>
                                                    <select name="is_admin" class="form-control" id="is_admin">
                                                        @if($item['is_admin']==0)
                                                            <option value="0" selected>{{__('Quản lý')}}</option>
                                                            @if(Auth::user()->is_admin==1)
                                                                <option value="1">{{__('Admin')}}</option>
                                                            @endif
                                                        @else
                                                            <option value="0">{{__('Quản lý')}}</option>
                                                            @if(Auth::user()->is_admin==1)
                                                                <option value="1" selected>{{__('Admin')}}</option>
                                                            @endif
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group m-form__group ">
                                                    <label class="black-title">{{__('Nhóm quyền')}}:</label>
                                                    <select name="role-group-id"
                                                            class="form-control js-example-data-ajax"
                                                            id="role-group-id"
                                                            multiple="multiple" style="width: 100%">
                                                        @foreach($roleGroup as $key=>$value)
                                                            @if(in_array($key,$arrayMapRoleGroupStaff))
                                                                <option selected value="{{$key}}">{{$value}}</option>
                                                            @else
                                                                <option value="{{$key}}">{{$value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Chi nhánh')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <div class="input-group">
                                                        <select name="branch_id" id="branch_id"
                                                                class="form-control m-input">
                                                            <option></option>
                                                            @foreach($branch as $key=>$value)
                                                                @if($item['branch_id']==$key)
                                                                    <option value="{{$key}}"
                                                                            selected>{{$value}}</option>
                                                                @else
                                                                    <option value="{{$key}}">{{$value}}</option>
                                                                @endif
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
                                                                @if($item['staff_title_id']==$key)
                                                                    <option value="{{$key}}"
                                                                            selected>{{$value}}</option>
                                                                @else
                                                                    <option value="{{$key}}">{{$value}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Phòng ban')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <div class="input-group">
                                                        <select name="department_id" id="department_id"
                                                                onchange="staff.changeDepartment()"
                                                                class="form-control m-input">
                                                            <option value="">{{__('Hãy chọn phòng ban')}}</option>
                                                            @foreach($depart as $key=>$value)

                                                                @if($item['department_id']==$key)
                                                                    <option value="{{$key}}"
                                                                            selected>{{$value}}</option>
                                                                @else
                                                                    <option value="{{$key}}">{{$value}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Nhóm')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <div class="input-group">
                                                        <select name="team_id" id="team_id"
                                                                class="form-control m-input">
                                                            <option></option>
                                                            @foreach($optionTeam as $v)
                                                                <option value="{{$v['team_id']}}" {{$item['team_id'] == $v['team_id'] ? 'selected': ''}}>{{$v['team_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Loại hợp đồng nhân viên')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <div class="input-group">
                                                        <select name="staff_type" id="staff_type"
                                                                class="form-control m-input">
                                                            <option value="staff" {{$item['staff_type'] == 'staff' ? 'selected' : ''}}>{{__('Chính thức')}}</option>
                                                            <option value="probationers" {{$item['staff_type'] == 'probationers' ? 'selected' : ''}}>{{__('Thử việc')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Lương cứng')}}:</label>
                                                    <div class="input-group">
                                                        <input type="text" name="salary" class="form-control m-input"
                                                               id="salary"
                                                               placeholder="{{__('Hãy nhập lương cứng')}}"
                                                               value="{{number_format($item['salary'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Trợ cấp')}}:</label>
                                                    <div class="input-group">
                                                        <input type="text" name="subsidize" class="form-control m-input"
                                                               id="subsidize"
                                                               placeholder="{{__('Hãy nhập trợ cấp')}}"
                                                               value="{{number_format($item['subsidize'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Tỉ lệ hoa hồng')}}:</label>
                                                    <div class="input-group">
                                                        <input type="text" name="commission_rate"
                                                               class="form-control m-input" id="commission_rate"
                                                               placeholder="{{__('Hãy nhập tỉ lệ hoa hồng')}}"
                                                               value="{{number_format($item['commission_rate'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                    </div>
                                                </div>

                                                <div class="form-group m-form__group ">
                                                    <label class="black-title">{{__('Tên tài khoản')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <input type="text" name="user_name" class="form-control m-input"
                                                           id="user_name"
                                                           placeholder="{{__('Hãy nhập tên tài khoản')}}"
                                                           value="{{$item['user_name']}}" disabled>
                                                    <span class="error_user" style="color: #ff0000"></span>

                                                </div>
                                                <div class="form-group m-form__group" {{ $errors->has('password') ? ' has-danger' : '' }}>
                                                    <label class="black-title">{{__('Mật khẩu mới')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <input type="password" name="password" class="form-control m-input"
                                                           id="password"
                                                           placeholder="{{__('Hãy nhập mật khẩu')}}">
                                                    {{--<span class="error-name"></span>--}}
                                                </div>
                                                <div class="form-group m-form__group" {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}>
                                                    <label class="black-title">{{__('Nhập lại mật khẩu')}}:<b
                                                                class="text-danger">*</b></label>
                                                    <input type="password" name="repass" class="form-control m-input"
                                                           id="repass" placeholder="{{__('Nhập lại mật khẩu')}}">
                                                </div>
                                                <div class="form-group m-form__group">
                                                    <label class="black-title">{{__('Trạng thái')}}:</label>
                                                    {{--<div class="input-group">--}}
                                                    {{--<label class="m-checkbox">--}}
                                                    {{--@if($item['is_inactive']==1)--}}
                                                    {{--<input type="checkbox" checked name="is_inactive" id="is_inactive"> Hoạt--}}
                                                    {{--động--}}
                                                    {{--<span></span>--}}
                                                    {{--@else--}}
                                                    {{--<input type="checkbox" name="is_inactive" id="is_inactive"> Hoạt động--}}
                                                    {{--<span></span>--}}
                                                    {{--@endif--}}

                                                    {{--</label>--}}
                                                    {{--</div>--}}
                                                    <div class="row">
                                                        <div class="col-lg-1">
                                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label>
                                                                        <input id="is_actived" name="is_actived"
                                                                               type="checkbox"
                                                                                {{$item['is_actived']==1?'checked':''}}>
                                                                        <span></span>
                                                                    </label>
                                                                </span>
                                                        </div>
                                                        <div class="col-lg-6 m--margin-top-5">
                                                            <i>{{__('Chọn để kích hoạt trạng thái')}}</i>
                                                        </div>
                                                    </div>

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
                                        <button type="button"
                                                class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_edit m--margin-left-10">
                                            <span>
                                            <i class="la la-edit"></i>
                                            <span>{{__('CẬP NHẬT')}}</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane tab-thiet-lap-luong" id="m_tabs_3_2" role="tabpanel">

                        <form id="form-salary">


                            <div class="m-portlet__body">
                                <div class="row padding_row border">

                                    <div class="col-md-4 staff_salary_template_id_input">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Mẫu áp dụng')</b>
                                            </label>
                                            <select class="form-control m-input width-select"
                                                    name="staff_salary_template_id"
                                                    id="staff_salary_template_id" style="width: calc(100% - 30px);"
                                                    onchange="salaryTempalte.changeStaffSalaryTemplate(this)">
                                                <option value="">@lang('Chọn mẫu áp dụng')</option>
                                                @if(isset($optionStaffSalaryTemplate))
                                                    @foreach($optionStaffSalaryTemplate as $key => $item)
                                                        @if($item['staff_salary_template_id']== ($staffSalaryConfig['staff_salary_template_id']??0) )
                                                            <option value="{{ $item['staff_salary_template_id'] }}"
                                                                    selected="selected">
                                                                {{ __($item['staff_salary_template_name']) }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $item['staff_salary_template_id'] }}">
                                                                {{ __($item['staff_salary_template_name']) }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </select>

                                            <div style="position: absolute;top: 45px;right: 15px;color: #0067AC;cursor: pointer;">
                                                {{-- <a href="javascript:void(0)" onclick="view.showModalAddTemplate()">
                                                   
                                                </a> --}}
                                                <a href="javascript:void(0)" onclick="view.showModalAddTemplate()"
                                                   style="color: #0067AC;">
                                                    <span>
                                                        <i class="fas fa-plus" style="font-size:20px;"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                        <span class="staff_salary_template_id"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Loại lương')</b><b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control m-input width-select" name="staff_salary_type"
                                                    id="staff_salary_type" style="width : 100%;"
                                                    onchange="salaryTempalte.changeStaffSalaryType(this)">
                                                <option value="">@lang('Chọn loại lương')</option>
                                                @if(isset($staffSalaryType))
                                                    @foreach($staffSalaryType as $key => $item)
                                                        @if(isset($staffSalaryConfig['staff_salary_type_code']) && $staffSalaryConfig['staff_salary_type_code']== $item['staff_salary_type_code'])
                                                            <option value="{{ $item['staff_salary_type_code'] }}"
                                                                    selected="selected">
                                                                {{ __($item['staff_salary_type_name']) }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $item['staff_salary_type_code'] }}">
                                                                {{ __($item['staff_salary_type_name']) }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <span class="error-staff-salary-type"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Kỳ hạn trả lương')</b><b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control m-input width-select" name="salary_pay_period"
                                                    id="salary_pay_period" style="width : 100%;">
                                                <option value=""
                                                        selected="selected">@lang('Chọn kỳ hạn trả lương')</option>
                                                @if(isset($staffSalaryPayPeriod))
                                                    @foreach($staffSalaryPayPeriod as $key => $item)
                                                        @if(isset($staffSalaryConfig['staff_salary_pay_period_code']) && $staffSalaryConfig['staff_salary_pay_period_code'] == $item['staff_salary_pay_period_code'])
                                                            <option value="{{ $item['staff_salary_pay_period_code'] }}"
                                                                    selected="selected">
                                                                {{ __($item['staff_salary_pay_period_name']) }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $item['staff_salary_pay_period_code'] }}">
                                                                {{ __($item['staff_salary_pay_period_name']) }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <span class="error-staff-salary-pay-period"></span>
                                    </div>

                                    <div class="col-md-4" id="payPeriod" style="display:none;">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Chọn kỳ hạn trả lương')</b><b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control m-input width-select" name="pay_period"
                                                    id="pay_period" style="width : 100%;">
                                                <option value="" selected="selected">Chọn kỳ hạn trả lương</option>
                                                <option value="monday">@lang('Thứ hai')</option>
                                                <option value="tuesday">@lang('Thứ ba')</option>
                                                <option value="wednesday">@lang('Thứ tư')</option>
                                                <option value="thursday">@lang('Thứ năm')</option>
                                                <option value="friday">@lang('Thứ sáu')</option>
                                                <option value="saturday">@lang('Thứ bảy')</option>
                                                <option value="sunday">@lang('Chủ nhật')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4" id="payPeriodDate" style="display:none;">
                                        <div class="form-group m-form__group">
                                            <label>
                                                @lang('Ngày kết lương'):<b class="text-danger">*</b>
                                            </label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control m-input" readonly=""
                                                       placeholder="Select date" id="pay_period_date">
                                                <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </span>
                                                </div>
                                            </div>
                                            <span class="error-staff-holiday-start-date"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4" style="display: none">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Đơn vị tiền tệ')</b><b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control m-input width-select staff_salary_unit_code"
                                                    name="staff_salary_unit_code"
                                                    id="staff_salary_unit_code" style="width : 100%;"
                                                    onchange="salaryTempalte.chooseUnitAndType()">
                                                @if( isset($optionUnit) && $optionUnit )
                                                    @foreach($optionUnit as $v)
                                                        <option value="{{$v['staff_salary_unit_code']}}"
                                                                {{ ($staffSalaryConfig['staff_salary_unit_code']??'') == $v['staff_salary_unit_code'] ? 'selected': ''}}
                                                        >{{$v['staff_salary_unit_name']}}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </div>
                                        <span class="error-staff_salary_unit_code"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Hình thức trả lương')</b><b class="text-danger">*</b>
                                            </label>
                                            <select class="form-control m-input width-select payment_type"
                                                    name="payment_type"
                                                    id="payment_type" style="width : 100%;">
                                                <option value="">@lang('Chọn hình thức trả lương')</option>
                                                <option value="cash" {{($staffSalaryConfig['payment_type']??'') == 'cash' ? 'selected': ''}}>@lang('Tiền mặt')</option>
                                                <option value="transfer" {{($staffSalaryConfig['payment_type']??'') == 'transfer' ? 'selected': ''}}>@lang('Chuyển khoản')</option>
                                            </select>
                                        </div>
                                        <span class="error-payment_type"></span>
                                    </div>

                                    <div class="col-md-12" id="tblSalaryType">
                                        @if(isset($staffSalaryConfig))
                                            @if($staffSalaryConfig['staff_salary_type_code'] == 'shift')
                                                @include('staff-salary::staff-salary-template.salary-shift')
                                            @elseif($staffSalaryConfig['staff_salary_type_code'] == 'hourly')
                                                @include('staff-salary::staff-salary-template.salary-hour')
                                            @else
                                                @include('staff-salary::staff-salary-template.salary-month')
                                            @endif
                                        @endif
                                    </div>

                                </div>
                                <br>
                                <div class="row padding_row border">
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Lương làm thêm giờ')</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8 text-right">

                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                    @if(isset($staffSalaryOvertime))

                                                        <input type="checkbox" class="manager-btn" name="ckbOvertime"
                                                               checked="checked"
                                                               onclick="salaryTempalte.checkOvertime();">
                                                        <span></span>
                                                    @else
                                                        <input type="checkbox" class="manager-btn" name="ckbOvertime"
                                                               onclick="salaryTempalte.checkOvertime();">
                                                        <span></span>
                                                    @endif

                                                </label>
                                            </span>

                                    </div>
                                    <div class="col-md-12" id="tblSalaryOvertime" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table m-table m-table--head-bg-default" id="tblOvertime">
                                                <thead class="bg">
                                                <tr>
                                                    <th class="tr_thead_list"></th>
                                                    <th class="tr_thead_list text-center">@lang('Mức lương')</th>
                                                    <th class="tr_thead_list text-center">@lang('Thứ bảy')</th>
                                                    <th class="tr_thead_list text-center">@lang('Chủ nhật')</th>
                                                    <th class="tr_thead_list text-center">@lang('Ngày lễ')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td style="vertical-align: middle;">
                                                        <input type="hidden" class="form-control"
                                                               value="{{ $branch_id }}">
                                                        @lang('Mặc định')
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" name="staff_salary_overtime_weekday"
                                                                   class="form-control m-input numeric_child"
                                                                   id="staff_salary_overtime_weekday"
                                                                   placeholder="{{__('Hãy nhập lương cứng')}}"
                                                                   value="{{number_format($staffSalaryOvertime['staff_salary_overtime_weekday'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">

                                                            <div class="input-group-append">
                                                                <div class="btn-group btn-group-toggle"
                                                                     data-toggle="buttons">
                                                                    <label class="btn btn-secondary active">
                                                                        <input type="radio" name="options"
                                                                               autocomplete="off" checked=""
                                                                               disabled="true">
                                                                        <span class="salary-unit-name">$</span>
                                                                    </label>
                                                                    <span class="input-group-text"
                                                                          id="basic-addon2">/ @lang('Giờ')</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error-staff-salary-overtime-weekday"></span>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" name="staff_salary_overtime_saturday"
                                                                   class="form-control m-input numeric_child"
                                                                   id="staff_salary_overtime_saturday"
                                                                   placeholder="{{__('Hãy nhập lương cứng')}}"
                                                                   value="{{number_format($staffSalaryOvertime['staff_salary_overtime_saturday'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                            <div class="input-group-append">
                                                                <div class="btn-group btn-group-toggle"
                                                                     data-toggle="buttons">
                                                                    @if(isset($staffSalaryOvertime['staff_salary_overtime_saturday_type']))
                                                                        @if($staffSalaryOvertime['staff_salary_overtime_saturday_type'] == 'money')
                                                                            <label class="btn btn-secondary active">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSaturdayMoney"
                                                                                       id="ckbStaffSalaryOvertimeSaturdayMoney"
                                                                                       autocomplete="off" checked=""
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('money');">
                                                                                <span>$</span>
                                                                            </label>
                                                                            <label class="btn btn-secondary">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSaturdayPercent"
                                                                                       id="ckbStaffSalaryOvertimeSaturdayPercent"
                                                                                       autocomplete="off"
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('percent');">
                                                                                %
                                                                            </label>
                                                                        @else
                                                                            <label class="btn btn-secondary">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSaturdayMoney"
                                                                                       id="ckbStaffSalaryOvertimeSaturdayMoney"
                                                                                       autocomplete="off" checked=""
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('money');">
                                                                                <span>$</span>
                                                                            </label>
                                                                            <label class="btn btn-secondary active">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSaturdayPercent"
                                                                                       id="ckbStaffSalaryOvertimeSaturdayPercent"
                                                                                       autocomplete="off"
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('percent');">
                                                                                %
                                                                            </label>
                                                                        @endif
                                                                    @else
                                                                        <label class="btn btn-secondary active">
                                                                            <input type="radio"
                                                                                   name="ckbStaffSalaryOvertimeSaturdayMoney"
                                                                                   id="ckbStaffSalaryOvertimeSaturdayMoney"
                                                                                   autocomplete="off" checked=""
                                                                                   onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('money');"
                                                                                   value="0">
                                                                            <span>$</span>
                                                                        </label>
                                                                        <label class="btn btn-secondary">
                                                                            <input type="radio"
                                                                                   name="ckbStaffSalaryOvertimeSaturdayPercent"
                                                                                   id="ckbStaffSalaryOvertimeSaturdayPercent"
                                                                                   autocomplete="off"
                                                                                   onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('percent');"
                                                                                   value="0"> %
                                                                        </label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error-staff-salary-overtime-saturday"></span>
                                                        <input type="hidden" class="form-control"
                                                               id="staff_salary_overtime_saturday_type"
                                                               value="{{ $staffSalaryOvertime['staff_salary_overtime_saturday_type'] ?? 'money' }}">
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" name="staff_salary_overtime_sunday"
                                                                   class="form-control m-input numeric_child"
                                                                   id="staff_salary_overtime_sunday"
                                                                   placeholder="{{__('Hãy nhập lương cứng')}}"
                                                                   value="{{number_format($staffSalaryOvertime['staff_salary_overtime_sunday'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                            <div class="input-group-append">
                                                                <div class="btn-group btn-group-toggle"
                                                                     data-toggle="buttons">
                                                                    @if(isset($staffSalaryOvertime['staff_salary_overtime_sunday_type']))
                                                                        @if($staffSalaryOvertime['staff_salary_overtime_sunday_type'] == 'money')
                                                                            <label class="btn btn-secondary active">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSundayMoney"
                                                                                       id="ckbStaffSalaryOvertimeSundayMoney"
                                                                                       autocomplete="off" checked=""
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('money');">
                                                                                <span>$</span>
                                                                            </label>
                                                                            <label class="btn btn-secondary">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSundayPercent"
                                                                                       id="ckbStaffSalaryOvertimeSundayPercent"
                                                                                       autocomplete="off"
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('percent');">
                                                                                %
                                                                            </label>
                                                                        @else
                                                                            <label class="btn btn-secondary">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSundayMoney"
                                                                                       id="ckbStaffSalaryOvertimeSundayMoney"
                                                                                       autocomplete="off" checked=""
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('money');">
                                                                                <span>$</span>
                                                                            </label>
                                                                            <label class="btn btn-secondary active">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeSundayPercent"
                                                                                       id="ckbStaffSalaryOvertimeSundayPercent"
                                                                                       autocomplete="off"
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('percent');">
                                                                                %
                                                                            </label>
                                                                        @endif
                                                                    @else
                                                                        <label class="btn btn-secondary active">
                                                                            <input type="radio"
                                                                                   name="ckbStaffSalaryOvertimeSundayMoney"
                                                                                   id="ckbStaffSalaryOvertimeSundayMoney"
                                                                                   autocomplete="off" checked=""
                                                                                   onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('money');">
                                                                            <span>$</span>
                                                                        </label>
                                                                        <label class="btn btn-secondary">
                                                                            <input type="radio"
                                                                                   name="ckbStaffSalaryOvertimeSundayPercent"
                                                                                   id="ckbStaffSalaryOvertimeSundayPercent"
                                                                                   autocomplete="off"
                                                                                   onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('percent');">
                                                                            %
                                                                        </label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error-staff-salary-overtime-sunday"></span>
                                                        <input type="hidden" class="form-control"
                                                               id="staff_salary_overtime_sunday_type"
                                                               value="{{ $staffSalaryOvertime['staff_salary_overtime_sunday_type'] ?? 'money' }}">
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" name="staff_salary_overtime_holiday"
                                                                   class="form-control m-input numeric_child"
                                                                   id="staff_salary_overtime_holiday"
                                                                   placeholder="{{__('Hãy nhập lương cứng')}}"
                                                                   value="{{number_format($staffSalaryOvertime['staff_salary_overtime_holiday'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                            <div class="input-group-append">
                                                                <div class="btn-group btn-group-toggle"
                                                                     data-toggle="buttons">
                                                                    @if(isset($staffSalaryOvertime['staff_salary_overtime_holiday_type']))
                                                                        @if($staffSalaryOvertime['staff_salary_overtime_holiday_type'] == 'money')
                                                                            <label class="btn btn-secondary active">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeHolidayMoney"
                                                                                       id="ckbStaffSalaryOvertimeHolidayMoney"
                                                                                       autocomplete="off" checked=""
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('money');">
                                                                                <span>$</span>
                                                                            </label>
                                                                            <label class="btn btn-secondary">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeHolidayPercent"
                                                                                       id="ckbStaffSalaryOvertimeHolidayPercent"
                                                                                       autocomplete="off"
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('percent');">
                                                                                %
                                                                            </label>
                                                                        @else
                                                                            <label class="btn btn-secondary">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeHolidayMoney"
                                                                                       id="ckbStaffSalaryOvertimeHolidayMoney"
                                                                                       autocomplete="off" checked=""
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('money');">
                                                                                <span>$</span>
                                                                            </label>
                                                                            <label class="btn btn-secondary active">
                                                                                <input type="radio"
                                                                                       name="ckbStaffSalaryOvertimeHolidayPercent"
                                                                                       id="ckbStaffSalaryOvertimeHolidayPercent"
                                                                                       autocomplete="off"
                                                                                       onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('percent');">
                                                                                %
                                                                            </label>
                                                                        @endif
                                                                    @else
                                                                        <label class="btn btn-secondary active">
                                                                            <input type="radio"
                                                                                   name="ckbStaffSalaryOvertimeHolidayMoney"
                                                                                   id="ckbStaffSalaryOvertimeHolidayMoney"
                                                                                   autocomplete="off" checked=""
                                                                                   onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('money');">
                                                                            <span>$</span>
                                                                        </label>
                                                                        <label class="btn btn-secondary">
                                                                            <input type="radio"
                                                                                   name="ckbStaffSalaryOvertimeHolidayPercent"
                                                                                   id="ckbStaffSalaryOvertimeHolidayPercent"
                                                                                   autocomplete="off"
                                                                                   onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('percent');">
                                                                            %
                                                                        </label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error-staff-salary-overtime-holiday"></span>
                                                        <input type="hidden" class="form-control"
                                                               id="staff_salary_overtime_holiday_type"
                                                               value="{{ $staffSalaryOvertime['staff_salary_overtime_holiday_type'] ?? 'money' }}">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row padding_row border">
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Phụ cấp')</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8 text-right">
                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                     @if(isset($arraySalaryAllowance))
                                                        @if(count($arraySalaryAllowance) > 0)
                                                            <input type="checkbox" name="ckbAllowances"
                                                                   checked="checked"
                                                                   onclick="salaryTempalte.checkAllowances();">
                                                            <span></span>
                                                        @else
                                                            <input type="checkbox" name="ckbAllowances"
                                                                   onclick="salaryTempalte.checkAllowances();">
                                                            <span></span>
                                                        @endif
                                                    @endif

                                                </label>
                                            </span>
                                    </div>
                                    <div class="col-md-12" id="tblAllowances" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table m-table m-table--head-bg-default"
                                                   id="tblSalaryAllowance">
                                                <thead class="bg">
                                                <tr>
                                                    <th class="tr_thead_list">@lang('Loại phụ cấp')</th>
                                                    <th class="tr_thead_list">@lang('Phụ cấp thụ hưởng')</th>
                                                    <th class="tr_thead_list"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($arraySalaryAllowance))
                                                    @foreach($arraySalaryAllowance as $key => $item)
                                                        <tr>
                                                            <td>
                                                                {{ $item['salary_allowance_name'] }}
                                                                <input type="hidden"
                                                                       value="{{ $item['salary_allowance_id'] }}"
                                                                       id="salary_allowance_id">
                                                            </td>
                                                            <td>
                                                                {{ number_format($item['staff_salary_allowance_num'], 0, '.', ',') }}
                                                                <span class="salary-unit-name">@lang("VNĐ")</span>

                                                                <input type="hidden"
                                                                       value="{{ $item['staff_salary_allowance_num'] }}"
                                                                       id="staff_salary_allowance_num">
                                                            </td>
                                                            <td nowrap="">

                                                                <a onclick="salaryTempalte.removeCell(this);"
                                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                                   title="Delete">
                                                                    <i class="la la-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row" style="padding-bottom: 10px;">
                                            <a href="javascript:void(0)"
                                               onclick="salaryTempalte.showModalAllowancesAdd()"
                                               class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
                                            <span>
                                                <i class="fa fa-plus-circle"></i>
                                                <span>@lang('Thêm điều kiện')</span>
                                            </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row padding_row border" style="display: none;">
                                    <div class="col-md-4">
                                        <div class="form-group m-form__group">
                                            <label>
                                                <b>@lang('Thưởng / phạt')</b>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-8 text-right">
                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                     @if(isset($arraySalaryBonusMinus))
                                                        @if(count($arraySalaryBonusMinus) > 0)
                                                            <input type="checkbox" name="ckbBonusMinus"
                                                                   checked="checked"
                                                                   onclick="salaryTempalte.checkBonusMinus();">
                                                            <span></span>
                                                            @elsespan
                                                            <input type="checkbox" name="ckbBonusMinus"
                                                                   onclick="salaryTempalte.checkBonusMinus();">
                                                            <span></span>
                                                        @endif
                                                    @endif

                                                </label>
                                            </span>
                                    </div>
                                    <div class="col-md-12" id="divBonusMinus" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table m-table m-table--head-bg-default" id="tblBonusMinus">
                                                <thead class="bg">
                                                <tr>
                                                    <th class="tr_thead_list">@lang('Loại thưởng / phạt')</th>
                                                    <th class="tr_thead_list">@lang('Số tiền thưởng / phạt')</th>
                                                    <th class="tr_thead_list"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($arraySalaryBonusMinus))
                                                    @foreach($arraySalaryBonusMinus as $key => $item)
                                                        <tr>
                                                            <td>
                                                                {{ $item['salary_bonus_minus_name'] }}
                                                                <input type="hidden"
                                                                       value="{{ $item['salary_bonus_minus_id'] }}"
                                                                       id="salary_bonus_minus_id">
                                                            </td>
                                                            <td>

                                                                @if($item['salary_bonus_minus_type'] == 'bonus')
                                                                    + {{ number_format($item['staff_salary_bonus_minus_num'], 0, '.', ',') }}
                                                                    <span class="salary-unit-name">@lang("VNĐ")</span>
                                                                @else
                                                                    - {{ number_format($item['staff_salary_bonus_minus_num'], 0, '.', ',') }}
                                                                    <span class="salary-unit-name">@lang("VNĐ")</span>
                                                                @endif
                                                                <input type="hidden"
                                                                       value="{{ $item['staff_salary_bonus_minus_num'] }}"
                                                                       id="staff_salary_bonus_minus_num">
                                                            </td>
                                                            <td nowrap="">

                                                                <a onclick="salaryTempalte.removeCell(this);"
                                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                                   title="Delete">
                                                                    <i class="la la-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row" style="padding-bottom: 10px;">
                                            <a href="javascript:void(0)"
                                               onclick="salaryTempalte.showModalBonusMinusAdd()"
                                               class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
                                            <span>
                                                <i class="fa fa-plus-circle"></i>
                                                <span>@lang('Thêm điều kiện')</span>
                                            </span>
                                            </a>
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
                                        <a onclick="staffSalary.saveSalary();"
                                           class="btn btn-primary m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10"
                                           style="color:#fff !important">
                                            <i class="la la-edit"></i>
                                            {{__('CẬP NHẬT')}}
                                        </a>

                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="modal-template-salary-add"></div>
    <div id="modal-salary-bonus-minus-add"></div>
    <div id="modal-allowances-add"></div>
    <div id="modal-template-add"></div>

@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/huniel.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/staff/edit.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/staff-salary/staff-salary-template/list.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/staff-salary/staff-salary/list.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}"></script>

    {{-- <script src="{{asset('static/backend/js/staff-salary/staff-salary/list.js?v='.time())}}"></script> --}}
    {{-- <script>
        staffSalary._init();
    </script> --}}

    <script>
        function demo(a) {
            $(a).css('background-color', '#0067AC');
        }

        AjaxHandle.startListen({
            form: '.ajax',
            button: '.submit',
            submit: '.submit',
            callback: function () {


            }
        });

        // $('.dropdown-menu a').on('click', function (event) {
        //     var $target = $(event.currentTarget),
        //         val = $target.attr('data-value'),
        //         $inp = $target.find('input'),
        //         idx;
        //
        //     if ((idx = options.indexOf(val)) > -1) {
        //         options.splice(idx, 1);
        //         setTimeout(function () {
        //             $inp.prop('checked', false)
        //         }, 0);
        //     } else {
        //         options.push(val);
        //         setTimeout(function () {
        //             $inp.prop('checked', true)
        //         }, 0);
        //     }
        //     $(event.target).blur();
        //     console.log(options);
        //     return false;
        // });
    </script>

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/staff-salary/template/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init();
        salaryTempalte.chooseUnitAndType(true);
        // new AutoNumeric.multiple('#staff_salary_overtime_weekday, #staff_salary_overtime_saturday, #staff_salary_overtime_sunday, #staff_salary_overtime_holiday', {
        //     currencySymbol: '',
        //     decimalCharacter: '.',
        //     digitGroupSeparator: ',',
        //     decimalPlaces: decimal_number,
        //     minimumValue: 0
        // });
        $('#bank_number').on('keypress', function (event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
    </script>

    <script type="text/template" id="tr-allowance-tpl">
        <tr class="tr_allowance">
            <td class="text-center">
                <input type="hidden" class="salary_allowance_id" value="{salary_allowance_id}">
                {salary_allowance_name}
            </td>
            <td class="text-center">
                <input type="hidden" class="staff_salary_allowance_num" value="{staff_salary_allowance_num}">
                {staff_salary_allowance_num}
            </td>
            <td class="text-center">
                <a href="javascript:void(0)" onclick="view.removeAllowance(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xoá')">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="head-table-default-tpl">
        <th class="tr_thead_list text-center salary_not_month">@lang('Thứ bảy')</th>
        <th class="tr_thead_list text-center salary_not_month">@lang('Chủ nhật')</th>
        <th class="tr_thead_list text-center salary_not_month">@lang('Ngày lễ')</th>
    </script>
    <script type="text/template" id="body-table-default-tpl">
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_saturday_default"
                       name="salary_saturday_default" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_saturday_default_type" checked
                                   value="money">
                            <span>$</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_saturday_default_type" value="percent">
                            %
                        </label>
                    </div>
                </div>
            </div>
            <div id="salary_saturday_default-error"></div>
        </td>
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_sunday_default"
                       name="salary_sunday_default" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_sunday_default_type" checked
                                   value="money">
                            <span>$</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_sunday_default_type" value="percent"> %
                        </label>
                    </div>
                </div>
            </div>
            <div id="salary_sunday_default-error"></div>
        </td>
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_holiday_default"
                       name="salary_holiday_default" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_holiday_default_type" checked
                                   value="money">
                            <span>$</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_holiday_default_type" value="percent">
                            %
                        </label>
                    </div>
                </div>
            </div>
            <div id="salary_holiday_default-error"></div>
        </td>
    </script>
    <script type="text/template" id="head-table-overtime-tpl">
        <th class="tr_thead_list text-center salary_not_month">@lang('Thứ bảy')</th>
        <th class="tr_thead_list text-center salary_not_month">@lang('Chủ nhật')</th>
        <th class="tr_thead_list text-center salary_not_month">@lang('Ngày lễ')</th>
    </script>
    <script type="text/template" id="body-table-overtime-tpl">
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_saturday_overtime"
                       name="salary_saturday_overtime" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_saturday_overtime_type" checked
                                   value="money">
                            <span>$</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_saturday_overtime_type" value="percent">
                            %
                        </label>
                    </div>
                </div>
            </div>
            <div id="salary_saturday_overtime-error"></div>
            <input type="hidden" class="form-control" id="salary_saturday_overtime_type" value="money">
        </td>
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_sunday_overtime"
                       name="salary_sunday_overtime" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_sunday_overtime_type" checked
                                   value="money">
                            <span>$</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_sunday_overtime_type" value="percent"> %
                        </label>
                    </div>
                </div>
            </div>
            <div id="salary_sunday_overtime-error"></div>
        </td>
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_holiday_overtime"
                       name="salary_holiday_overtime" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_holiday_overtime_type" checked
                                   value="money">
                            <span>$</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_holiday_overtime_type" value="percent">
                            %
                        </label>
                    </div>
                </div>
            </div>
            <div id="salary_holiday_overtime-error"></div>
        </td>
    </script>
@stop

