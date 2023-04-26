<div class="table-responsive">
    <table class="table m-table m-table--head-bg-default" id="tblSalary">
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
                    <input type="text" name="staff_salary_weekday" class="form-control m-input" id="staff_salary_weekday"
                           placeholder="{{__('Hãy nhập lương cứng')}}"
                           value="{{number_format($arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-secondary active">
                                <input type="radio" name="options" id="txtSalary" autocomplete="off" checked="">
                                <span class="salary-unit-name">$</span>
                            </label>
                        </div>
                    </div>
                </div>
                <span class="error-staff-salary-weekday"></span>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" name="staff_salary_saturday" class="form-control m-input" id="staff_salary_saturday"
                           placeholder="{{__('Hãy nhập lương cứng')}}"
                           value="{{number_format($arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_value'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @if(isset($arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_type']) && $arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_type'] == 'percent') 
                                <label class="btn btn-secondary">
                                    <input type="radio" name="ckbStaffSalarySaturdayMoney" id="ckbStaffSalarySaturdayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalarySaturday('money');">
                                    <span class="">$</span>
                                </label>
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="ckbStaffSalarySaturdayPercent" id="ckbStaffSalarySaturdayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalarySaturday('percent');"> %
                                </label>
                            @else
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="ckbStaffSalarySaturdayMoney" id="ckbStaffSalarySaturdayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalarySaturday('money');">
                                    <span class="">$</span>
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="ckbStaffSalarySaturdayPercent" id="ckbStaffSalarySaturdayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalarySaturday('percent');"> %
                                </label>
                            @endif

                        </div>
                    </div>
                </div>
                <span class="error-staff-salary-saturday"></span>
                <input type="hidden" class="form-control" id="staff_salary_saturday_type" value="{{ $arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_type'] ?? 'money' }}">
            </td>
            <td>
                <div class="input-group">
                    <input type="text" name="staff_salary_sunday" class="form-control m-input" id="staff_salary_sunday"
                           placeholder="{{__('Hãy nhập lương cứng')}}"
                           value="{{number_format($arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_value'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @if(isset($arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_type']) && $arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_type'] == 'percent') 
                                <label class="btn btn-secondary">
                                    <input type="radio" name="ckbStaffSalarySundayMoney" id="ckbStaffSalarySundayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalarySunday('money');">
                                    <span class="">$</span>
                                </label>
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="ckbStaffSalarySundaydayPercent" id="ckbStaffSalarySundaydayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalarySunday('percent');"> %
                                </label>
                            @else
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="ckbStaffSalarySundayMoney" id="ckbStaffSalarySundayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalarySunday('money');">
                                    <span class="">$</span>
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="ckbStaffSalarySundayPercent" id="ckbStaffSalarySundaydayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalarySunday('percent');"> %
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <span class="error-staff-salary-sunday"></span>
                <input type="hidden" class="form-control" id="staff_salary_sunday_type" value="{{ $arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_type'] ?? 'money' }}">
            </td>
            <td>
                <div class="input-group">
                    <input type="text" name="staff_salary_holiday" class="form-control m-input" id="staff_salary_holiday"
                           placeholder="{{__('Hãy nhập lương cứng')}}"
                           value="{{number_format($arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_value'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            @if(isset($arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_type']) && $arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_type'] == 'percent') 
                                <label class="btn btn-secondary">
                                    <input type="radio" name="ckbStaffSalaryHolidayMoney" id="ckbStaffSalaryHolidayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryHoliday('money');">
                                    <span class="">$</span>
                                </label>
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="ckbStaffSalaryHolidayPercent" id="ckbStaffSalaryHolidayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryHoliday('percent');"> %
                                </label>
                            @else
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="ckbStaffSalaryHolidayMoney" id="ckbStaffSalaryHolidayMoney" autocomplete="off" checked="" onchange="salaryTempalte.checkStaffSalaryHoliday('money');">
                                    <span class="">$</span>
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="ckbStaffSalaryHolidayPercent" id="ckbStaffSalaryHolidayPercent" autocomplete="off" onchange="salaryTempalte.checkStaffSalaryHoliday('percent');"> %
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <span class="error-staff-salary-holiday"></span>
                <input type="hidden" class="form-control" id="staff_salary_holiday_type" value="{{ $arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_type']  ?? 'money' }}">
            </td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    new AutoNumeric.multiple('#staff_salary_weekday, #staff_salary_saturday, #staff_salary_sunday, #staff_salary_holiday', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });
</script>
