{{--{{dd(old("product_id[]"))}}--}}
@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ LƯƠNG')}}</span>
@endsection
@section('content')
    <style>
        .btn.btn-default, .btn.btn-secondary.active {
            color: #fff !important;
            background: #4fc4ca !important;
            border-color: #4fc4ca !important;
        }
    </style>

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THÊM MẪU ÁP DỤNG')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">
            <form id="form-register">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên mẫu lương'):<b class="text-danger">*</b>
                            </label>
                            <input type="text" class="form-control m-input" id="staff_salary_template_name" name="staff_salary_template_name"
                                   placeholder="@lang('Tên mẫu lương')">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Kỳ hạn trả lương'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group m-input-group">
                                <select class="form-control" id="staff_salary_pay_period_code"
                                        name="staff_salary_pay_period_code">
                                    <option></option>
                                    @foreach($optionPayPeriod as $v)
                                        <option value="{{$v['staff_salary_pay_period_code']}}">
                                            {{__($v['staff_salary_pay_period_name'])}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Loại lương'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group m-input-group">
                                <select class="form-control" id="staff_salary_type_code" name="staff_salary_type_code" onchange="view.chooseUnitAndType()">
                                    @foreach($optionType as $v)
                                        <option value="{{$v['staff_salary_type_code']}}">{{__($v['staff_salary_type_name'])}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group" style="display: none;">
                            <label class="black_title">
                                @lang('Đơn vị tiền tệ'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group m-input-group">
                                <select class="form-control" id="staff_salary_unit_code" name="staff_salary_unit_code" onchange="view.chooseUnitAndType()">
                                    @foreach($optionUnit as $v)
                                        <option value="{{$v['staff_salary_unit_code']}}" selected>{{$v['staff_salary_unit_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Hình thức trả lương'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group m-input-group">
                                <select class="form-control" id="payment_type" name="payment_type">
                                    <option></option>
                                    <option value="cash">@lang('Tiền mặt')</option>
                                    <option value="transfer">@lang('Chuyển khoản')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <table class="table m-table m-table--head-bg-default" id="table_default">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list"></th>
                            <th class="tr_thead_list text-center">@lang('Mức lương')</th>
                            <th class="tr_thead_list text-center salary_not_month">@lang('Thứ bảy')</th>
                            <th class="tr_thead_list text-center salary_not_month">@lang('Chủ nhật')</th>
                            <th class="tr_thead_list text-center salary_not_month">@lang('Ngày lễ')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="vertical-align: middle;">
                                @lang('Mặc định')
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input numeric" id="salary_default" name="salary_default" value="0">

                                    <div class="input-group-append">
                                        <span class="input-group-text text_type_default">
                                        </span>
                                    </div>
                                </div>
                                <div id="salary_default-error"></div>
                            </td>
                            <td class="salary_not_month">
                                <div class="input-group">
                                    <input type="text" class="form-control m-input numeric_child" id="salary_saturday_default"
                                           name="salary_saturday_default" value="0">
                                         
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="salary_saturday_default_type" checked
                                                       value="money"> <span class="">$</span>
                                                       
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
                                                       value="money"> <span class="">$</span>
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
                                                       value="money"> <span class="">$</span>
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
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <strong>@lang('Lương làm thêm giờ')</strong>
                    </div>
                    <div class="col-lg-6" style="text-align: right;">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label>
                                    <input type="checkbox" class="manager-btn" name="is_overtime" id="is_overtime"
                                           onchange="view.checkIsOvertime(this);">
                                    <span></span>

                                </label>
                            </span>
                    </div>
                </div>
                <div class="form-group div_overtime" style="display: none;">
                    <table class="table m-table m-table--head-bg-default" id="table_overtime">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list"></th>
                            <th class="tr_thead_list text-center">@lang('Mức lương')</th>
                            <th class="tr_thead_list text-center salary_not_month">@lang('Thứ bảy')</th>
                            <th class="tr_thead_list text-center salary_not_month">@lang('Chủ nhật')</th>
                            <th class="tr_thead_list text-center salary_not_month">@lang('Ngày lễ')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="vertical-align: middle;">
                                @lang('Mặc định')
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input numeric" id="salary_overtime" name="salary_overtime" value="0">
                                    <div class="input-group-append">
                                        <span class="input-group-text text_type_overtime">
                                        </span>
                                    </div>
                                </div>
                                <div id="salary_overtime-error"></div>
                            </td>
                            <td class="salary_not_month">
                                <div class="input-group">
                                    <input type="text" class="form-control m-input numeric_child" id="salary_saturday_overtime"
                                           name="salary_saturday_overtime" value="0">

                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="salary_saturday_overtime_type" checked
                                                       value="money"> <span class="">$</span>
                                            </label>
                                            <label class="btn btn-secondary">
                                                <input type="radio" name="salary_saturday_overtime_type" value="percent">
                                                %
                                            </label>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div id="salary_saturday_overtime-error"></div>
                            </td>
                            <td class="salary_not_month">
                                <div class="input-group">
                                    <input type="text" class="form-control m-input numeric_child" id="salary_sunday_overtime"
                                           name="salary_sunday_overtime" value="0">

                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="salary_sunday_overtime_type" checked
                                                       value="money"> <span class="">$</span>
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
                                                       value="money"> <span class="">$</span>
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
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <strong>@lang('Phụ cấp')</strong>
                    </div>
                    <div class="col-lg-6" style="text-align: right;">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label>
                                    <input type="checkbox" class="manager-btn" id="is_allowance" name="is_allowance"
                                           onchange="view.checkIsAllowance(this);">
                                    <span></span>

                                </label>
                            </span>
                    </div>
                </div>
                <div class="form-group div_allowance" style="display: none;">
                    <table class="table m-table m-table--head-bg-default" id="table_allowance">
                        <thead class="bg">
                        <tr>
                            {{--<th class="tr_thead_list text-center">@lang('Loại phụ cấp')</th>--}}
                            <th class="tr_thead_list text-center">@lang('Tên phụ cấp')</th>
                            <th class="tr_thead_list text-center">@lang('Phụ cấp thưởng')</th>
                            {{--<th class="tr_thead_list text-center">@lang('Phụ cấp chịu thuế')</th>--}}
                            <th class="tr_thead_list"></th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <a href="javascript:void(0)" onclick="view.showPopCreateAllowance()" class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
                        <span>
                            <i class="fa fa-plus-circle"></i>
                            <span>@lang('Thêm điều kiện')</span>
                        </span>
                    </a>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('staff-salary.template')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="view.store()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="modal-allowance"></div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/staff-salary/template/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init();
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
                <a href="javascript:void(0)" onclick="view.removeAllowance(this)" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
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
                                   value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_saturday_default_type" value="percent">
                            %
                        </label>
                    </div>
                </div>
            </div>
        </td>
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_sunday_default"
                       name="salary_sunday_default" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_sunday_default_type" checked
                                   value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_sunday_default_type" value="percent"> %
                        </label>
                    </div>
                </div>
            </div>
        </td>
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_holiday_default"
                       name="salary_holiday_default" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_holiday_default_type" checked
                                   value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_holiday_default_type" value="percent">
                            %
                        </label>
                    </div>
                </div>
            </div>
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
                                   value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="salary_saturday_overtime_type" value="percent">
                            %
                        </label>
                    </div>
                </div>
                
            </div>
            <div id="salary_saturday_overtime-error"></div>
        </td>
        <td class="salary_not_month">
            <div class="input-group">
                <input type="text" class="form-control m-input numeric_child" id="salary_sunday_overtime"
                       name="salary_sunday_overtime" value="0">

                <div class="input-group-append">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="salary_sunday_overtime_type" checked
                                   value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
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
                                   value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
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
