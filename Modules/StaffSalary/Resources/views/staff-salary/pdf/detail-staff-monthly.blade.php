<div class="row font_12">
    <div class="col-12">
        <!--begin:: Widgets/Support Cases-->
        <div class="m-portlet  m-portlet--full-height ">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h4 class="font_14" style="margin-left: 35px;text-decoration:underline;margin: -5px 0px 10px 35px;">@lang('Lương chính'):</h4>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row">
                    <table class="table m-table m-table--head-bg-default" id="tblSalary">
                        <thead class="bg">
                        <tr>
                            <th class="tr_thead_list"></th>
                            <th class="tr_thead_list text-left">@lang('Mỗi kỳ lương')</th>
                            <th class="tr_thead_list text-left">@lang('Số ngày làm')</th>
                            <th class="tr_thead_list text-left">@lang('Số ngày nghĩ có lương')</th>
                            <th class="tr_thead_list text-left">@lang('Số ngày nghĩ không có lương')</th>
                            <th class="tr_thead_list text-left">@lang('Thực nhận')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                @lang('Mức lương')
                            </td>
                            <td>
                                {{number_format($arrayStaffSalaryAttribute['salary_monthly']['staff_salary_attribute_value'] , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}} 
                            </td>
                            <td>
                                <?php 
                                    $total_working_day = $staffTimekeepingStaff['total_working_day'] + $staffTimekeepingStaff['total_day_saturday'] + $staffTimekeepingStaff['total_day_sunday']  + $staffTimekeepingStaff['total_day_holiday'];    
                                ?>
                                {{ $total_working_day }}
                            </td>
                           
                            <td>
                                <?php 
                                    $total_day_paid_leave = $staffTimekeepingStaff['total_day_paid_leave'] + $staffTimekeepingStaff['total_saturday_paid_leave'] + $staffTimekeepingStaff['total_sunday_paid_leave']  + $staffTimekeepingStaff['total_holiday_paid_leave'];
                                ?>
                                {{ $total_day_paid_leave }}
                            </td>
                            <td>
                                <?php
                                    $total_day_unpaid_leave = $staffTimekeepingStaff['total_day_unpaid_leave'] + $staffTimekeepingStaff['total_saturday_unpaid_leave'] + $staffTimekeepingStaff['total_sunday_unpaid_leave']  + $staffTimekeepingStaff['total_holiday_unpaid_leave'];
                                ?>
                                {{ $total_day_unpaid_leave }} 
                            </td>
                            <td>
                                {{number_format($staffInfoSalary['staff_salary_main'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}} 
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>

<div class="row font_12">
    <div class="col-12">
        <!--begin:: Widgets/Support Cases-->
        <div class="m-portlet  m-portlet--full-height ">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h4 class="font_14" style="margin-left: 35px;text-decoration:underline;margin: 10px 0px 10px 35px;">@lang('Lương làm thêm giờ'):</h4>
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
                                    <?php $totalOtSaturday =  $salary_overtime_saturday * $staffTimekeepingStaff['total_time_ot_saturday']; ?>
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
                                    <b>{{number_format($totalOt , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}  {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}} </b>
                                </td>
                            </tr>
                            @endif
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>
<div class="row font_12">
    <div class="col-12">
        <!--begin:: Widgets/Support Cases-->
        <div class="m-portlet  m-portlet--full-height ">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h4 class="font_14" style="margin-left: 35px;text-decoration:underline;margin: 10px 0px 10px 35px;">@lang('Các khoản phụ cấp'):</h4>
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
                                    <?php ?>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                                <td colspan="2">
                                    <b>@lang('Tổng tiền')</b>
                                </td>
                                <td >
                                    <b>{{number_format($totalAllowance ?? 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}} </b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>