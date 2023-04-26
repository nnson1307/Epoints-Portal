<?php
    $index = 1;
?>
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('Tên nhân viên')</th>
            <th class="tr_thead_list">@lang('Chi nhánh')</th>
            <th class="tr_thead_list">@lang('Phòng ban')</th>
            <th class="tr_thead_list text-center">@lang('Lương chính')</th>
            <th class="tr_thead_list text-center">@lang('Làm thêm')</th>
            <th class="tr_thead_list text-center">@lang('Phụ cấp')</th>
            {{-- <th class="tr_thead_list text-center">@lang('Thưởng')</th>
            <th class="tr_thead_list text-center">@lang('Phạt')</th> --}}
            <th class="tr_thead_list text-center">@lang('Thực nhận')</th>
        </tr>
        </thead>
        <tbody>
            @if(isset($LIST))
                @foreach ($LIST as $key => $item)
                    <?php
                        $staff_salary_main = $item['staff_salary_main']  ?? 0;
                        $staff_salary_allowance = $item['staff_salary_allowance']  ?? 0;
                        $staff_salary_overtime = $item['staff_salary_overtime']  ?? 0;
                        $staff_salary_bonus = $item['staff_salary_bonus']  ?? 0;
                        $staff_salary_minus = $item['staff_salary_minus']  ?? 0;
                    ?>
                    <tr>
                        <td>
                            {{ $index++ }}
                        </td>
                        <td>
                           <a href="{{route('staff-salary.detail-staff')}}?staff_id={{ $item['staff_id'] }}&staff_salary_id={{ $item['staff_salary_id'] }}">
                                {{ $item['staff_name'] }}
                           </a>
                        <td>
                            {{ $item['branch_name'] }}
                        </td>
                        <td>
                            {{ $item['department_name'] }}
                        </td>
                        <td class="text-center">
                            {{ number_format($staff_salary_main)}}
                        </td>
                        <td class="text-center">
                            {{ number_format($staff_salary_overtime)}}
                        </td>
                        <td class="text-center">
                            {{ number_format($staff_salary_allowance)}}
                        </td>
                        {{-- <td class="text-center">
                            {{ number_format($staff_salary_bonus)}}
                        </td>
                        <td class="text-center">
                            {{ number_format($staff_salary_minus)}}
                        </td> --}}
                        <td class="text-center">

                            {{ number_format($staff_salary_main + $staff_salary_allowance + $staff_salary_overtime + $staff_salary_bonus - $staff_salary_minus)}}
                        </td>
                        
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    
</div>