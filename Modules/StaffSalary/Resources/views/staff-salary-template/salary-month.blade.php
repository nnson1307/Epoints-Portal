<div class="table-responsive">
    <table class="table m-table m-table--head-bg-default" id="tblSalaryMonthly">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>

            <th class="tr_thead_list text-center">@lang('Lương hàng tháng')</th>
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
                    <input type="text" name="staff_salary_monthly" class="form-control m-input" id="staff_salary_monthly"
                           placeholder="{{__('Hãy nhập lương cứng')}}"
                           value="{{number_format($arrayStaffSalaryAttribute['salary_monthly']['staff_salary_attribute_value'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                    <div class="input-group-append">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="options" autocomplete="off" checked="">
                                    <span class="salary-unit-name">@lang("VNĐ")</span>
                                </label>
                                <span class="input-group-text" id="basic-addon2">/ @lang('Tháng')</span>
                            </div>

                        </div>
                    </div>
                </div>
                <span class="error-staff-salary-monthly"></span>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    new AutoNumeric.multiple('#staff_salary_contract, #staff_salary_monthly', {
        currencySymbol: '',
        decimalCharacter: '.',
        digitGroupSeparator: ',',
        decimalPlaces: decimal_number,
        minimumValue: 0
    });
</script>