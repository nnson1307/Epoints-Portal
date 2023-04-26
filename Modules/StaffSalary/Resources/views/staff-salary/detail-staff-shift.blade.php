<div class="row">
    <div class="col-12">
        <!--begin:: Widgets/Support Cases-->
        <div class="m-portlet  m-portlet--full-height ">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            @lang('Lương chính')
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row">
                    <table class="table m-table m-table--head-bg-default" id="tblSalary">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list"></th>
                            <th class="tr_thead_list text-left">@lang('Mỗi ca làm')</th>
                            <th class="tr_thead_list text-left">@lang('Số ca làm')</th>
                            <th class="tr_thead_list text-left">@lang('Số ca nghĩ có lương')</th>
                            <th class="tr_thead_list text-left">@lang('Số ca nghĩ không lương')</th>
                            <th class="tr_thead_list text-left">@lang('Thực nhận')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                @lang('Ngày thường')
                            </td>
                            <td>
                                {{number_format($arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value'] , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_working_day'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_day_paid_leave'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_day_unpaid_leave'] }}
                            </td>
                            <td>
                                    <?php $totalWeekday =  ($staffTimekeepingStaff['total_working_day'] + $staffTimekeepingStaff['total_day_paid_leave']) * $arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value']; ?>
                                    {{number_format($totalWeekday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @lang('Thứ 7')
                            </td>
                            <td>
                                <?php 
                                    $salary_sarturday = 0;
                                    if($arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_type'] == 'percent'){
                                        $salary_sarturday = $arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value'] * $arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_value'] / 100;
                                    }else {
                                        $salary_sarturday = $arrayStaffSalaryAttribute['salary_sarturday']['staff_salary_attribute_value'];
                                    }
                                ?>
                                {{number_format($salary_sarturday, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_day_saturday'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_saturday_paid_leave'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_saturday_unpaid_leave'] }}
                            </td>
                            <td>
                                <?php $totalSaturday = $salary_sarturday * ($staffTimekeepingStaff['total_day_saturday'] + $staffTimekeepingStaff['total_saturday_paid_leave']); ?>
                                {{number_format($totalSaturday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @lang('Chủ nhật')
                            </td>
                            <td>
                                <?php 
                                    $salary_sunday = 0;
                                    if($arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_type'] == 'percent'){
                                        $salary_sunday = $arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value'] * $arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_value'] / 100;
                                    }else {
                                        $salary_sunday = $arrayStaffSalaryAttribute['salary_sunday']['staff_salary_attribute_value'];
                                    }
                                ?>
                                {{number_format($salary_sunday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_day_sunday'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_sunday_paid_leave'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_sunday_unpaid_leave'] }}
                            </td>
                            <td>
                                <?php $totalSunday = $salary_sunday * ($staffTimekeepingStaff['total_day_sunday'] + $staffTimekeepingStaff['total_sunday_paid_leave']); ?>
                                {{number_format($totalSunday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                               @lang('Ngày lễ')
                            </td>
                            <td>
                                <?php 
                                    $salary_holiday = 0;
                                    if($arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_type'] == 'percent'){
                                        $salary_holiday = $arrayStaffSalaryAttribute['salary_weekday']['staff_salary_attribute_value'] * $arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_value'] / 100;
                                    }else {
                                        $salary_holiday = $arrayStaffSalaryAttribute['salary_holiday']['staff_salary_attribute_value'];
                                    }
                                ?>
                                {{number_format($salary_holiday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                           
                            <td>
                                {{ $staffTimekeepingStaff['total_day_holiday'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_holiday_paid_leave'] }}
                            </td>
                            <td>
                                {{ $staffTimekeepingStaff['total_holiday_unpaid_leave'] }}
                            </td>
                            <td>
                                <?php $totalHoliday = $salary_holiday *  ($staffTimekeepingStaff['total_day_holiday']); ?>
                                {{number_format($totalHoliday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <b>@lang('Tổng tiền')</b>
                            </td>
                           
                            <td>
                                <?php $total =  $totalWeekday + $totalSaturday + $totalSunday + $totalHoliday; ?>
                                <b>{{number_format($total , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}</b>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-12">
        <!--begin:: Widgets/Support Cases-->
        <div class="m-portlet  m-portlet--full-height ">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            @lang('Lương làm thêm giờ')
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row">
                    <table class="table m-table m-table--head-bg-default" id="tblSalary">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list"></th> 
                            <th class="tr_thead_list text-left">@lang('Mỗi giờ làm thêm')</th>
                            <th class="tr_thead_list text-left">@lang('Số giờ làm thêm')</th>
                            <th class="tr_thead_list text-left">@lang('Thực nhận')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($staffSalaryOvertime != null)
                            <tr>
                                <td>
                                    @lang('Ngày thường')
                                </td>
                                <td>
                                    {{number_format($staffSalaryOvertime['staff_salary_overtime_weekday'] , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                                <td>
                                    {{ $staffTimekeepingStaff['total_working_ot_time'] ?? 0 }}
                                    
                                </td>
                                <td>
                                    <?php $totalOtWeekday=  $staffSalaryOvertime['staff_salary_overtime_weekday'] * $staffTimekeepingStaff['total_working_ot_time']; ?>
                                    {{number_format($totalOtWeekday ?? 0 , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   @lang('Thứ 7')
                                </td>
                                <td>
                                    <?php 
                                        $salary_overtime_saturday = 0;
                                        if($staffSalaryOvertime['staff_salary_overtime_saturday_type'] == 'percent'){
                                            $salary_overtime_saturday = $staffSalaryOvertime['staff_salary_overtime_weekday'] * $staffSalaryOvertime['staff_salary_overtime_saturday'] / 100;
                                        }else {
                                            $salary_overtime_saturday = $staffSalaryOvertime['staff_salary_overtime_saturday'];
                                        }
                                    ?>
                                    {{number_format($salary_overtime_saturday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                                <td>
                                   
                                    {{ $staffTimekeepingStaff['total_time_ot_saturday'] ?? 0 }}
                                   
                                </td>
                                <td>
                                    <?php $totalOtSaturday =  $salary_overtime_saturday * $staffTimekeepingStaff['total_time_ot_saturday'] ; ?>
                                    {{number_format($totalOtSaturday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @lang('Chủ nhật')
                                </td>
                                <td>
                                    <?php 
                                        $salary_overtime_sunday = 0;
                                        if($staffSalaryOvertime['staff_salary_overtime_sunday_type'] == 'percent'){
                                            $salary_overtime_sunday = $staffSalaryOvertime['staff_salary_overtime_weekday'] * $staffSalaryOvertime['staff_salary_overtime_sunday'] / 100;
                                        }else {
                                            $salary_overtime_sunday = $staffSalaryOvertime['staff_salary_overtime_sunday'];
                                        }
                                    ?>
                                    {{number_format($salary_overtime_sunday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                                <td>
                                   
                                    {{ $staffTimekeepingStaff['total_time_ot_sunday'] ?? 0 }}
                                   
                                </td>
                              
                                <td>
                                    <?php $totalOtSunday =  $salary_overtime_sunday * $staffTimekeepingStaff['total_time_ot_sunday']; ?>
                                    {{number_format($totalOtSunday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @lang('Ngày lễ')
                                </td>
                                <td>
                                    <?php 
                                    $salary_overtime_holiday = 0;
                                    if($staffSalaryOvertime['staff_salary_overtime_holiday_type'] == 'percent'){
                                        $salary_overtime_holiday = $staffSalaryOvertime['staff_salary_overtime_weekday'] * $staffSalaryOvertime['staff_salary_overtime_holiday'] / 100;
                                    }else {
                                        $salary_overtime_holiday = $staffSalaryOvertime['staff_salary_overtime_holiday'];
                                    }
                                ?>
                                    {{number_format($salary_overtime_holiday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                                <td>
                    
                                    {{ $staffTimekeepingStaff['total_time_ot_holiday'] ?? 0 }}
                                  
                                </td>
                                <td>
                                    <?php $totalOtHoliday =  $salary_overtime_holiday * $staffTimekeepingStaff['total_time_ot_holiday']; ?>
                                    {{number_format($totalOtHoliday , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <b>@lang('Tổng tiền')</b>
                                </td>
                               
                                <td>
                                    <?php $totalOt =  $totalOtWeekday + $totalOtSaturday + $totalOtSunday + $totalOtHoliday; ?>
                                    <b>{{number_format($totalOt , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}  {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}</b>
                                </td>
                            </tr>
                            @endif
                       
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-12">
        <!--begin:: Widgets/Support Cases-->
        <div class="m-portlet  m-portlet--full-height ">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            @lang('Các khoản phụ cấp')
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row">
                    <table class="table m-table m-table--head-bg-default" id="tblSalary">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list text-left">@lang('Tên phụ cấp')</th>
                            <th class="tr_thead_list text-left">@lang('Mức áp dụng')</th>
                            <th class="tr_thead_list text-left">@lang('Thực nhận')</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $totalAllowance = 0; ?>
                            @if(count($arraySalaryAllowance) > 0)
                            @foreach($arraySalaryAllowance as $value => $item)
                            <tr>
                                <td class="text-left">
                                    {{ $item['salary_allowance_name'] }}
                                </td>
                                <td class="text-left">
                                    {{number_format($item['staff_salary_allowance_num'] ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                                </td>
                                <td class="text-left">
                                    <?php 
                                        $allowance = 0; 
                                        $allowance = $item['staff_salary_allowance_num'];
                                        $totalAllowance = $totalAllowance + $allowance;
                                    ?>
                                     {{number_format($allowance ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
                
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                                <td colspan="2">
                                    <b>@lang('Tổng tiền')</b>
                                </td>
                                <td >
                                    <b>{{number_format($totalAllowance ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>


