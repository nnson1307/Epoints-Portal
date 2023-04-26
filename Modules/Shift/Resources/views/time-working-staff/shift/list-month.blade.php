<div class="table-responsive" style="min-height: 300px;">
    <table class="table table-bordered m-table" style="table-layout: fixed;">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list" width="210px;">{{__('CA LÀM')}}</th>
            <th class="tr_thead_list" width="210px;">{{__('NHÂN VIÊN')}}</th>
            @if (count($listDay) > 0)
                @foreach($listDay as $v)
                    <th style="width: 160px; color: {{$v['is_holiday'] == 1 ? "#ff0000": ""}} !important;"
                        class="tr_thead_list text-center">
                        {{$v['day_format']}} <br/> {{$v['day_name']}}
                    </th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $k => $v)
                <tr>
                    <td rowspan="{{count($v['staff']) > 0 ? count($v['staff']) + 1 : 2}}">
                        <div class="row">
                            <div class="col-lg-9" style="text-align: left;">
                                {{$v['shift_name']}}
                            </div>
                            <div class="col-lg-3" style="text-align: right;">
                                <span class="dropdown">
                                    <a href="javascript:void(0)"
                                       class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown"
                                       aria-expanded="false">
                                      <i class="la la-ellipsis-v m--font-brand"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                        @if (in_array('shift.time-working-staff.show-pop-staff', session('routeList')))
                                            <a class="dropdown-item" href="javascript:void(0)"
                                               onclick="index.showModalStaff('', '{{$v['shift_id']}}', '{{$start_time}}', '{{$end_time}}')">
                                                <i class="fa fa-plus-circle"></i> @lang('Thêm nhân viên')
                                            </a>
                                        @endif

                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                ({{\Carbon\Carbon::parse($v['start_work_time'])->format('H:i')}}
                                - {{\Carbon\Carbon::parse($v['end_work_time'])->format('H:i')}}) <br/> <br/>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
                $color = ["success", "brand", "danger", "accent", "warning", "metal", "primary", "info"];
                ?>
                @if (count($v['staff']) > 0)
                    @foreach($v['staff'] as $k1 => $v1)
                    @php($num = rand(0,7))
                        <tr>
                            <td>
                                <div class="row"  style="padding: 0px 0px 0px 5px;">
                                    <div class="m-list-pics m-list-pics--sm">
                                        <div class="m-card-user m-card-user--sm">
                                            @if($v['staff_avatar']!=null)
                                            <div class="m-card-user__pic">
                                                <img src="{{$v1['staff_avatar']}}"
                                                     onerror="this.onerror=null;this.src='https://placehold.it/40x40/00a65a/ffffff/&text=' + '{{substr(str_slug($v1['staff_name']),0,1)}}';"
                                                     class="m--img-rounded m--marginless" alt="photo" width="40px"
                                                     height="40px">
                                            </div>
                                            @else
                                            <div class="m-card-user__pic">
                                                <div class="m-card-user__no-photo m--bg-fill-{{$color[$num]}}">
                                                    <span>
                                                        {{substr(str_slug($v1['staff_name']),0,1)}}
                                                    </span>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="m-card-user__details">
                                                <div class="row">
                                                    <div class="col-lg-9">
                                                        <a href="{{route('admin.staff.show', $v1['staff_id'])}}" target="_blank" class="m-card-user__name line-name font-name">
                                                            {{$v1['staff_name']}}</a>
                                                            <span class="m-card-user__email font-sub">
                                                                {{$v1['department_name']}}
                                                            </span>
                                                            <span class="m-card-user__email font-sub">
                                                                {{$v1['branch_name']}}
                                                            </span>
                                                    </div>
                                                    <span class="dropdown">
                                                        <a href="javascript:void(0)"
                                                           class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                                           data-toggle="dropdown" aria-expanded="false">
                                                          <i class="la la-ellipsis-v m--font-brand"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                                            @if (in_array('shift.time-working-staff.remove-staff-by-shift', session('routeList')))
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                   onclick="index.removeStaffByShift('{{$v1['staff_id']}}', '{{$v['shift_id']}}', '{{$start_time}}', '{{$end_time}}')">
                                                                <i class="fa fa-trash-alt"></i> @lang('Xoá')
                                                                </a>
                                                            @endif
                    
                                                        </div>
                                                    </span>
                                                </div>
                                              
                                            </div>
                                          
                                        </div>
                                    </div>
                                   
                                </div>
                            </td>
                            @foreach($v1['day'] as $k2 => $v2)
                                @if ($v2 != null)
                                    <?php
                                    $backgroundColor = "";

                                    if (\Carbon\Carbon::createFromFormat('Y-m-d', $v2['working_day'])->format('Y-m-d') > \Carbon\Carbon::now()->format('Y-m-d')) {
                                        //Chưa tới giờ làm việc
                                        $backgroundColor = "#D3D3D3";
                                        if ($v2['is_deducted'] === 0) {
                                            $backgroundColor = "#D9DCF0";
                                        }
                                        if ($v2['is_deducted'] === 1) {
                                            $backgroundColor = "#EBD4EF";
                                        }
                                    } //Ngày hiện tại
                                    elseif (\Carbon\Carbon::createFromFormat('Y-m-d', $v2['working_day'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) {
                                        //Chưa tới giờ làm việc
                                        $backgroundColor = "#DBEFDC";
                                        if ($v2['is_check_in'] === 0 || $v2['is_check_out'] === 0) {
                                            $backgroundColor = "#FDD9D7";
                                        }
                                        if ($v2['is_deducted'] === 0) {
                                            $backgroundColor = "#D9DCF0";
                                        }
                                        if ($v2['is_deducted'] === 1) {
                                            $backgroundColor = "#EBD4EF";
                                        }

                                        if ($v2['is_check_in'] === 1 &&
                                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_day'] . ' ' . $v2['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_in_day'] . ' ' . $v2['check_in_time'])) {
                                            //Vào trễ
                                            $backgroundColor = "#FFEACC";
                                        }

                                        if ($v2['is_check_out'] === 1 &&
                                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_end_day'] . ' ' . $v2['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_out_day'] . ' ' . $v2['check_out_time'])) {
                                            //Ra sớm
                                            $backgroundColor = "#FFEACC";
                                        }

                                        //Check có check in (nghỉ không lương so với cấu hình)
                                        if ($v2['is_check_in'] === 1 &&
                                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_day'] . ' ' . $v2['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_in_day'] . ' ' . $v2['check_in_time'])
                                            && session()->get('off_check_in') > 0) {
                                            //Nghĩ không lương
                                            $backgroundColor = "#EBD4EF";
                                        }

                                        //Check có check out (nghỉ không lương so với cấu hình)
                                        if ($v2['is_check_out'] === 1 &&
                                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_end_day'] . ' ' . $v2['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_out_day'] . ' ' . $v2['check_out_time'])
                                            && session()->get('off_check_out') > 0) {
                                            //Nghĩ không lương
                                            $backgroundColor = "#EBD4EF";
                                        }
                                    } //Ngày hiện tại trở về trước
                                    else {
                                        if ($v2['is_deducted'] === 0) {
                                            $backgroundColor = "#D9DCF0";
                                        } elseif ($v2['is_deducted'] === 1) {
                                            $backgroundColor = "#EBD4EF";
                                        } else {
                                            //Chưa vào ca/ ra ca
                                            if ($v2['is_check_in'] === 0 && $v2['is_check_out'] === 0) {
                                                if ($v2['is_deducted'] === 0) {
                                                    $backgroundColor = "#D9DCF0";
                                                } else {
                                                    $backgroundColor = "#EBD4EF";
                                                }
                                            } else {
                                                //Chưa vào ca/ ra ca
                                                if ($v2['is_check_in'] === 0 || $v2['is_check_out'] === 0) {

                                                    $backgroundColor = "#FDD9D7";
                                                }
                                            }
                                            if ($v2['is_check_in'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_day'] . ' ' . $v2['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_in_day'] . ' ' . $v2['check_in_time'])) {
                                                //Vào trễ
                                                $backgroundColor = "#FFEACC";
                                            }

                                            if ($v2['is_check_out'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_end_day'] . ' ' . $v2['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_out_day'] . ' ' . $v2['check_out_time'])) {
                                                //Ra sớm
                                                $backgroundColor = "#FFEACC";
                                            }

                                            if (($v2['is_check_out'] === 1 &&
                                                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_end_day'] . ' ' . $v2['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_out_day'] . ' ' . $v2['check_out_time'])) && ($v2['is_check_in'] == 1 &&
                                                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_day'] . ' ' . $v2['working_time'])->addMinutes(session()->get('late_check_in')) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_in_day'] . ' ' . $v2['check_in_time']))) {
                                                //Ra vào đúng giờ
                                                $backgroundColor = "#DBEFDC";
                                            }

                                            //Check có check in (nghỉ không lương so với cấu hình)
                                            if ($v2['is_check_in'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_day'] . ' ' . $v2['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_in_day'] . ' ' . $v2['check_in_time'])
                                                && session()->get('off_check_in') > 0) {
                                                //Nghĩ không lương
                                                $backgroundColor = "#EBD4EF";
                                            }

                                            //Check có check out (nghỉ không lương so với cấu hình)
                                            if ($v2['is_check_out'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['working_end_day'] . ' ' . $v2['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v2['check_out_day'] . ' ' . $v2['check_out_time'])
                                                && session()->get('off_check_out') > 0) {
                                                //Nghĩ không lương
                                                $backgroundColor = "#EBD4EF";
                                            }
                                        }
                                    }
                                    ?>

                                    <td class="is_shift_month">
                                        <div class="row">
                                            <div class="col-lg-6 text-left">
                                                @if ($v2['time_off_days_id'] != null && $v2['time_off_days_id'] != 0)
                                                    @if ($v2['is_approve_time_off'] === 1)
                                                        <span>
                                                            <i class="la la-check-circle" style="color : #00a650" title="{{ __('Đơn phép được chấp nhận') }}"></i>
                                                        </span>
                                                    @elseif ($v2['is_approve_time_off'] === 0)
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
                                            <div class="col-lg-6" style="text-align: center;">
                                                <span class="dropdown">
                                                    <a href="javascript:void(0)"
                                                       class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                                       data-toggle="dropdown" aria-expanded="false">
                                                      <i class="la la-ellipsis-v m--font-brand"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         x-placement="bottom-end">
                                                        @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                               onclick="index.showTimeWorkingDetail('{{$v2['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-info"></i> @lang('Chi tiết')
                                                            </a>
                                                        @endif
                                                        @if (\Carbon\Carbon::parse($v2['working_day'])->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d') && $v2['is_close'] == 0 && $v2['is_check_in'] == 0)
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                               onclick="index.showModalEdit('{{$v2['time_working_staff_id']}}', 'list')">
                                                                <i class="la la-edit"></i> @lang('Chỉnh sửa')
                                                             </a>
                                                        @endif
                                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                               onclick="index.showModalShift('{{$v2['staff_id']}}', '{{$v2['working_day']}}', 'list', null, '{{$listDay[$v2['working_day']]['is_holiday']}}')">
                                                                <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                            </a>
                                                        @endif

                                                        @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList'))
                                                            && \Carbon\Carbon::parse($v2['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d')
                                                            && $v2['is_close'] == 0)
                                                            @if (($v2['is_check_in'] == 0 || $v2['is_check_out'] == 0) && $v2['is_deducted'] === null)
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                   onclick="index.showModalTimeAttendance('{{$v2['time_working_staff_id']}}', 'list')">
                                                                        <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                </a>
                                                            @endif
                                                        @endif
                                                        @if ($v2['is_close'] == 0 && $v2['is_check_in'] == 0 && $v2['is_check_out'] == 0)
                                                            @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($v2['is_deducted'] === null || $v2['is_deducted'] === 1))
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                   onclick="index.paidOrUnPaidLeave('{{$v2['time_working_staff_id']}}', 'paid', 'list')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương')
                                                                </a>
                                                            @endif
                                                            @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($v2['is_deducted'] === null || $v2['is_deducted'] === 0))
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                   onclick="index.paidOrUnPaidLeave('{{$v2['time_working_staff_id']}}', 'unpaid', 'list')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                </a>
                                                            @endif
                                                        @endif
                                                        @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                               onclick="WorkChild.showPopup(null, '{{$v2['staff_id']}}', '5', '{{$v2['working_day']}}', '{{$v2['working_time']}}', '{{$v2['working_end_day']}}', '{{$v2['working_end_time']}}', 'shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                                            </a>
                                                        @endif
                                                        @if (in_array('shift.time-working-staff.show-pop-overtime', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                               onclick="index.showModalOvertime('{{$v2['time_working_staff_id']}}', 'list')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Làm thêm giờ')
                                                        </a>
                                                        @endif
                                                        @if (in_array('shift.time-working-staff.remove-shift', session('routeList')))
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                               onclick="index.remove('{{$v2['time_working_staff_id']}}', 'list')">
                                                                    <i class="fa fa-trash-alt"></i> @lang('Xoá')
                                                            </a>
                                                        @endif

                                                    </div>
                                                </span>
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12" style="text-align: center;">
                                                <span class="line_note"
                                                      @if ($v2['is_ot'] == 0)
                                                      style="background: {{$backgroundColor}};"
                                                      @else
                                                      style="background: {{$backgroundColor}};border: 2px solid red;"
                                                        @endif
                                                ></span>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td class="un_shift">
                                        {{--                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')) && \Carbon\Carbon::parse($k2)->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d'))--}}
                                        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                            @switch(\Carbon\Carbon::parse($k2)->format('l'))
                                                @case('Monday')
                                                @if ($v['is_monday'] == 1)
                                                    <a class="btn btn-metal m-btn m-btn--icon btn-sm m-btn--icon-only"
                                                       href="javascript:void(0)"
                                                       onclick="index.showModalShift('{{$v1['staff_id']}}', '{{$k2}}', 'list', '{{$v['shift_id']}}', '{{$listDay[$k2]['is_holiday']}}')">
                                                        <i class="la la-plus"></i>
                                                    </a>
                                                @endif
                                                @break
                                                @case('Tuesday')
                                                @if ($v['is_tuesday'] == 1)
                                                    <a class="btn btn-metal m-btn m-btn--icon btn-sm m-btn--icon-only"
                                                       href="javascript:void(0)"
                                                       onclick="index.showModalShift('{{$v1['staff_id']}}', '{{$k2}}', 'list', '{{$v['shift_id']}}', '{{$listDay[$k2]['is_holiday']}}')">
                                                        <i class="la la-plus"></i>
                                                    </a>
                                                @endif
                                                @break
                                                @case('Wednesday')
                                                @if ($v['is_wednesday'] == 1)
                                                    <a class="btn btn-metal m-btn m-btn--icon btn-sm m-btn--icon-only"
                                                       href="javascript:void(0)"
                                                       onclick="index.showModalShift('{{$v1['staff_id']}}', '{{$k2}}', 'list', '{{$v['shift_id']}}', '{{$listDay[$k2]['is_holiday']}}')">
                                                        <i class="la la-plus"></i>
                                                    </a>
                                                @endif
                                                @break
                                                @case('Thursday')
                                                @if ($v['is_thursday'] == 1)
                                                    <a class="btn btn-metal m-btn m-btn--icon btn-sm m-btn--icon-only"
                                                       href="javascript:void(0)"
                                                       onclick="index.showModalShift('{{$v1['staff_id']}}', '{{$k2}}', 'list', '{{$v['shift_id']}}', '{{$listDay[$k2]['is_holiday']}}')">
                                                        <i class="la la-plus"></i>
                                                    </a>
                                                @endif
                                                @break
                                                @case('Friday')
                                                @if ($v['is_friday'] == 1)
                                                    <a class="btn btn-metal m-btn m-btn--icon btn-sm m-btn--icon-only"
                                                       href="javascript:void(0)"
                                                       onclick="index.showModalShift('{{$v1['staff_id']}}', '{{$k2}}', 'list', '{{$v['shift_id']}}', '{{$listDay[$k2]['is_holiday']}}')">
                                                        <i class="la la-plus"></i>
                                                    </a>
                                                @endif
                                                @break
                                                @case('Saturday')
                                                @if ($v['is_saturday'] == 1)
                                                    <a class="btn btn-metal m-btn m-btn--icon btn-sm m-btn--icon-only"
                                                       href="javascript:void(0)"
                                                       onclick="index.showModalShift('{{$v1['staff_id']}}', '{{$k2}}', 'list', '{{$v['shift_id']}}', '{{$listDay[$k2]['is_holiday']}}')">
                                                        <i class="la la-plus"></i>
                                                    </a>
                                                @endif
                                                @break
                                                @case('Sunday')
                                                @if ($v['is_sunday'] == 1)
                                                    <a class="btn btn-metal m-btn m-btn--icon btn-sm m-btn--icon-only"
                                                       href="javascript:void(0)"
                                                       onclick="index.showModalShift('{{$v1['staff_id']}}', '{{$k2}}', 'list', '{{$v['shift_id']}}', '{{$listDay[$k2]['is_holiday']}}')">
                                                        <i class="la la-plus"></i>
                                                    </a>
                                                @endif
                                                @break
                                            @endswitch
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td></td>
                        @if (count($listDay) > 0)
                            @foreach($listDay as $v)
                                <td></td>
                            @endforeach
                        @endif

                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>

{{ $LIST->links('helpers.paging') }}