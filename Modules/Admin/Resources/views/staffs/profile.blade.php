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
            background-color: #4fc4ca;
        }

        .btn-primary, .btn-primary:hover {
            color: #fff;
            background-color: #4fc4ca !important;
            border-color: #4fc4ca !important;
        }

        .btn.btn-default, .btn.btn-secondary.active {
            color: #fff !important;
            background: #4fc4ca !important;
            border-color: #4fc4ca !important;
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
    <div class="m-portlet ">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-edit"></i> {{__('CHỈNH SỬA NHÂN VIÊN')}}</span>
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
                    <form id="form-edit">
                        <input type="hidden" id="staff_id" name="staff_id" value="{{$item['staff_id']}}">
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
                                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" id="getFile" type="file" onchange="uploadImage(this);" class="form-control"
                                                   style="display:none">
            
            
                                            <div class="m-widget19__action" style="max-width: 155px">
                                                <a href="javascript:void(0)" onclick="document.getElementById('getFile').click()"
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
                                                <label>{{__('Họ tên')}}:<b class="text-danger">*</b></label>
                                                <input type="text" name="full_name" class="form-control m-input btn-sm"
                                                       id="full_name"
                                                       placeholder="{{__('Hãy nhập họ tên')}}" value="{{$item['full_name']}}">
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>
                                                    {{__('Ngày sinh')}}:
                                                </label>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <select class="form-control op_day width-select" title="{{__('Ngày')}}" id="day"
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
                                                        <select class="form-control width-select" title="{{__('Tháng')}}"
                                                                style="text-align-last:center;" id="month" name="month">
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
                                                <label>{{__('Giới tính')}}:</label>
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
                                                <label>{{__('Số điện thoại')}}:<b class="text-danger">*</b></label>
                                                <input type="number" name="phone1" class="form-control m-input btn-sm" id="phone1"
                                                       placeholder="{{__('Hãy nhập số điện thoại')}}"
                                                       onkeydown="javascript: return event.keyCode == 69 ? false : true"
                                                       value="{{$item['phone1']}}">
            
            
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>{{__('Địa chỉ')}}:<b class="text-danger">*</b></label>
                                                <input type="text" name="address" class="form-control m-input btn-sm" id="address"
                                                       placeholder="{{__('Hãy nhập địa chỉ')}}" value="{{$item['address']}}">
                                                {{--<span class="error-name"></span>--}}
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>{{__('Email')}}:</label>
                                                <input type="text" name="email" class="form-control m-input btn-sm" id="email"
                                                       placeholder="{{__('Hãy nhập email')}}" value="{{$item['email']}}">
                                                <span class="error_email" style="color: #ff0000"></span>
                                            </div>
                                            {{--<div class="form-group m-form__group ">--}}
                                                {{--<label>{{__('Quyền hạn')}}:</label>--}}
                                                {{--<select name="is_admin" class="form-control" id="is_admin">--}}
                                                    {{--@if($item['is_admin']==0)--}}
                                                        {{--<option value="0" selected>{{__('Quản lý')}}</option>--}}
                                                        {{--<option value="1">{{__('Admin')}}</option>--}}
                                                    {{--@else--}}
                                                        {{--<option value="0">{{__('Quản lý')}}</option>--}}
                                                        {{--<option value="1" selected>{{__('Admin')}}</option>--}}
                                                    {{--@endif--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group m-form__group">
                                                <label>{{__('Chi nhánh')}}:<b class="text-danger">*</b></label>
                                                <div class="input-group">
                                                    <select name="branch_id" id="branch_id" class="form-control m-input" disabled>
                                                        <option></option>
                                                        @foreach($branch as $key=>$value)
                                                            {{--<option value="{{$key}}">{{$value}}</option>--}}
                                                            @if($item['branch_id']==$key)
                                                                <option value="{{$key}}" selected>{{$value}}</option>
                                                            @else
                                                                <option value="{{$key}}">{{$value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
            
                                            </div>
                                            <div class="form-group m-form__group" {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                                <label>{{__('Chức vụ')}}:<b class="text-danger">*</b></label>
                                                <div class="input-group">
                                                    <select name="staff_title_id" id="staff_title_id"
                                                            class="form-control m-input" disabled>
                                                        <option></option>
                                                        @foreach($title as $key=>$value)
                                                            <option value="{{$key}}">{{$value}}</option>
                                                            @if($item['staff_title_id']==$key)
                                                                <option value="{{$key}}" selected>{{$value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
            
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>{{__('Phòng ban')}}:<b class="text-danger">*</b></label>
                                                <div class="input-group">
                                                    <select name="department_id" id="department_id"
                                                            class="form-control m-input" disabled>
                                                        <option value="">{{__('Hãy chọn phòng ban')}}</option>
                                                        @foreach($depart as $key=>$value)
                                                            <option value="{{$key}}">{{$value}}</option>
                                                            @if($item['department_id']==$key)
                                                                <option value="{{$key}}" selected>{{$value}}</option>
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
                                            <div class="form-group m-form__group ">
                                                <label>{{__('Tên tài khoản')}}:<b class="text-danger">*</b></label>
                                                <input type="text" name="user_name" class="form-control m-input btn-sm"
                                                       id="user_name"
                                                       placeholder="{{__('Hãy nhập tên tài khoản')}}" value="{{$item['user_name']}}" disabled>
                                                <span class="error_user" style="color: #ff0000"></span>
            
                                            </div>
                                            <div class="form-group m-form__group" {{ $errors->has('password') ? ' has-danger' : '' }}>
                                                <label>{{__('Mật khẩu mới')}}:</label>
                                                <input type="password" name="password" class="form-control m-input btn-sm"
                                                       id="password"
                                                       placeholder="{{__('Hãy nhập mật khẩu')}}">
                                                {{--<span class="error-name"></span>--}}
                                            </div>
                                            <div class="form-group m-form__group" {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}>
                                                <label>{{__('Nhập lại mật khẩu')}}:</label>
                                                <input type="password" name="repass" class="form-control m-input btn-sm"
                                                       id="repass" placeholder="{{__('Nhập lại mật khẩu')}}">
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label>{{__('Trạng thái')}}:</label>
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
                                    <input id="is_actived" name="is_actived" type="checkbox"
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
                                            class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md btn_edit m--margin-left-10">
                                        <span>
                                        <i class="la la-edit"></i>
                                        <span>{{__('CẬP NHẬT')}}</span>
                                        </span>
                                    </button>
                                    {{--<button type="button"--}}
                                    {{--class="btn btn-primary  dropdown-toggle dropdown-toggle-split m-btn m-btn--md"--}}
                                    {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                                    {{--</button>--}}
                                    {{--<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"--}}
                                    {{--style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(160px, 49px, 0px);">--}}
                                    {{--<button type="submit" class="dropdown-item btn_edit_new"--}}
                                    {{--><i class="la la-plus"></i> Lưu &amp; Tiếp tục--}}
                                    {{--</button>--}}
                                    {{--<button type="submit" class="dropdown-item"><i class="la la-undo"></i> Lưu &amp; Đóng--}}
                                    {{--</button>--}}
                                    {{--</div>--}}
            
                                </div>
                            </div>
                        </div>
                    </form>
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
                                                <input type="hidden" value="{{ $item['staff_id'] }}" name="time_keeping_staff_id" id="time_keeping_staff_id">
                                                <select class="form-control m_selectpicker" id="date_object" name="date_object" style="width:100%;">
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
                                                        <a target="_blank" href="{{route('staff-salary.detail-staff')}}?staff_id={{ $itemSalary['staff_id'] }}&staff_salary_id={{ $itemSalary['staff_salary_id'] }}">
                                                            @lang('Bảng lương') {{ \Carbon\Carbon::createFromFormat('Y-m-d', $itemSalary['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $itemSalary['end_date'])->format('d/m/Y') }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $itemSalary['staff_salary_pay_period_name'] }}
                                                    </td>
                                                    <td>
                                                        @if($itemSalary['staff_salary_status'] == 1)
                                                            Đã Chốt Lương
                                                        @else
                                                            Chưa Chốt Lương
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

    <div id="my-modal"></div>
    <div id="my-modal-my-shift"></div>
    <form id="form-work" autocomplete="off">
        <div id="append-add-work"></div>
    </form>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/staff/edit.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/shift/time-working-staff/list.js')}}"
    type="text/javascript"></script>
    <script>
        $('#date_object').select2();
        $('#date_object').change(function(){
            $(".frmFilter").submit();
        });
        $('#autotable').PioTable({
            baseUrl: laroute.route('admin.timekeeping.list_detail')
        });
    </script>
@stop
