<form id="form-salary">
    <input type="hidden" id="staff_salary_config_id" value="{{ $staffSalaryConfig['staff_salary_config_id'] ?? $staff_salary_config_id ?? '' }}">

    <div class="m-portlet__body">
        <div class="row padding_row border">


            <div class="col-md-4 staff_salary_template_id_input">
                <div class="form-group m-form__group">
                    <label>
                        <b>@lang('Mẫu áp dụng')</b><b class="text-danger">*</b>
                    </label>
                    <select class="form-control m-input width-select" name="staff_salary_template_id"
                            id="staff_salary_template_id" style="width: calc(100% - 30px);"
                            onchange="salaryTempalte.changeStaffSalaryTemplate(this)">
                        <option value="">@lang('Chọn mẫu áp dụng')</option>
                        @if(isset($optionStaffSalaryTemplate))
                            @foreach($optionStaffSalaryTemplate as $key => $item)
                                @if($item['staff_salary_template_id']== ($staff_salary_template_id??0) )
                                    <option value="{{ $item['staff_salary_template_id'] }}" selected="selected">{{ __($item['staff_salary_template_name']) }}</option>
                                @else
                                    <option value="{{ $item['staff_salary_template_id'] }}">{{ __($item['staff_salary_template_name']) }}</option>
                                @endif
                            @endforeach
                        @endif

                    </select>
                    
                    <div style="position: absolute;top: 36px;right: 15px;color: #4fc4ca;cursor: pointer;">
                        <a href="javascript:void(0)" onclick="view.showModalAddTemplate()" style="color: #4fc4ca;">
                            <span>
                                <i class="fas fa-plus" style="font-size:20px;"></i>
                            </span>
                            </a>
                    </div>
                </div>
                <span class="error-staff-salary-type"></span>
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
                                @if(isset($staff_salary_type_code) && $staff_salary_type_code == $item['staff_salary_type_code'])
                                    <option value="{{ $item['staff_salary_type_code'] }}" selected="selected">{{ __($item['staff_salary_type_name']) }}</option>
                                @else
                                    <option value="{{ $item['staff_salary_type_code'] }}">{{ __($item['staff_salary_type_name']) }}</option>
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
                        <option value="" selected="selected">@lang('Chọn kỳ hạn trả lương')</option>
                        @if(isset($staffSalaryPayPeriod))
                            @foreach($staffSalaryPayPeriod as $key => $item)
                                @if(isset($staff_salary_pay_period_code) && $staff_salary_pay_period_code == $item['staff_salary_pay_period_code'])
                                    <option value="{{ $item['staff_salary_pay_period_code'] }}" selected="selected">{{ __($item['staff_salary_pay_period_name']) }}</option>
                                @else
                                    <option value="{{ $item['staff_salary_pay_period_code'] }}">{{ __($item['staff_salary_pay_period_name']) }}</option>
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

            <div class="col-md-4" style="display: none;">
                <div class="form-group m-form__group">
                    <label>
                        <b>@lang('Đơn vị tiền tệ')</b><b class="text-danger">*</b>
                    </label>
                    <select class="form-control m-input width-select" name="staff_salary_unit_code"
                            id="staff_salary_unit_code" style="width : 100%;" onchange="salaryTempalte.chooseUnitAndType()">

                        @foreach($optionUnit as $v)
                            <option value="{{$v['staff_salary_unit_code']}}" {{$staff_salary_unit_code == $v['staff_salary_unit_code'] ? 'selected': ''}}>{{$v['staff_salary_unit_name']}}</option>
                        @endforeach

                    </select>
                </div>
                <span class="error-staff_salary_unit_code"></span>
            </div>

            <div class="col-md-4">
                <div class="form-group m-form__group">
                    <label>
                        <b>@lang('Hình thức trả lương')</b><b class="text-danger">*</b>
                    </label>
                    <select class="form-control m-input width-select" name="payment_type"
                            id="payment_type" style="width : 100%;">
                        <option value="">@lang('Chọn hình thức trả lương')</option>
                        <option value="cash" {{$payment_type == 'cash' ? 'selected': ''}}>@lang('Tiền mặt')</option>
                        <option value="transfer" {{$payment_type == 'transfer' ? 'selected': ''}}>@lang('Chuyển khoản')</option>
                    </select>
                </div>
                <span class="error-payment_type"></span>
            </div>


            <div class="col-md-12" id="tblSalaryType">
                @if(isset($item2['staff_salary_type_code'] ))
                    @if($item2['staff_salary_type_code'] == 'shift')
                        @include('staff-salary::staff-salary-template.salary-shift')
                    @elseif($item2['staff_salary_type_code'] == 'hourly')
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
                        @if(isset($item2['is_overtime']) && $item2['is_overtime']==1)

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

            <div class="col-md-12" id="tblSalaryOvertime" @if(isset($item2['is_overtime']) && $item2['is_overtime']==1) @else style="display: none;" @endif>
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
                                <input type="hidden" class="form-control" value="{{ $branch_id }}">
                                @lang('Mặc định')
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="staff_salary_overtime_weekday" class="form-control m-input" id="staff_salary_overtime_weekday"
                                           placeholder="{{__('Hãy nhập lương cứng')}}"
                                           value="{{number_format($item2['salary_overtime'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-secondary active">
                                                <input type="radio" name="options" autocomplete="off" checked="" disabled="true"> <span class="salary-unit-name">$</span>
                                            </label>
                                            <span class="input-group-text" id="basic-addon2">/ @lang('Giờ')</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="error-staff-salary-overtime-weekday"></span>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="staff_salary_overtime_saturday" class="form-control m-input" id="staff_salary_overtime_saturday"
                                           placeholder="{{__('Hãy nhập lương cứng')}}"
                                           value="{{number_format($item2['salary_saturday_overtime'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @if(isset($item2['salary_saturday_overtime_type']))
                                                @if($item2['salary_saturday_overtime_type'] == 'money')
                                                    <label class="btn btn-secondary active">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSaturdayMoney" id="ckbStaffSalaryOvertimeSaturdayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('money');"> <span class="">$</span>
                                                    </label>
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSaturdayPercent" id="ckbStaffSalaryOvertimeSaturdayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('percent');"> %
                                                    </label>
                                                @else
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSaturdayMoney" id="ckbStaffSalaryOvertimeSaturdayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('money');"> <span class="">$</span>
                                                    </label>
                                                    <label class="btn btn-secondary active">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSaturdayPercent" id="ckbStaffSalaryOvertimeSaturdayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('percent');"> %
                                                    </label>
                                                @endif
                                            @else
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="ckbStaffSalaryOvertimeSaturdayMoney" id="ckbStaffSalaryOvertimeSaturdayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('money');"> <span class="">$</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="ckbStaffSalaryOvertimeSaturdayPercent" id="ckbStaffSalaryOvertimeSaturdayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeSaturday('percent');"> %
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="error-staff-salary-overtime-saturday"></span>
                                <input type="hidden" class="form-control" id="staff_salary_overtime_saturday_type" value="{{ $item2['salary_saturday_overtime_type'] ?? 'money' }}">
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="staff_salary_overtime_sunday" class="form-control m-input" id="staff_salary_overtime_sunday"
                                           placeholder="{{__('Hãy nhập lương cứng')}}"
                                           value="{{number_format($item2['salary_sunday_overtime'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @if(isset($item2['salary_sunday_overtime_type']))
                                                @if($item2['salary_sunday_overtime_type'] == 'money')
                                                    <label class="btn btn-secondary active">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSundayMoney" id="ckbStaffSalaryOvertimeSundayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('money');"> <span class="">$</span>
                                                    </label>
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSundayPercent" id="ckbStaffSalaryOvertimeSundayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('percent');"> %
                                                    </label>
                                                @else
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSundayMoney" id="ckbStaffSalaryOvertimeSundayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('money');"> <span class="">$</span>
                                                    </label>
                                                    <label class="btn btn-secondary active">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeSundayPercent" id="ckbStaffSalaryOvertimeSundayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('percent');"> %
                                                    </label>
                                                @endif
                                            @else
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="ckbStaffSalaryOvertimeSundayMoney" id="ckbStaffSalaryOvertimeSundayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('money');"> <span class="">$</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="ckbStaffSalaryOvertimeSundayPercent" id="ckbStaffSalaryOvertimeSundayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeSunday('percent');"> %
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="error-staff-salary-overtime-sunday"></span>
                                <input type="hidden" class="form-control" id="staff_salary_overtime_sunday_type" value="{{ $item2['salary_sunday_overtime_type'] ?? 'money' }}">
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="staff_salary_overtime_holiday" class="form-control m-input" id="staff_salary_overtime_holiday"
                                           placeholder="{{__('Hãy nhập lương cứng')}}"
                                           value="{{number_format($item2['salary_holiday_overtime'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @if(isset($item2['salary_holiday_overtime_type']))
                                                @if($item2['salary_holiday_overtime_type'] == 'money')
                                                    <label class="btn btn-secondary active">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeHolidayMoney" id="ckbStaffSalaryOvertimeHolidayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('money');"> <span class="">$</span>
                                                    </label>
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeHolidayPercent" id="ckbStaffSalaryOvertimeHolidayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('percent');"> %
                                                    </label>
                                                @else
                                                    <label class="btn btn-secondary">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeHolidayMoney" id="ckbStaffSalaryOvertimeHolidayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('money');"> <span class="">$</span>
                                                    </label>
                                                    <label class="btn btn-secondary active">
                                                        <input type="radio" name="ckbStaffSalaryOvertimeHolidayPercent" id="ckbStaffSalaryOvertimeHolidayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('percent');"> %
                                                    </label>
                                                @endif
                                            @else
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="ckbStaffSalaryOvertimeHolidayMoney" id="ckbStaffSalaryOvertimeHolidayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('money');"> <span class="">$</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="ckbStaffSalaryOvertimeHolidayPercent" id="ckbStaffSalaryOvertimeHolidayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryOvertimeHoliday('percent');"> %
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="error-staff-salary-overtime-holiday"></span>
                                <input type="hidden" class="form-control" id="staff_salary_overtime_holiday_type" value="{{ $item2['salary_holiday_overtime_type'] ?? 'money' }}">
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
                                                     @if(isset($templateAllowance))
                                                        @if(count($templateAllowance) > 0)
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
            <div class="col-md-12" id="tblAllowances"@if(isset($templateAllowance) && count($templateAllowance) > 0) @else style="display: none;" @endif >
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
                        @if(isset($templateAllowance))
                            @foreach($templateAllowance as $key => $item)
                                <tr>
                                    <td>
                                        {{ $item['salary_allowance_name'] }}
                                        <input type="hidden"
                                               value="{{ $item['salary_allowance_id'] }}"
                                               id="salary_allowance_id">
                                    </td>
                                    <td>
                                        {{ number_format($item['staff_salary_allowance_num'], 0, '.', ',') }}
                                        <span class=""><span class="">$</span></span>

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
                <div class="row" style="padding-bottom: 10px;">˚
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
                                                        @else
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
                                            <span class="">$</span>
                                        @else
                                            - {{ number_format($item['staff_salary_bonus_minus_num'], 0, '.', ',') }}
                                            <span class="">$</span>
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