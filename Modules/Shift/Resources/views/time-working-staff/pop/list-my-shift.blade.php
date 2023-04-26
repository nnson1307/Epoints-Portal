<div class="row">
    @if (count($list) > 0)
        @foreach($list as $k => $v)
            <?php
            $backgroundColor = "";

            if (\Carbon\Carbon::createFromFormat('Y-m-d', $v['working_day'])->format('Y-m-d') > \Carbon\Carbon::now()->format('Y-m-d')) {
                //Chưa tới giờ làm việc
                $backgroundColor = "#D3D3D3";
                if ($v['is_deducted'] === 0) {
                    $backgroundColor = "#D9DCF0";
                }
                if ($v['is_deducted'] === 1) {
                    $backgroundColor = "#EBD4EF";
                }
            } //Ngày hiện tại
            elseif (\Carbon\Carbon::createFromFormat('Y-m-d', $v['working_day'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) {
                //Chưa tới giờ làm việc
                $backgroundColor = "#DBEFDC";
                if ($v['is_check_in'] == 0 || $v['is_check_out'] == 0) {
                    $backgroundColor = "#FDD9D7";
                }
                if ($v['is_deducted'] === 0) {
                    $backgroundColor = "#D9DCF0";
                }
                if ($v['is_deducted'] === 1) {
                    $backgroundColor = "#EBD4EF";
                }

                if ($v['is_check_in'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_day'] . ' ' . $v['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_in_day'] . ' ' . $v['check_in_time'])) {
                    //Vào trễ
                    $backgroundColor = "#FFEACC";
                }

                if ($v['is_check_out'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_end_day'] . ' ' . $v['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_out_day'] . ' ' . $v['check_out_time'])) {
                    //Ra sớm
                    $backgroundColor = "#FFEACC";
                }

                if (($v['is_check_out'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_end_day'] . ' ' . $v['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_out_day'] . ' ' . $v['check_out_time'])) && ($v['is_check_in'] == 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_day'] . ' ' . $v['working_time'])->addMinutes(session()->get('late_check_in')) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_in_day'] . ' ' . $v['check_in_time']))) {
                    //Ra vào đúng giờ
                    $backgroundColor = "#DBEFDC";
                }

                //Check có check in (nghỉ không lương so với cấu hình)
                if ($v['is_check_in'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_day'] . ' ' . $v['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_in_day'] . ' ' . $v['check_in_time'])
                    && session()->get('off_check_in') > 0) {
                    //Nghĩ không lương
                    $backgroundColor = "#EBD4EF";
                }

                //Check có check out (nghỉ không lương so với cấu hình)
                if ($v['is_check_out'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_end_day'] . ' ' . $v['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_out_day'] . ' ' . $v['check_out_time'])
                    && session()->get('off_check_out') > 0) {
                    //Nghĩ không lương
                    $backgroundColor = "#EBD4EF";
                }
            } //Ngày hiện tại trở về trước
            else {
                if ($v['is_deducted'] === 0) {
                    $backgroundColor = "#D9DCF0";
                } elseif ($v['is_deducted'] === 1) {
                    $backgroundColor = "#EBD4EF";
                } else {
                    if ($v['is_check_in'] === 0 && $v['is_check_out'] === 0) {
                        if ($v['is_deducted'] === 0) {
                            $backgroundColor = "#D9DCF0";
                        } else {
                            $backgroundColor = "#EBD4EF";
                        }
                    } else {
                        //Chưa vào ca/ ra ca
                        if ($v['is_check_in'] === 0 || $v['is_check_out'] === 0) {
                            $backgroundColor = "#FDD9D7";
                        }
                    }

                    if ($v['is_check_in'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_day'] . ' ' . $v['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_in_day'] . ' ' . $v['check_in_time'])) {
                        //Vào trễ
                        $backgroundColor = "#FFEACC";
                    }

                    if ($v['is_check_out'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_end_day'] . ' ' . $v['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_out_day'] . ' ' . $v['check_out_time'])) {
                        //Ra sớm
                        $backgroundColor = "#FFEACC";
                    }

                    if (($v['is_check_out'] === 1 &&
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_end_day'] . ' ' . $v['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_out_day'] . ' ' . $v['check_out_time'])) && ($v['is_check_in'] == 1 &&
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_day'] . ' ' . $v['working_time'])->addMinutes(session()->get('late_check_in')) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_in_day'] . ' ' . $v['check_in_time']))) {
                        //Ra vào đúng giờ
                        $backgroundColor = "#DBEFDC";
                    }

                    //Check có check in (nghỉ không lương so với cấu hình)
                    if ($v['is_check_in'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_day'] . ' ' . $v['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_in_day'] . ' ' . $v['check_in_time'])
                        && session()->get('off_check_in') > 0) {
                        //Nghĩ không lương
                        $backgroundColor = "#EBD4EF";
                    }

                    //Check có check out (nghỉ không lương so với cấu hình)
                    if ($v['is_check_out'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['working_end_day'] . ' ' . $v['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $v['check_out_day'] . ' ' . $v['check_out_time'])
                        && session()->get('off_check_out') > 0) {
                        //Nghĩ không lương
                        $backgroundColor = "#EBD4EF";
                    }
                }
            }
            ?>
            <div class="form-group col-lg-3"
                 style="background-color: {{$backgroundColor}};  {{$v['is_ot'] == 1 ? 'border: 2px solid red;' : ''}}; margin: 10px; text-align: center; ">
                <div class="row">
                    <div class="col-lg-8" style="text-align: left; margin: auto; width: 50%;">
                        {{$v['shift_name']}}
                    </div>
                    <div class="col-lg-4" style="text-align: right;">
                        <span class="dropdown">
                            <a href="javascript:void(0)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                               data-toggle="dropdown" aria-expanded="false">
                              <i class="la la-ellipsis-v m--font-brand"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="index.showTimeWorkingDetail('{{$v['time_working_staff_id']}}', 'my_shift')">
                                        <i class="la la-info"></i> @lang('Chi tiết')</a>
                                @endif
                                {{--in_array('shift.time-working-staff.show-pop-edit', session('routeList')) &&--}}
                                @if (\Carbon\Carbon::parse($v['working_day'])->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d') && $v['is_close'] == 0 && $v['is_check_in'] == 0)
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="index.showModalEdit('{{$v['time_working_staff_id']}}', 'my_shift')">
                                                <i class="la la-edit"></i> @lang('Chỉnh sửa')
                                             </a>
                                @endif
                                @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($v['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $v['is_close'] == 0)
                                    @if (($v['is_check_in'] == 0 || $v['is_check_out'] == 0) && $v['is_deducted'] == null)
                                        <a class="dropdown-item" href="javascript:void(0)"
                                           onclick="index.showModalTimeAttendance('{{$v['time_working_staff_id']}}', 'my_shift')">
                                                <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                             </a>
                                    @endif
                                @endif
                                @if ($v['is_close'] == 0 && $v['is_check_in'] == 0 && $v['is_check_out'] == 0)
                                    @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($v['is_deducted'] == null || $v['is_deducted'] == 0))
                                        <a class="dropdown-item" href="javascript:void(0)"
                                           onclick="index.paidOrUnPaidLeave('{{$v['time_working_staff_id']}}', 'paid', 'my_shift')">
                                                <i class="fa fa-check-circle"></i> @lang('Nghỉ có lương')
                                            </a>
                                    @endif
                                    @if (in_array('shift.time-working-staff.paid-or-unpaid-leave', session('routeList')) && ($v['is_deducted'] == null || $v['is_deducted'] == 0))
                                        <a class="dropdown-item" href="javascript:void(0)"
                                           onclick="index.paidOrUnPaidLeave('{{$v['time_working_staff_id']}}', 'unpaid', 'list')">
                                                                        <i class="fa fa-check-circle"></i> @lang('Nghỉ không lương')
                                                                </a>
                                    @endif
                                @endif
                                @if (in_array('manager-work.detail.show-popup-work-child', session('routeList')))
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="WorkChild.showPopup(null, '{{$v['staff_id']}}', '5', '{{$v['working_day']}}', '{{$v['working_time']}}', '{{$v['working_end_day']}}', '{{$v['working_end_time']}}', 'my_shift')">
                                            <i class="fa fa-plus-circle"></i> @lang('Thêm công việc')
                                        </a>
                                @endif
                                @if (in_array('shift.time-working-staff.show-pop-overtime', session('routeList')))
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="index.showModalOvertime('{{$v['time_working_staff_id']}}', 'my_shift')">
                                                                    <i class="fa fa-plus-circle"></i> @lang('Làm thêm giờ')
                                </a>
                                @endif
                                @if (in_array('shift.time-working-staff.remove-shift', session('routeList')))
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="index.remove('{{$v['time_working_staff_id']}}', 'my_shift')">
                                        <i class="fa fa-trash-alt"></i> @lang('Xoá')
                                    </a>
                                @endif
                            </div>
                        </span>
                    </div>
                </div>
                <div style="text-align: center;">
                    @if (\Carbon\Carbon::parse($v['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d'))
                        ({{$v['is_check_in'] == 1 ? \Carbon\Carbon::parse($v['check_in_time'])->format('H:i') : '--:--'}}
                        @if ($v['is_check_in'] == 1 && $v['created_type_ci'] == "staff")
                            <i class="la la-mobile"></i>
                        @elseif($v['is_check_in'] == 1 && $v['created_type_ci'] == "admin")
                            <i class="la la-user-plus"></i>
                        @endif
                        -
                        {{$v['is_check_out'] == 1 ? \Carbon\Carbon::parse($v['check_out_time'])->format('H:i') : '--:--'}}
                        @if ($v['is_check_out'] == 1  && $v['created_type_co'] == "staff")
                            <i class="la la-mobile"></i>)
                        @elseif($v['is_check_out'] == 1  && $v['created_type_co'] == "admin")
                            <i class="la la-user-plus"></i>)
                        @else
                            )
                        @endif
                    @else
                       ({{\Carbon\Carbon::parse($v['working_time'])->format('H:i')}} - {{\Carbon\Carbon::parse($v['working_end_time'])->format('H:i')}})
                    @endif
                </div>
                <div>
                    <span class="font-weight-bold">{{$v['branch_name']}}</span>
                </div>
            </div>
        @endforeach
    @endif
    <div class="form-group col-lg-3" style="display: flex; align-items: center; margin: 0;">
        @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')) && \Carbon\Carbon::parse($working_day)->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d'))
            <a class="btn btn-secondary btn-lg m-btn m-btn m-btn--icon" href="javascript:void(0)"
               onclick="index.showModalShift('{{$staff_id}}', '{{$working_day}}', 'my_shift', null, '{{$is_holiday}}')">
                <span>
                    <i class="la la-plus"></i>
                </span>
            </a>
        @endif
    </div>
</div>

