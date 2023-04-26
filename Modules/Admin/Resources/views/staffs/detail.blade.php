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
            background-color:#0067AC;
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

        .line_note {
            color: #444;
            font-size: 0.8rem;
            line-height: 20px;
            min-height: 20px;
            min-width: 20px;
            vertical-align: middle;
            text-align: center;
            display: inline-block;
            padding: 0px 3px;
            border-radius: 0.75rem;
        }

        .line_note_shift {
            color: #444;
            font-size: 0.8rem;
            line-height: 15px;
            min-height: 15px;
            min-width: 15px;
            vertical-align: middle;
            text-align: center;
            display: inline-block;
            padding: 0px 2px;
            border-radius: 0.75rem;
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
                        {{__('CHI TIẾT NHÂN VIÊN')}}
                    </h2>

                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active show" data-toggle="tab" href="#m_staff_detail_3_1">
                        @lang('Thông tin nhân viên')
                    </a>
                </li>

                @if(in_array('work-schedule-staff',session('routeList')))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_staff_detail_3_2">
                            @lang('Lịch làm việc')
                        </a>
                    </li>
                @endif
                @if(in_array('salary-staff',session('routeList')))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_staff_detail_3_3">
                            @lang('Bảng lương')
                        </a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="m_staff_detail_3_1" role="tabpanel">
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <input type="hidden" id="staff_avatar" name="staff_avatar"
                                           value="{{$item['staff_avatar']}}">
                                    <input type="hidden" id="staff_avatar_upload" name="staff_avatar_upload" value="">
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
                                                   id="full_name" disabled
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
                                                            name="day" disabled>
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
                                                    <select class="form-control width-select" title="{{__('Tháng')}}"
                                                            style="text-align-last:center;" id="month" name="month"
                                                            disabled>
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
                                                    <select class="form-control width-select" title="{{__('Năm')}}"
                                                            id="year" name="year" disabled>
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
                                                    <input type="radio" checked name="gender" value="male" disabled
                                                            {{$item['gender']=='male'?'checked':''}}> {{__('Nam')}}
                                                    <span></span>
                                                </label>
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender" value="female" disabled
                                                            {{$item['gender']=='female'?'checked':''}}> {{__('Nữ')}}
                                                    <span></span>
                                                </label>
                                                <label class="m-radio cus">
                                                    <input type="radio" name="gender" value="other" disabled
                                                            {{$item['gender']=='other'?'checked':''}}> {{__('Khác')}}
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group ">
                                            <label class="black-title">{{__('Số điện thoại')}}:<b
                                                        class="text-danger">*</b></label>
                                            <input type="number" name="phone1" class="form-control m-input" id="phone1"
                                                   placeholder="{{__('Hãy nhập số điện thoại')}}" disabled
                                                   onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                                   value="{{$item['phone1']}}">


                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Địa chỉ')}}:<b
                                                        class="text-danger">*</b></label>
                                            <input type="text" name="address" class="form-control m-input" id="address"
                                                   placeholder="{{__('Hãy nhập địa chỉ')}}" value="{{$item['address']}}"
                                                   disabled>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Email')}}:</label>
                                            <input type="text" name="email" class="form-control m-input" id="email"
                                                   placeholder="{{__('Hãy nhập email')}}" value="{{$item['email']}}"
                                                   disabled>
                                            <span class="error_email" style="color: #ff0000"></span>
                                        </div>
                                        <div class="row">
                                            <div class="form-group m-form__group col-lg-6">
                                                <label class="black-title">{{__('Số tài khoản')}}:</label>
                                                <input type="text" class="form-control m-input" id="bank_number"
                                                       name="bank_number" value="{{$item['bank_number']}}" disabled
                                                       placeholder="{{__('Hãy nhập số tài khoản')}}">
                                            </div>
                                            <div class="form-group m-form__group col-lg-6">
                                                <label class="black-title">{{__('Ngân hàng')}}:</label>
                                                <input type="text" class="form-control m-input" id="bank_name" disabled
                                                       name="bank_name" value="{{$item['bank_name']}}"
                                                       placeholder="{{__('Hãy nhập ngân hàng')}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Chi nhánh ngân hàng')}}:</label>
                                            <input type="text" class="form-control m-input" id="bank_branch_name"
                                                   disabled
                                                   name="bank_branch_name" value="{{$item['bank_branch_name']}}"
                                                   placeholder="{{__('Hãy nhập chi nhánh ngân hàng')}}">
                                        </div>
                                        <div class="form-group m-form__group ">
                                            <label class="black-title">{{__('Quyền hạn')}}:</label>
                                            <select name="is_admin" class="form-control" id="is_admin" disabled>
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
                                            <select name="role-group-id" class="form-control js-example-data-ajax"
                                                    id="role-group-id" disabled
                                                    multiple="multiple" style="width: 100%">
                                                @foreach($roleGroup as $key => $value)
                                                    @if(in_array($value['id'],$arrayMapRoleGroupStaff))
                                                        <option selected
                                                                value="{{$value['id']}}">{{$value['name']}}</option>
                                                    @else
                                                        <option value="{{$value['id']}}">{{$value['name']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Chi nhánh')}}:<b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <select name="branch_id" id="branch_id" class="form-control m-input"
                                                        disabled>
                                                    <option></option>
                                                    @foreach($optionBranch as $key => $value)
                                                        @if($item['branch_id']==$value['branch_id'])
                                                            <option value="{{$value['branch_id']}}"
                                                                    selected>{{$value['branch_name']}}</option>
                                                        @else
                                                            <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-group m-form__group" {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                            <label class="black-title">{{__('Chức vụ')}}:<b
                                                        class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <select name="staff_title_id" id="staff_title_id" disabled
                                                        class="form-control m-input">
                                                    <option></option>
                                                    @foreach($optionTitle as $key => $value)
                                                        @if($item['staff_title_id']==$value['staff_title_id'])
                                                            <option value="{{$value['staff_title_id']}}"
                                                                    selected>{{$value['staff_title_name']}}</option>
                                                        @else
                                                            <option value="{{$value['staff_title_id']}}">{{$value['staff_title_name']}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Phòng ban')}}:<b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <select name="department_id" id="department_id" disabled
                                                        class="form-control m-input">
                                                    <option value="">{{__('Hãy chọn phòng ban')}}</option>
                                                    @foreach($optionDepartment as $key => $value)

                                                        @if($item['department_id']==$value['department_id'])
                                                            <option value="{{$value['department_id']}}"
                                                                    selected>{{$value['department_name']}}</option>
                                                        @else
                                                            <option value="{{$value['department_id']}}">{{$value['department_name']}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Nhóm')}}:<b
                                                        class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <select name="team_id" id="team_id" disabled
                                                        class="form-control m-input">
                                                    <option></option>
                                                    @foreach($optionTeam as $v)
                                                        <option value="{{$v['team_id']}}" {{$item['team_id'] == $v['team_id'] ? 'selected': ''}}>{{$v['team_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Lương cứng')}}:</label>
                                            <div class="input-group">
                                                <input type="text" name="salary" class="form-control m-input"
                                                       id="salary"
                                                       placeholder="{{__('Hãy nhập lương cứng')}}" disabled
                                                       value="{{number_format($item['salary'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Trợ cấp')}}:</label>
                                            <div class="input-group">
                                                <input type="text" name="subsidize" class="form-control m-input"
                                                       id="subsidize"
                                                       placeholder="{{__('Hãy nhập trợ cấp')}}" disabled
                                                       value="{{number_format($item['subsidize'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Tỉ lệ hoa hồng')}}:</label>
                                            <div class="input-group">
                                                <input type="text" name="commission_rate" class="form-control m-input"
                                                       id="commission_rate"
                                                       placeholder="{{__('Hãy nhập tỉ lệ hoa hồng')}}" disabled
                                                       value="{{number_format($item['commission_rate'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group ">
                                            <label class="black-title">{{__('Tên tài khoản')}}:<b
                                                        class="text-danger">*</b></label>
                                            <input type="text" name="user_name" class="form-control m-input"
                                                   id="user_name" disabled
                                                   placeholder="{{__('Hãy nhập tên tài khoản')}}"
                                                   value="{{$item['user_name']}}">
                                            <span class="error_user" style="color: #ff0000"></span>

                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black-title">{{__('Trạng thái')}}:</label>
                                            <div class="row">
                                                <div class="col-lg-1">
                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                        <label>
                                                            <input id="is_actived" name="is_actived" type="checkbox"
                                                                   disabled
                                                                    {{$item['is_actived']==1?'checked':''}}>
                                                            <span></span>
                                                        </label>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content m--margin-top-40">
                            <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;">
                                <li class="nav-item">
                                    <a class="nav-link son active show" data-toggle="tab"
                                       id="calendar">@lang("DANH SÁCH HOA HỒNG")</a>
                                </li>
                            </ul>
                        </div>
                        <div class="bd-ct">
                            <div id="div-service">
                                <div class="m-demo__preview">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="true">
                                            @if(count($listCommission))
                                                <div class="m-scrollable m-scroller ps ps--active-y m--margin-top-5"
                                                     data-scrollable="true"
                                                     data-height="350" data-mobile-height="300"
                                                     style="height: 300px; overflow: hidden;">
                                                    <table class="table m-table m-table--head-separator-metal"
                                                           style="border-collapse: collapse;">
                                                        <thead>
                                                        <tr>
                                                            <th>@lang("Mã đơn hàng")</th>
                                                            <th>@lang("Tên")</th>
                                                            <th>@lang("Số tiền")</th>
                                                            <th class="tr_thead_list_dt_cus text-center">@lang("Thời gian")</th>
                                                            <th>
                                                                {{__('Trạng thái')}}
                                                            </th>
                                                            <th>
                                                                <button onclick="staffCommission.create('{{$item['staff_id']}}')"
                                                                        class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                                                                    <span>
                                                                        <i class="fa fa-plus-circle"></i>
                                                                        <span> {{__('THÊM')}}</span>
                                                                    </span>
                                                                </button>
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($listCommission as $key => $value)
                                                            <tr>
                                                                <td>
                                                                    @if (isset($value['order_id']) && $value['order_id'] != null)
                                                                        <a href="{{route("admin.order.detail", $value['order_id'])}}"
                                                                           class="line-name font-name">{{$value['order_code']}}</a>
                                                                    @endif
                                                                </td>
                                                                <td>{{$value['object_name']}}</td>
                                                                <td>{{number_format($value['staff_money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                                                <td class="text-center">{{$value['created_at']}}</td>
                                                                <td>
                                                                    @if ($value['status'] == 'approve')
                                                                        @lang('Đã duyệt')
                                                                    @elseif($value['status'] == 'cancel')
                                                                        @lang('Huỷ')
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <button onclick="staffCommission.edit('{{$value['id']}}')"
                                                                            class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                                            title="{{__('Sửa')}}">
                                                                        <i class="la la-edit"></i>
                                                                    </button>
                                                                    <button onclick="staffCommission.remove( {{$value['id']}})"
                                                                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                                            title="{{__('Xoá')}}">
                                                                        <i class="la la-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach


                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--end::m-widget5-->
                                            @else
                                                <div class="row col-12">
                                                    @lang("Không có dữ liệu")
                                                </div>
                                                <div class="row col-12 mt-3">
                                                    <button onclick="staffCommission.create('{{$item['staff_id']}}')"
                                                            class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                                                                        <span>
                                                                            <i class="fa fa-plus-circle"></i>
                                                                            <span> {{__('THÊM')}}</span>
                                                                        </span>
                                                    </button>
                                                </div>

                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m--align-right">
                                @if($salary_id)
                                    <a href="{{route('salary.detail',['id' => $salary_id])}}"
                                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                                    </a>
                                @else
                                    <a href="{{route('admin.staff')}}"
                                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                                    </a>
                                @endif
                                <a href="{{route('admin.staff.edit', $item['staff_id'])}}"
                                   class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_edit m--margin-left-10">
                                    <span>
                                    <i class="la la-edit"></i>
                                    <span>{{__('CHỈNH SỬA')}}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="m_staff_detail_3_2" role="tabpanel">
                    <div class="m-portlet__body">
                        <div id="autotable">
                            <form class="frmFilter bg">
                                <div class="padding_row">
                                    <div class="form-group">
                                        <div class="form-group m-form__group row align-items-center">
                                            <div class="col-lg-3 form-group">
                                                <?php
                                                $month = date('m');
                                                ?>
                                                <input type="hidden" value="{{ $item['staff_id'] }}"
                                                       name="time_keeping_staff_id" id="time_keeping_staff_id">
                                                <select class="form-control m_selectpicker" id="date_object"
                                                        name="date_object" style="width:100%;">
                                                    @for($i = 1; $i <= 12; $i++)
                                                        <option value="{{$i}}" {{$i == date('m') ? 'selected': ''}}>
                                                            {{ __('Tháng '. $i) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @include('shift::timekeeping.list_detail')
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="m_staff_detail_3_3" role="tabpanel">
                    <div class="m-portlet__body">
                        <div id="autotableSalary">
                            <div class="table-responsive">
                                <table class="table table-striped m-table m-table--head-bg-default">
                                    <thead class="bg">
                                    <tr>
                                        <th class="tr_thead_list">#</th>
                                        <th class="tr_thead_list">@lang('Tên bảng lương')</th>
                                        <th class="tr_thead_list">@lang('Kỳ hạn trả lương')</th>
                                        <th class="tr_thead_list">@lang('Trạng thái')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($staffSalary))
                                        @foreach($staffSalary as $key => $itemSalary)
                                            <tr>
                                                <td>
                                                    {{$key+1}}
                                                </td>
                                                <td>
                                                    <a target="_blank"
                                                       href="{{route('staff-salary.detail-staff')}}?staff_id={{ $itemSalary['staff_id'] }}&staff_salary_id={{ $itemSalary['staff_salary_id'] }}">
                                                        @lang('Bảng lương') {{ \Carbon\Carbon::createFromFormat('Y-m-d', $itemSalary['start_date'])->format('d/m/Y') }}
                                                        - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $itemSalary['end_date'])->format('d/m/Y') }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{ $itemSalary['staff_salary_pay_period_name'] }}
                                                </td>
                                                <td>
                                                    @if($itemSalary['staff_salary_status'] == 1)
                                                        {{ __('Đã Chốt Lương') }}
                                                    @else
                                                        {{ __('Chưa Chốt Lương') }}
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="my-modal"></div>
    <div id="my-modal-my-shift"></div>
    <div id="my-modal-recompense"></div>

    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/staff-commission/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/shift/time-working-staff/list.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        is_load = 1;
        $('#role-group-id, #date_object').select2();
        $('#date_object').change(function () {
            $(".frmFilter").submit();
        });
        $('#autotable').PioTable({
            baseUrl: laroute.route('admin.timekeeping.list_detail')
        });

    </script>
@endsection

