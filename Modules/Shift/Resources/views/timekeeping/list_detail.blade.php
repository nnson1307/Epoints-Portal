<div class="table-content m--padding-top-10">
    <div class="table-responsive table-timekeeping">
        <table class="table table-bordered">
            <thead class="bg">
            <tr>
                <th class="tr_thead_list" style="width: 200px; max-width: 230px;">
                    @lang('Thứ hai')
                </th>
                <th class="tr_thead_list" style="width: 200px; max-width: 230px;">
                    @lang('Thứ ba')
                </th>
                <th class="tr_thead_list" style="width: 200px; max-width: 230px;">
                    @lang('Thứ tư')
                </th>
                <th class="tr_thead_list" style="width: 200px; max-width: 230px;">
                    @lang('Thứ năm')
                </th>
                <th class="tr_thead_list" style="width: 200px; max-width: 230px;">
                    @lang('Thứ sáu')
                </th>
                <th class="tr_thead_list" style="width: 200px; max-width: 230px;">
                    @lang('Thứ bảy')
                </th>
                <th class="tr_thead_list" style="width: 200px; max-width: 230px;">
                    @lang('Chủ nhật')
                </th>
            </tr>
            </thead>
            <tbody>
                @if(isset($arrayMonth) && count($arrayMonth) > 0)
                    @foreach($arrayMonth as $value => $item)
                        <tr>
                            <td class="text-right" style="height: 20px;border-bottom: none;">
                                @if(isset($item['monday']['day']))
                                <b> {{ $item['monday']['day'] }}</b>
                                @endif
                            </td>
                            <td class="text-right" style="height: 20px;border-bottom: none;">
                                @if(isset($item['tuesday']['day']))
                                <b> {{ $item['tuesday']['day'] }}</b>
                                @endif
                            </td>
                            <td class="text-right" style="height: 20px;border-bottom: none;">
                                @if(isset($item['wednesday']['day']))
                                    <b>{{ $item['wednesday']['day'] }}</b>
                                @endif
                            </td>
                            <td class="text-right" style="height: 20px;border-bottom: none;">
                                @if(isset($item['thursday']['day']))
                                    <b>{{ $item['thursday']['day'] }}</b>
                                @endif
                            </td>
                            <td class="text-right" style="height: 20px;border-bottom: none;">
                                @if(isset($item['friday']['day']))
                                    <b>{{ $item['friday']['day'] }}</b>
                                @endif
                            </td>
                            <td class="text-right" style="height: 20px;border-bottom: none;">
                                @if(isset($item['saturday']['day']))
                                    <b>{{ $item['saturday']['day'] }}</b>
                                @endif
                            </td>
                            <td class="text-right" style="height: 20px;border-bottom: none;">
                                @if(isset($item['sunday']['day']))
                                    <b>{{ $item['sunday']['day'] }}</b>
                                @endif
                            </td>
                        </tr>
                        <tr style="text-align: center;">
                            {{-- Thứ 2 --}}
                            @if(isset($item['monday']['data']) && count($item['monday']['data']) > 0)
                                @if(count($item['monday']['data']) == 1)
                                    <td style="background-color: {{ $item['monday']['data'][0]['background'] }}; border-top: none; {{$item['monday']['data'][0]['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['monday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                    <p class="text-left"><b>{{ $item['monday']['data'][0]['shift_name'] }}</b></p>
                                                </a> 
                                            </div>
                                            <div class="col-3" style="padding-right: 0px;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                  
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['monday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                        @endif
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalShift('{{$item['monday']['data'][0]['staff_id']}}', '{{$item['monday']['data'][0]['working_day']}}', 'list')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif
                                                        @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="WorkChild.showPopup(null, '{{$item['monday']['data'][0]['staff_id']}}', '5', '{{$item['monday']['data'][0]['working_day']}}', '{{$item['monday']['data'][0]['working_time']}}', '{{$item['monday']['data'][0]['working_end_day']}}', '{{$item['monday']['data'][0]['working_end_time']}}', 'shift')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                            </a>
                                                        @endif
                                                        @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($item['monday']['data'][0]['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $item['monday']['data'][0]['is_close'] == 0)
                                                                @if (($item['monday']['data'][0]['is_check_in'] == 0 || $item['monday']['data'][0]['is_check_out'] == 0) && $item['monday']['data'][0]['is_deducted'] === null)
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalTimeAttendance('{{$item['monday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                     </a>
                                                                @endif
                                                          @endif
                                                          @if (($item['monday']['data'][0]['is_close'] == 0 || $item['monday']['data'][0]['is_close'] == null) && ($item['monday']['data'][0]['is_check_in'] == 0 || $item['monday']['data'][0]['is_check_in'] == null) && ($item['monday']['data'][0]['is_check_out'] == 0 || $item['monday']['data'][0]['is_check_out'] == null))
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['monday']['data'][0]['is_deducted'] === null || $item['monday']['data'][0]['is_deducted'] === 1))
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.paidOrUnPaidLeave('{{$item['monday']['data'][0]['time_working_staff_id']}}', 'paid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương') 
                                                                    </a>    
                                                                @endif
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['monday']['data'][0]['is_deducted'] === null || $item['monday']['data'][0]['is_deducted'] == 0))
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                           onclick="index.paidOrUnPaidLeave('{{$item['monday']['data'][0]['time_working_staff_id']}}', 'unpaid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                    </a>
                                                                @endif
                                                            @endif
                                                          
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-12">
                                                <div style="text-align: center;">
                                                    (
                                                    @if($item['monday']['data'][0]['is_check_in'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['monday']['data'][0]['check_in_time'])->format('H:s') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>
                                                    -
                                                    @if($item['monday']['data'][0]['is_check_out'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['monday']['data'][0]['check_out_time'])->format('H:s') }}
                                                        
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>)
                                                </div>
                                            </div>
                                       </div>
                                       
                                       <div class="row" style="padding-top: 10px;">
                                            <div class="col-lg-12 font-weight-bold" style="text-align: left;">
                                                <p><b>{{$item['monday']['data'][0]['branch_name']}}</b></p>
                                            </div>
                                           
                                            <div class="col-12" >
                                                @if ($item['monday']['data'][0]['time_off_days_id'] != null && $item['monday']['data'][0]['time_off_days_id'] != 0)
                                                @if ($item['monday']['data'][0]['is_approve_time_off'] === 1)
                                                    <span>
                                                        <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                    </span>
                                                @elseif ($item['monday']['data'][0]['is_approve_time_off'] === 0)
                                                    <span>
                                                        <i class="la la-times-circle-o" style="color : #ed2e24" title="{{ __('Đơn phép không được chấp nhận') }}"></i>
                                                    </span>
                                                @else
                                                    <span>
                                                        <i class="la la-clock-o" style="color : #ffb927" title="{{ __('Đơn phép đang chờ duyệt') }}"></i>
                                                    </span>
                                                @endif
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td style="border-top: none; background: #a0d4d4;">
                                        <div class="form-group m--padding-top-30">
                                            <a href="javascript:void(0)" onclick="index.showModalMyShift('{{$item['monday']['data'][0]['staff_id']}}', '{{$item['monday']['data'][0]['working_day']}}')">
                                                <p><b>{{count($item['monday']['data'])}} @lang('Ca')</b></p>
                                            </a>
                                            @foreach($item['monday']['data'] as $value => $objShift)
                                                <span class="line_note_shift" style="background: {{ $objShift['background'] }}; {{$objShift['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}"></span> 
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            @else
                                <td style="border-top: none;"></td>
                            @endif
                            {{-- Thứ 3 --}}
                            @if(isset($item['tuesday']['data']) && count($item['tuesday']['data']) > 0)
                                @if(count($item['tuesday']['data']) == 1)
                                    <td style="background-color: {{ $item['tuesday']['data'][0]['background'] }}; border-top: none; {{$item['tuesday']['data'][0]['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['tuesday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                    <p class="text-left"><b>{{ $item['tuesday']['data'][0]['shift_name'] }}</b></p>
                                                </a>
                                            </div>
                                            <div class="col-3" style="padding-right: 0px;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                  
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['tuesday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                        @endif
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalShift('{{$item['tuesday']['data'][0]['staff_id']}}', '{{$item['tuesday']['data'][0]['working_day']}}', 'list')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif
                                                         @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($item['tuesday']['data'][0]['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $item['tuesday']['data'][0]['is_close'] == 0)
                                                                @if (($item['tuesday']['data'][0]['is_check_in'] == 0 || $item['tuesday']['data'][0]['is_check_out'] == 0) && $item['tuesday']['data'][0]['is_deducted'] === null)
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalTimeAttendance('{{$item['tuesday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                     </a>
                                                                @endif
                                                          @endif
                                                          @if (($item['tuesday']['data'][0]['is_close'] == 0 || $item['tuesday']['data'][0]['is_close'] == null) && ($item['tuesday']['data'][0]['is_check_in'] == 0 || $item['tuesday']['data'][0]['is_check_in'] == null) && ($item['tuesday']['data'][0]['is_check_out'] == 0 || $item['tuesday']['data'][0]['is_check_out'] == null))
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['tuesday']['data'][0]['is_deducted'] === null || $item['tuesday']['data'][0]['is_deducted'] === 1))
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.paidOrUnPaidLeave('{{$item['tuesday']['data'][0]['time_working_staff_id']}}', 'paid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương') 
                                                                    </a>    
                                                                @endif
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['tuesday']['data'][0]['is_deducted'] === null || $item['tuesday']['data'][0]['is_deducted'] === 0))
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                           onclick="index.paidOrUnPaidLeave('{{$item['tuesday']['data'][0]['time_working_staff_id']}}', 'unpaid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="WorkChild.showPopup(null, '{{$item['tuesday']['data'][0]['staff_id']}}', '5', '{{$item['tuesday']['data'][0]['working_day']}}', '{{$item['tuesday']['data'][0]['working_time']}}', '{{$item['tuesday']['data'][0]['working_end_day']}}', '{{$item['tuesday']['data'][0]['working_end_time']}}', 'shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                                </a>
                                                            @endif
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-12">
                                                    <div style="text-align: center;">
                                                        (
                                                        @if($item['tuesday']['data'][0]['is_check_in'] == 1)
                                                            {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['tuesday']['data'][0]['check_in_time'])->format('H:s') }}
                                                        @else
                                                            --:--
                                                        @endif
                                                        <i class="la la-mobile"></i>
                                                        -
                                                        @if($item['tuesday']['data'][0]['is_check_out'] == 1)
                                                            {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['tuesday']['data'][0]['check_out_time'])->format('H:s') }}
                                                            
                                                        @else
                                                            --:--
                                                        @endif
                                                        <i class="la la-mobile"></i>)
                                                    </div>
                                                </div>
                                           </div>
                                           <div class="row" style="padding-top: 10px;">
                                                <div class="col-12" >
                                                    <p><b>{{$item['tuesday']['data'][0]['branch_name']}}</b></p>
                                                </div>
                                                <div class="col-12" >
                                                    @if ($item['tuesday']['data'][0]['time_off_days_id'] != null && $item['tuesday']['data'][0]['time_off_days_id'] != 0)
                                                    @if ($item['tuesday']['data'][0]['is_approve_time_off'] === 1)
                                                        <span>
                                                            <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                        </span>
                                                    @elseif ($item['tuesday']['data'][0]['is_approve_time_off'] === 0)
                                                        <span>
                                                            <i class="la la-times-circle-o" style="color : #ed2e24" title="{{ __('Đơn phép không được chấp nhận') }}"></i>
                                                        </span>
                                                    @else
                                                        <span>
                                                            <i class="la la-clock-o" style="color : #ffb927" title="{{ __('Đơn phép đang chờ duyệt') }}"></i>
                                                        </span>
                                                    @endif
                                                @endif
                                                </div>
                                            </div>
                                    </td>
                                @else
                                    <td style="border-top: none; background: #a0d4d4;">
                                        <div class="form-group m--padding-top-30">
                                            <a href="javascript:void(0)" onclick="index.showModalMyShift('{{$item['tuesday']['data'][0]['staff_id']}}', '{{$item['tuesday']['data'][0]['working_day']}}')">
                                                <p><b>{{count($item['tuesday']['data'])}} @lang('Ca')</b></p>
                                            </a>
                                          
                                            @foreach($item['tuesday']['data'] as $value => $objShift)
                                                <span class="line_note_shift" style="background: {{ $objShift['background'] }}; {{$objShift['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}"></span> 
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            @else
                                <td style="border-top: none;"></td>
                            @endif

                            {{-- Thứ 4 --}}
                            @if(isset($item['wednesday']['data']) && count($item['wednesday']['data']) > 0)
                                @if(count($item['wednesday']['data']) == 1)
                                    <td style="background-color: {{ $item['wednesday']['data'][0]['background'] }}; border-top: none; {{$item['wednesday']['data'][0]['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['wednesday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                    <p class="text-left"><b>{{ $item['wednesday']['data'][0]['shift_name'] }}</b></p>
                                                </a>
                                            </div>
                                            <div class="col-3" style="padding-right: 0px;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                  
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['wednesday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                        @endif
                                                             
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalShift('{{$item['wednesday']['data'][0]['staff_id']}}', '{{$item['wednesday']['data'][0]['working_day']}}', 'list')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif
                                                         @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($item['wednesday']['data'][0]['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $item['wednesday']['data'][0]['is_close'] == 0)
                                                                @if (($item['wednesday']['data'][0]['is_check_in'] == 0 || $item['wednesday']['data'][0]['is_check_out'] == 0) && $item['wednesday']['data'][0]['is_deducted'] === null)
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalTimeAttendance('{{$item['wednesday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                     </a>
                                                                @endif
                                                          @endif
                                                          @if (($item['wednesday']['data'][0]['is_close'] == 0 || $item['wednesday']['data'][0]['is_close'] == null) && ($item['wednesday']['data'][0]['is_check_in'] == 0 || $item['wednesday']['data'][0]['is_check_in'] == null) && ($item['wednesday']['data'][0]['is_check_out'] == 0 || $item['wednesday']['data'][0]['is_check_out'] == null))
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['wednesday']['data'][0]['is_deducted'] === null || $item['wednesday']['data'][0]['is_deducted'] === 1))
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.paidOrUnPaidLeave('{{$item['wednesday']['data'][0]['time_working_staff_id']}}', 'paid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương') 
                                                                    </a>    
                                                                @endif
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['wednesday']['data'][0]['is_deducted'] === null || $item['wednesday']['data'][0]['is_deducted'] === 0))
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                           onclick="index.paidOrUnPaidLeave('{{$item['wednesday']['data'][0]['time_working_staff_id']}}', 'unpaid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="WorkChild.showPopup(null, '{{$item['wednesday']['data'][0]['staff_id']}}', '5', '{{$item['wednesday']['data'][0]['working_day']}}', '{{$item['wednesday']['data'][0]['working_time']}}', '{{$item['wednesday']['data'][0]['working_end_day']}}', '{{$item['wednesday']['data'][0]['working_end_time']}}', 'shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                                </a>
                                                            @endif
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-12">
                                                <div style="text-align: center;">
                                                    (
                                                    @if($item['wednesday']['data'][0]['is_check_in'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['wednesday']['data'][0]['check_in_time'])->format('H:s') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>
                                                    -
                                                    @if($item['wednesday']['data'][0]['is_check_out'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['wednesday']['data'][0]['check_out_time'])->format('H:s') }}
                                                        
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>)
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row" style="padding-top: 10px;">
                                            <div class="col-12" >
                                                <p><b>{{$item['wednesday']['data'][0]['branch_name']}}</b></p>
                                            </div>
                                            <div class="col-12" >
                                                @if ($item['wednesday']['data'][0]['time_off_days_id'] != null && $item['wednesday']['data'][0]['time_off_days_id'] != 0)
                                                @if ($item['wednesday']['data'][0]['is_approve_time_off'] === 1)
                                                    <span>
                                                        <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                    </span>
                                                @elseif ($item['wednesday']['data'][0]['is_approve_time_off'] === 0)
                                                    <span>
                                                        <i class="la la-times-circle-o" style="color : #ed2e24" title="{{ __('Đơn phép không được chấp nhận') }}"></i>
                                                    </span>
                                                @else
                                                    <span>
                                                        <i class="la la-clock-o" style="color : #ffb927" title="{{ __('Đơn phép đang chờ duyệt') }}"></i>
                                                    </span>
                                                @endif
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td style="border-top: none; background: #a0d4d4;">
                                        <div class="form-group m--padding-top-30">
                                            <a href="javascript:void(0)" onclick="index.showModalMyShift('{{ $item['wednesday']['data'][0]['staff_id'] ?? 0 }}', '{{ $item['wednesday']['data'][0]['working_day']?? 0 }}')">
                                                <p><b>{{count($item['wednesday']['data'])}} @lang('Ca')</b></p>
                                            </a>
                                            @foreach($item['wednesday']['data'] as $value => $objShift)
                                                 <span class="line_note_shift" style="background: {{ $objShift['background'] }}; {{$objShift['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}"></span> 
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            @else
                                <td style="border-top: none;"></td>
                            @endif

                            {{-- Thứ 5 --}}
                            @if(isset($item['thursday']['data']) && count($item['thursday']['data']) > 0)
                                @if(count($item['thursday']['data']) == 1)
                                    <td style="background-color: {{ $item['thursday']['data'][0]['background'] }};border-top: none; {{$item['thursday']['data'][0]['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['thursday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                    <p class="text-left"><b>{{ $item['thursday']['data'][0]['shift_name'] }}</b></p>
                                                </a>
                                            </div>
                                            <div class="col-3" style="padding-right: 0px;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                  
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['thursday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                        @endif   
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalShift('{{$item['thursday']['data'][0]['staff_id']}}', '{{$item['thursday']['data'][0]['working_day']}}', 'list')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif
                                                         @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($item['thursday']['data'][0]['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $item['thursday']['data'][0]['is_close'] == 0)
                                                                @if (($item['thursday']['data'][0]['is_check_in'] == 0 || $item['thursday']['data'][0]['is_check_out'] == 0) && $item['thursday']['data'][0]['is_deducted'] === null)
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalTimeAttendance('{{$item['thursday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                     </a>
                                                                @endif
                                                          @endif
                                                          @if (($item['thursday']['data'][0]['is_close'] == 0 || $item['thursday']['data'][0]['is_close'] == null) && ($item['thursday']['data'][0]['is_check_in'] == 0 || $item['thursday']['data'][0]['is_check_in'] == null) && ($item['thursday']['data'][0]['is_check_out'] == 0 || $item['thursday']['data'][0]['is_check_out'] == null))
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['thursday']['data'][0]['is_deducted'] === null || $item['thursday']['data'][0]['is_deducted'] === 1))
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.paidOrUnPaidLeave('{{$item['thursday']['data'][0]['time_working_staff_id']}}', 'paid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương') 
                                                                    </a>    
                                                                @endif
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['thursday']['data'][0]['is_deducted'] === null || $item['thursday']['data'][0]['is_deducted'] === 0))
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                           onclick="index.paidOrUnPaidLeave('{{$item['thursday']['data'][0]['time_working_staff_id']}}', 'unpaid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="WorkChild.showPopup(null, '{{$item['thursday']['data'][0]['staff_id']}}', '5', '{{$item['thursday']['data'][0]['working_day']}}', '{{$item['thursday']['data'][0]['working_time']}}', '{{$item['thursday']['data'][0]['working_end_day']}}', '{{$item['thursday']['data'][0]['working_end_time']}}', 'shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                                </a>
                                                            @endif
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-12">
                                                <div style="text-align: center;">
                                                    (
                                                    @if($item['thursday']['data'][0]['is_check_in'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['thursday']['data'][0]['check_in_time'])->format('H:s') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>
                                                    -
                                                    @if($item['thursday']['data'][0]['is_check_out'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['thursday']['data'][0]['check_out_time'])->format('H:s') }}
                                                        
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>)
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row" style="padding-top: 10px;">
                                            <div class="col-12" >
                                                <p><b>{{$item['thursday']['data'][0]['branch_name']}}</b></p>
                                            </div>
                                            <div class="col-12" >
                                                @if ($item['thursday']['data'][0]['time_off_days_id'] != null && $item['thursday']['data'][0]['time_off_days_id'] != 0)
                                                @if ($item['thursday']['data'][0]['is_approve_time_off'] === 1)
                                                    <span>
                                                        <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                    </span>
                                                @elseif ($item['thursday']['data'][0]['is_approve_time_off'] === 0)
                                                    <span>
                                                        <i class="la la-times-circle-o" style="color : #ed2e24" title="{{ __('Đơn phép không được chấp nhận') }}"></i>
                                                    </span>
                                                @else
                                                    <span>
                                                        <i class="la la-clock-o" style="color : #ffb927" title="{{ __('Đơn phép đang chờ duyệt') }}"></i>
                                                    </span>
                                                @endif
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td style="border-top: none; background: #a0d4d4;">
                                        <div class="form-group m--padding-top-30">
                                            <a href="javascript:void(0)" onclick="index.showModalMyShift('{{$item['thursday']['data'][0]['staff_id']}}', '{{$item['thursday']['data'][0]['working_day']}}')">
                                                <p><b>{{count($item['thursday']['data'])}} @lang('Ca')</b></p>
                                            </a>
                                            
                                            @foreach($item['thursday']['data'] as $value => $objShift)
                                                <span class="line_note_shift" style="background: {{ $objShift['background'] }}; {{$objShift['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}"></span> 
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            @else
                                <td style="border-top: none;"></td>
                            @endif

                            {{-- Thứ 6 --}}
                            @if(isset($item['friday']['data']) && count($item['friday']['data']) > 0)
                                @if(count($item['friday']['data']) == 1)
                                    <td style="background-color: {{ $item['friday']['data'][0]['background'] }};border-top: none; {{$item['friday']['data'][0]['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['friday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                    <p class="text-left"><b>{{ $item['friday']['data'][0]['shift_name'] }}</b></p>
                                                </a>
                                            </div>
                                            <div class="col-3" style="padding-right: 0px;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                  
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['friday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                        @endif  
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalShift('{{$item['friday']['data'][0]['staff_id']}}', '{{$item['friday']['data'][0]['working_day']}}', 'list')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif
                                                         @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($item['friday']['data'][0]['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $item['friday']['data'][0]['is_close'] == 0)
                                                                @if (($item['friday']['data'][0]['is_check_in'] == 0 || $item['friday']['data'][0]['is_check_out'] == 0) && $item['friday']['data'][0]['is_deducted'] === null)
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalTimeAttendance('{{$item['friday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                     </a>
                                                                @endif
                                                          @endif
                                                          @if (($item['friday']['data'][0]['is_close'] == 0 || $item['friday']['data'][0]['is_close'] == null) && ($item['friday']['data'][0]['is_check_in'] == 0 || $item['friday']['data'][0]['is_check_in'] == null) && ($item['friday']['data'][0]['is_check_out'] == 0 || $item['friday']['data'][0]['is_check_out'] == null))
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['friday']['data'][0]['is_deducted'] === null || $item['friday']['data'][0]['is_deducted'] === 1))
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.paidOrUnPaidLeave('{{$item['friday']['data'][0]['time_working_staff_id']}}', 'paid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương') 
                                                                    </a>    
                                                                @endif
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['friday']['data'][0]['is_deducted'] === null || $item['friday']['data'][0]['is_deducted'] === 0))
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                           onclick="index.paidOrUnPaidLeave('{{$item['friday']['data'][0]['time_working_staff_id']}}', 'unpaid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="WorkChild.showPopup(null, '{{$item['friday']['data'][0]['staff_id']}}', '5', '{{$item['friday']['data'][0]['working_day']}}', '{{$item['friday']['data'][0]['working_time']}}', '{{$item['friday']['data'][0]['working_end_day']}}', '{{$item['friday']['data'][0]['working_end_time']}}', 'shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                                </a>
                                                            @endif
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-12">
                                                <div style="text-align: center;">
                                                    (
                                                    @if($item['friday']['data'][0]['is_check_in'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['friday']['data'][0]['check_in_time'])->format('H:s') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>
                                                    -
                                                    @if($item['friday']['data'][0]['is_check_out'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['friday']['data'][0]['check_out_time'])->format('H:s') }}
                                                        
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>)
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row" style="padding-top: 10px;">
                                            <div class="col-12" >
                                                <p><b>{{$item['friday']['data'][0]['branch_name']}}</b></p>
                                            </div>
                                            <div class="col-12" >
                                                @if ($item['friday']['data'][0]['time_off_days_id'] != null && $item['friday']['data'][0]['time_off_days_id'] != 0)
                                                @if ($item['friday']['data'][0]['is_approve_time_off'] === 1)
                                                    <span>
                                                        <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                    </span>
                                                @elseif ($item['friday']['data'][0]['is_approve_time_off'] === 0)
                                                    <span>
                                                        <i class="la la-times-circle-o" style="color : #ed2e24" title="{{ __('Đơn phép không được chấp nhận') }}"></i>
                                                    </span>
                                                @else
                                                    <span>
                                                        <i class="la la-clock-o" style="color : #ffb927" title="{{ __('Đơn phép đang chờ duyệt') }}"></i>
                                                    </span>
                                                @endif
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td style="border-top: none; background: #a0d4d4;">
                                        <div class="form-group m--padding-top-30">
                                            <a href="javascript:void(0)" onclick="index.showModalMyShift('{{$item['friday']['data'][0]['staff_id']}}', '{{$item['friday']['data'][0]['working_day']}}')">
                                                <p><b>{{count($item['friday']['data'])}} @lang('Ca')</b></p>
                                            </a>
                                            
                                            @foreach($item['friday']['data'] as $value => $objShift)
                                                <span class="line_note_shift" style="background: {{ $objShift['background'] }}; {{$objShift['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}"></span> 
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            @else
                                <td style="border-top: none;"></td>
                            @endif

                            {{-- Thứ 7 --}}
                            @if(isset($item['saturday']['data']) && count($item['saturday']['data']) > 0)
                                @if(count($item['saturday']['data']) == 1)
                                    <td style="background-color: {{ $item['saturday']['data'][0]['background'] }};border-top: none; {{$item['saturday']['data'][0]['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['saturday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                    <p class="text-left"><b>{{ $item['saturday']['data'][0]['shift_name'] }}</b></p>
                                                </a>
                                            </div>
                                            <div class="col-3" style="padding-right: 0px;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                  
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['saturday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                        @endif
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalShift('{{$item['saturday']['data'][0]['staff_id']}}', '{{$item['saturday']['data'][0]['working_day']}}', 'list')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif
                                                         @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($item['saturday']['data'][0]['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $item['saturday']['data'][0]['is_close'] == 0)
                                                                @if (($item['saturday']['data'][0]['is_check_in'] == 0 || $item['saturday']['data'][0]['is_check_out'] == 0) && $item['saturday']['data'][0]['is_deducted'] === null)
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalTimeAttendance('{{$item['saturday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                     </a>
                                                                @endif
                                                          @endif
                                                          @if (($item['saturday']['data'][0]['is_close'] == 0 || $item['saturday']['data'][0]['is_close'] == null) && ($item['saturday']['data'][0]['is_check_in'] == 0 || $item['saturday']['data'][0]['is_check_in'] == null) && ($item['saturday']['data'][0]['is_check_out'] == 0 || $item['saturday']['data'][0]['is_check_out'] == null))
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['saturday']['data'][0]['is_deducted'] === null || $item['saturday']['data'][0]['is_deducted'] === 1))
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.paidOrUnPaidLeave('{{$item['saturday']['data'][0]['time_working_staff_id']}}', 'paid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương') 
                                                                    </a>    
                                                                @endif
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['saturday']['data'][0]['is_deducted'] === null || $item['saturday']['data'][0]['is_deducted'] === 0))
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                           onclick="index.paidOrUnPaidLeave('{{$item['saturday']['data'][0]['time_working_staff_id']}}', 'unpaid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="WorkChild.showPopup(null, '{{$item['saturday']['data'][0]['staff_id']}}', '5', '{{$item['saturday']['data'][0]['working_day']}}', '{{$item['saturday']['data'][0]['working_time']}}', '{{$item['saturday']['data'][0]['working_end_day']}}', '{{$item['saturday']['data'][0]['working_end_time']}}', 'shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                                </a>
                                                            @endif
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-12">
                                                <div style="text-align: center;">
                                                    (
                                                    @if($item['saturday']['data'][0]['is_check_in'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['saturday']['data'][0]['check_in_time'])->format('H:s') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>
                                                    -
                                                    @if($item['saturday']['data'][0]['is_check_out'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['saturday']['data'][0]['check_out_time'])->format('H:s') }}
                                                        
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>)
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row" style="padding-top: 10px;">
                                            <div class="col-12" >
                                                <p><b>{{$item['saturday']['data'][0]['branch_name']}}</b></p>
                                            </div>
                                            <div class="col-12" >
                                                @if ($item['saturday']['data'][0]['time_off_days_id'] != null && $item['saturday']['data'][0]['time_off_days_id'] != 0)
                                                @if ($item['saturday']['data'][0]['is_approve_time_off'] === 1)
                                                    <span>
                                                        <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                    </span>
                                                @elseif ($item['saturday']['data'][0]['is_approve_time_off'] === 0)
                                                    <span>
                                                        <i class="la la-times-circle-o" style="color : #ed2e24" title="{{ __('Đơn phép không được chấp nhận') }}"></i>
                                                    </span>
                                                @else
                                                    <span>
                                                        <i class="la la-clock-o" style="color : #ffb927" title="{{ __('Đơn phép đang chờ duyệt') }}"></i>
                                                    </span>
                                                @endif
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td style="border-top: none; background: #a0d4d4;">
                                        <div class="form-group m--padding-top-30">
                                            <a href="javascript:void(0)" onclick="index.showModalMyShift('{{$item['saturday']['data'][0]['staff_id']}}', '{{$item['saturday']['data'][0]['working_day']}}')">
                                                <p><b>{{count($item['saturday']['data'])}} @lang('Ca')</b></p>
                                            </a>
                                            @foreach($item['saturday']['data'] as $value => $objShift)
                                                <span class="line_note_shift" style="background: {{ $objShift['background'] }}; {{$objShift['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}"></span> 
                                            @endforeach
                                        </div>
                                    </td>
                                @endif
                            @else
                                <td style="border-top: none;"></td>
                            @endif

                            {{-- Chủ nhật --}}
                            @if(isset($item['sunday']['data']) && count($item['sunday']['data']) > 0)
                                @if(count($item['sunday']['data']) == 1)
                                    <td style="background-color: {{ $item['sunday']['data'][0]['background'] }};border-top: none; {{$item['sunday']['data'][0]['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}">
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['sunday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                    <p class="text-left"><b>{{ $item['sunday']['data'][0]['shift_name'] }}</b></p>
                                                </a>
                                            </div>
                                            <div class="col-3" style="padding-right: 0px;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                  
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)" onclick="index.showTimeWorkingDetail('{{$item['sunday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                        @endif
                                                              
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalShift('{{$item['sunday']['data'][0]['staff_id']}}', '{{$item['sunday']['data'][0]['working_day']}}', 'list')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif
                                                         @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($item['sunday']['data'][0]['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $item['sunday']['data'][0]['is_close'] == 0)
                                                                @if (($item['sunday']['data'][0]['is_check_in'] == 0 || $item['sunday']['data'][0]['is_check_out'] == 0) && $item['sunday']['data'][0]['is_deducted'] === null)
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.showModalTimeAttendance('{{$item['sunday']['data'][0]['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                     </a>
                                                                @endif
                                                          @endif
                                                          @if (($item['sunday']['data'][0]['is_close'] == 0 || $item['sunday']['data'][0]['is_close'] == null) && ($item['sunday']['data'][0]['is_check_in'] == 0 || $item['sunday']['data'][0]['is_check_in'] == null) && ($item['sunday']['data'][0]['is_check_out'] == 0 || $item['sunday']['data'][0]['is_check_out'] == null))
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['sunday']['data'][0]['is_deducted'] === null || $item['sunday']['data'][0]['is_deducted'] === 1))
                                                                    <a class="dropdown-item" href="javascript:void(0)"onclick="index.paidOrUnPaidLeave('{{$item['sunday']['data'][0]['time_working_staff_id']}}', 'paid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương') 
                                                                    </a>    
                                                                @endif
                                                                @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($item['sunday']['data'][0]['is_deducted'] === null || $item['sunday']['data'][0]['is_deducted'] === 0))
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                           onclick="index.paidOrUnPaidLeave('{{$item['sunday']['data'][0]['time_working_staff_id']}}', 'unpaid', 'salary-detail')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="WorkChild.showPopup(null, '{{$item['sunday']['data'][0]['staff_id']}}', '5', '{{$item['sunday']['data'][0]['working_day']}}', '{{$item['sunday']['data'][0]['working_time']}}', '{{$item['sunday']['data'][0]['working_end_day']}}', '{{$item['sunday']['data'][0]['working_end_time']}}', 'shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                                </a>
                                                            @endif
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-12">
                                                <div style="text-align: center;">
                                                    (
                                                    @if($item['sunday']['data'][0]['is_check_in'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['sunday']['data'][0]['check_in_time'])->format('H:s') }}
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>
                                                    -
                                                    @if($item['sunday']['data'][0]['is_check_out'] == 1)
                                                        {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['sunday']['data'][0]['check_out_time'])->format('H:s') }}
                                                        
                                                    @else
                                                        --:--
                                                    @endif
                                                    <i class="la la-mobile"></i>
                                                    )
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row" style="padding-top: 10px;">
                                            <div class="col-12" >
                                                <p><b>{{$item['sunday']['data'][0]['branch_name']}}</b></p>
                                            </div>
                                            <div class="col-12" >
                                                @if ($item['sunday']['data'][0]['time_off_days_id'] != null && $item['sunday']['data'][0]['time_off_days_id'] != 0)
                                                @if ($item['sunday']['data'][0]['is_approve_time_off'] === 1)
                                                    <span>
                                                        <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                    </span>
                                                @elseif ($item['sunday']['data'][0]['is_approve_time_off'] === 0)
                                                    <span>
                                                        <i class="la la-times-circle-o" style="color : #ed2e24" title="{{ __('Đơn phép không được chấp nhận') }}"></i>
                                                    </span>
                                                @else
                                                    <span>
                                                        <i class="la la-clock-o" style="color : #ffb927" title="{{ __('Đơn phép đang chờ duyệt') }}"></i>
                                                    </span>
                                                @endif
                                            @endif
                                            </div>
                                        </div>
                                    </td>
                                @else
                                <td style="border-top: none; background: #a0d4d4;">
                                    <div class="form-group m--padding-top-30">
                                        <a href="javascript:void(0)" onclick="index.showModalMyShift('{{$item['sunday']['data'][0]['staff_id']}}', '{{$item['sunday']['data'][0]['working_day']}}')">
                                            <p><b>{{count($item['sunday']['data'])}} @lang('Ca')</b></p>
                                        </a>
                                        @foreach($item['sunday']['data'] as $value => $objShift)
                                            <span class="line_note_shift" style="background: {{ $objShift['background'] }}; {{$objShift['is_ot'] == 1 ? 'border: 1px solid red;' : ''}}"></span> 
                                        @endforeach
                                    </div>
                                </td>
                                @endif
                            @else
                                <td style="border-top: none;"></td>
                            @endif
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
        </table>
    </div>
    <div class="form-group m--padding-top-30">
        <span class="line_note" style="background: #ECECEC;"></span> &nbsp; @lang('Chưa đến ca') &nbsp;
        <span class="line_note" style="background: #DBEFDC;"></span> &nbsp; @lang('Chấm công đúng giờ')
        &nbsp;
        <span class="line_note" style="background: #FDD9D7;"></span> &nbsp; @lang('Chưa vào/ ra ca') &nbsp;
        <span class="line_note" style="background: #FFEACC;"></span> &nbsp; @lang('Vào trễ/ ra sớm') &nbsp;
        <span class="line_note" style="background: #D9DCF0;"></span> &nbsp; @lang('Nghỉ phép có lương')
        &nbsp;
        <span class="line_note" style="background: #EBD4EF;"></span> &nbsp; @lang('Nghỉ phép không lương')
        &nbsp;
        <span class="line_note" style="background: #F6695E;"></span> &nbsp; @lang('Tăng ca')
    </div>
</div>
