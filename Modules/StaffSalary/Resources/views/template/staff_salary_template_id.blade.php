<div class="col-md-4 staff_salary_template_id_input">
    <div class="form-group m-form__group">
        <label>
            <b>@lang('Mẫu áp dụng')</b><b class="text-danger">*</b>
        </label>
        <select class="form-control m-input width-select select2" name="staff_salary_template_id"
                id="staff_salary_template_id" style="width: calc(100% - 30px);"
                onchange="salaryTempalte.changeStaffSalaryTemplate(this)">
            <option value="">@lang('Chọn mẫu áp dụng')</option>
            @if(isset($optionStaffSalaryTemplate))
                @foreach($optionStaffSalaryTemplate as $key => $item)
                    @if( $item['staff_salary_template_id'] == ($staff_salary_template_id??0) )
                        <option value="{{ $item['staff_salary_template_id'] }}" selected="selected">
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