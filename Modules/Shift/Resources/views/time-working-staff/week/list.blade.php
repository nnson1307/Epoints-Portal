<div class="table-responsive" style="min-height: 300px;">
    <table class="table table-bordered m-table">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">{{__('Nhân viên')}}</th>

            @if (count($listDay) > 0)
                @foreach($listDay as $v)
                    <th class="tr_thead_list text-center"
                        style="color: {{$v['is_holiday'] == 1 ? "#ff0000": ""}} !important;">
                        {{$v['day_name']}} <br/> {{$v['day_format']}}
                    </th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        <?php
        $color = ["success", "brand", "danger", "accent", "warning", "metal", "primary", "info"];
        ?>
        @if(isset($LIST))
            @foreach ($LIST as $k => $v)
            @php($num = rand(0,7))
                <tr>
                    <td>
                        <div class="row" style="padding: 0px 0px 0px 5px; ">
                            <div class="m-list-pics m-list-pics--sm">
                                <div class="m-card-user m-card-user--sm">
                                    @if($v['staff_avatar']!=null)
                                    <div class="m-card-user__pic">
                                        <img src="{{$v['staff_avatar']}}"
                                             onerror="this.onerror=null;this.src='https://placehold.it/40x40/00a65a/ffffff/&text=' + '{{substr(str_slug($v['full_name']),0,1)}}';"
                                             class="m--img-rounded m--marginless" alt="photo" width="40px"
                                             height="40px">
                                    </div>
                                    @else
                                    <div class="m-card-user__pic">

                                        <div class="m-card-user__no-photo m--bg-fill-{{$color[$num]}}">
                                            <span>
                                                {{substr(str_slug($v['full_name']),0,1)}} 
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                  
                                    <div class="m-card-user__details">
                                        <a href="{{route('admin.staff.show', $v['staff_id'])}}" target="_blank" class="m-card-user__name line-name font-name">
                                            {{$v['full_name']}}</a>
                                        <span class="m-card-user__email font-sub">
                                                {{$v['department_name']}}
                                            </span>
                                            <span class="m-card-user__email font-sub">
                                                {{$v['branch_name']}}
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    @if (count($v['shift']) > 0)
                        @foreach($v['shift'] as $k1 => $v1)
                            @switch (count($v1))
                                @case(0)
                                <td class="un_shift">
                                    @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                        <a class="btn btn-secondary btn-lg m-btn m-btn m-btn--icon"
                                           href="javascript:void(0)"
                                           onclick="index.showModalShift('{{$v['staff_id']}}', '{{$k1}}', 'list', null, '{{$listDay[$k1]['is_holiday']}}')">
                                        <span>
                                            <i class="la la-plus"></i>
                                        </span>
                                        </a>
                                    @endif
                                </td>
                                @break
                                @case(1)
                                @foreach($v1 as $k2 => $v2)
                                    <?php
                                    $backgroundColor = "";

                                    //Lớn hơn ngày hiện tại
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

                                    <td class="is_shift"
                                        style="background-color: {{$backgroundColor}};  {{$v2['is_ot'] == 1 ? 'border: 2px solid red;' : ''}} ">
                                        <div class="row">
                                            <div class="col-lg-9" style="text-align: left;">
                                                {{$v2['shift_name']}}
                                            </div>
                                            <span class="dropdown">
                                                <a href="javascript:void(0)"
                                                   class="btn m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                                                   data-toggle="dropdown" aria-expanded="false">
                                                  <i class="la la-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                     x-placement="bottom-end">
                                                    @if (in_array('shift.time-working-staff.show-time-working-detail', session('routeList')))
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                           onclick="index.showTimeWorkingDetail('{{$v2['time_working_staff_id']}}', 'list', null, '{{$listDay[$v2['working_day']]['is_holiday']}}')">
                                                            <i class="la la-info"></i> @lang('Chi tiết')</a>
                                                    @endif
                                                    @if (\Carbon\Carbon::parse($v2['working_day'])->format('Y-m-d') >= \Carbon\Carbon::now()->format('Y-m-d') && $v2['is_close'] == 0 && $v2['is_check_in'] == 0)
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                           onclick="index.showModalEdit('{{$v2['time_working_staff_id']}}', 'list')">
                                                            <i class="la la-edit"></i> @lang('Chỉnh sửa')
                                                         </a>
                                                    @endif
                                                    @if (in_array('shift.time-working-staff.show-pop-shift', session('routeList')))
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                           onclick="index.showModalShift('{{$v['staff_id']}}', '{{$k1}}', 'list', null, '{{$listDay[$k1]['is_holiday']}}')">
                                                            <i class="fa fa-plus-circle"></i> @lang('Thêm ca làm việc')
                                                        </a>
                                                    @endif
                                                    @if (in_array('shift.time-working-staff.show-pop-time-attendance', session('routeList')) &&  \Carbon\Carbon::parse($v2['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') && $v2['is_close'] == 0)
                                                        @if (($v2['is_check_in'] == 0 || $v2['is_check_out'] == 0) && $v2['is_deducted'] === null)
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                               onclick="index.showModalTimeAttendance('{{$v2['time_working_staff_id']}}', 'list')">
                                                                    <i class="fa fa-calendar-check"></i> @lang('Chấm công hộ')
                                                                 </a>
                                                        @endif
                                                    @endif
                                                    @if (($v2['is_close'] == 0 || $v2['is_close'] == null) && ($v2['is_check_in'] == 0 || $v2['is_check_in'] == null) && ($v2['is_check_out'] == 0 || $v2['is_check_out'] == null))
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
                                        <div style="text-align: center;">
                                            @if (\Carbon\Carbon::parse($v2['working_day'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d'))
                                                ({{$v2['is_check_in'] == 1 ? \Carbon\Carbon::parse($v2['check_in_time'])->format('H:i') : '--:--'}}
                                                @if ($v2['is_check_in'] == 1 && $v2['created_type_ci'] == "staff")
                                                    <i class="la la-mobile"></i>
                                                @elseif($v2['is_check_in'] == 1 && $v2['created_type_ci'] == "admin")
                                                    <i class="la la-user-plus"></i>
                                                @endif
                                                -
                                                {{$v2['is_check_out'] == 1 ? \Carbon\Carbon::parse($v2['check_out_time'])->format('H:i') : '--:--'}}
                                                @if ($v2['is_check_out'] == 1  && $v2['created_type_co'] == "staff")
                                                    <i class="la la-mobile"></i>)
                                                @elseif($v2['is_check_out'] == 1  && $v2['created_type_co'] == "admin")
                                                    <i class="la la-user-plus"></i>)
                                                @else
                                                    )
                                                @endif
                                            @else
                                               ({{\Carbon\Carbon::parse($v2['working_time'])->format('H:i')}} - {{\Carbon\Carbon::parse($v2['working_end_time'])->format('H:i')}})
                                            @endif
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="col-lg-10 font-weight-bold" style="text-align: left;">
                                                    {{$v2['branch_name']}}
                                                </div>
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
                                          
                                        </div>
                                    </td>
                                @endforeach
                                @break
                                @case(count($v1) > 1)
                                <td class="is_shift">
                                    <p>
                                        <a href="javascript:void(0)"
                                           onclick="index.showModalMyShift('{{$v['staff_id']}}', '{{$k1}}')">
                                            <span class="font-weight-bold">{{count($v1)}} @lang('Ca')</span>
                                        </a>
                                    </p>
                                    @foreach($v1 as $value => $objShift)
                                        <?php

                                        if (\Carbon\Carbon::createFromFormat('Y-m-d', $objShift['working_day'])->format('Y-m-d') > \Carbon\Carbon::now()->format('Y-m-d')) {
                                            //Chưa tới giờ làm việc
                                            $backgroundColor = "#D3D3D3";
                                            if ($objShift['is_deducted'] === 0) {
                                                $backgroundColor = "#D9DCF0";
                                            }
                                            if ($objShift['is_deducted'] === 1) {
                                                $backgroundColor = "#EBD4EF";
                                            }
                                        } //Ngày hiện tại
                                        elseif (\Carbon\Carbon::createFromFormat('Y-m-d', $objShift['working_day'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) {
                                            //Chưa tới giờ làm việc
                                            $backgroundColor = "#DBEFDC";
                                            if ($objShift['is_check_in'] == 0 || $objShift['is_check_out'] == 0) {
                                                $backgroundColor = "#FDD9D7";
                                            }
                                            if ($objShift['is_deducted'] === 0) {
                                                $backgroundColor = "#D9DCF0";
                                            }
                                            if ($objShift['is_deducted'] === 1) {
                                                $backgroundColor = "#EBD4EF";
                                            }
                                            if ($objShift['is_check_in'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_day'] . ' ' . $objShift['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_in_day'] . ' ' . $objShift['check_in_time'])->format('Y-m-d H:i')) {
                                                //Vào trễ
                                                $backgroundColor = "#FFEACC";
                                            }

                                            if ($objShift['is_check_out'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_end_day'] . ' ' . $objShift['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_out_day'] . ' ' . $objShift['check_out_time'])) {
                                                //Ra sớm
                                                $backgroundColor = "#FFEACC";
                                            }

                                            //Check có check in (nghỉ không lương so với cấu hình)
                                            if ($objShift['is_check_in'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_day'] . ' ' . $objShift['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_in_day'] . ' ' . $objShift['check_in_time'])
                                                && session()->get('off_check_in') > 0) {
                                                //Nghĩ không lương
                                                $backgroundColor = "#EBD4EF";
                                            }

                                            //Check có check out (nghỉ không lương so với cấu hình)
                                            if ($objShift['is_check_out'] === 1 &&
                                                \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_end_day'] . ' ' . $objShift['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_out_day'] . ' ' . $objShift['check_out_time'])
                                                && session()->get('off_check_out') > 0) {
                                                //Nghĩ không lương
                                                $backgroundColor = "#EBD4EF";
                                            }
                                        } //Ngày hiện tại trở về trước
                                        else {
                                            if ($objShift['is_deducted'] === 0) {
                                                $backgroundColor = "#D9DCF0";
                                            } elseif ($objShift['is_deducted'] === 1) {
                                                $backgroundColor = "#EBD4EF";
                                            } else {

                                                if ($objShift['is_check_in'] === 0 && $objShift['is_check_out'] === 0) {
                                                    if ($objShift['is_deducted'] === 0) {
                                                        $backgroundColor = "#D9DCF0";
                                                    } else {
                                                        $backgroundColor = "#EBD4EF";
                                                    }
                                                } else {
                                                    //Chưa vào ca/ ra ca
                                                    if ($objShift['is_check_in'] === 0 || $objShift['is_check_out'] === 0) {

                                                        $backgroundColor = "#FDD9D7";

                                                    }
                                                }
                                                if ($objShift['is_check_in'] === 1 &&
                                                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_day'] . ' ' . $objShift['working_time'])->addMinutes(session()->get('late_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_in_day'] . ' ' . $objShift['check_in_time'])) {
                                                    //Vào trễ
                                                    $backgroundColor = "#FFEACC";
                                                }

                                                if ($objShift['is_check_out'] === 1 &&
                                                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_end_day'] . ' ' . $objShift['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_out_day'] . ' ' . $objShift['check_out_time'])) {
                                                    //Ra sớm
                                                    $backgroundColor = "#FFEACC";
                                                }

                                                if (($objShift['is_check_out'] === 1 &&
                                                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_end_day'] . ' ' . $objShift['working_end_time'])->subMinutes(session()->get('back_soon_check_out')) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_out_day'] . ' ' . $objShift['check_out_time'])) && ($objShift['is_check_in'] == 1 &&
                                                        \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_day'] . ' ' . $objShift['working_time'])->addMinutes(session()->get('late_check_in')) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_in_day'] . ' ' . $objShift['check_in_time']))) {
                                                    //Ra vào đúng giờ
                                                    $backgroundColor = "#DBEFDC";
                                                }

                                                //Check có check in (nghỉ không lương so với cấu hình)
                                                if ($objShift['is_check_in'] === 1 &&
                                                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_day'] . ' ' . $objShift['working_time'])->addMinutes(session()->get('off_check_in')) < \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_in_day'] . ' ' . $objShift['check_in_time'])
                                                    && session()->get('off_check_in') > 0) {
                                                    //Nghĩ không lương
                                                    $backgroundColor = "#EBD4EF";
                                                }

                                                //Check có check out (nghỉ không lương so với cấu hình)
                                                if ($objShift['is_check_out'] === 1 &&
                                                    \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['working_end_day'] . ' ' . $objShift['working_end_time'])->subMinutes(session()->get('off_check_out')) > \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $objShift['check_out_day'] . ' ' . $objShift['check_out_time'])
                                                    && session()->get('off_check_out') > 0) {
                                                    //Nghĩ không lương
                                                    $backgroundColor = "#EBD4EF";
                                                }
                                            }
                                        }
                                        ?>
                                        <span class="line_note"
                                              style="background: {{ $backgroundColor}}; {{$objShift['is_ot'] == 1 ? 'border: 2px solid red;' : ''}} "></span>
                                    @endforeach
                                </td>
                                @break
                            @endswitch
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
