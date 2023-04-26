<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list text-center">#</th>
            <th class="tr_thead_list text-center">@lang('Tên nhân viên')</th>
            <th class="tr_thead_list text-center">@lang('Vị trí làm việc')</th>
            <th class="tr_thead_list text-center">@lang('Phòng ban')</th>
            <th class="tr_thead_list text-center">@lang('Tên ca')</th>
            <th class="tr_thead_list text-center">@lang('Ngày làm việc')</th>
            <th class="tr_thead_list text-center">@lang('Giờ vào')</th>
            <th class="tr_thead_list text-center">@lang('Giờ ra')</th>
            <th class="tr_thead_list text-center">@lang('Lý do')</th>
            <th class="tr_thead_list"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">

                        <a href="{{route('admin.staff.show', $item['staff_id'])}}" target="_blank">
                            {{$item['staff_name']}}
                        </a>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        {{ $item['branch_name'] }}
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        {{ $item['department_name'] }}
                    </td>

                    <td class="text-center" style="vertical-align: middle;">
                        {{ $item['shift_name'] }} <br>
                        <b>
                            ({{ \Carbon\Carbon::createFromFormat('H:s:i', $item['working_time'])->format('H:s') }}
                            - {{ \Carbon\Carbon::createFromFormat('H:s:i', $item['working_end_time'])->format('H:s') }})
                        </b>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if(isset($item['working_day']))
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('d/m/Y') }}
                        @endif
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if(isset($item['check_in_time']))
                            {{-- {{ $item['check_in_day'] }} <br> --}}
                            {{ $item['check_in_time'] }}
                        @endif

                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if(isset($item['check_out_time']))
                            {{-- {{ $item['check_out_day'] }} <br> --}}
                            {{ $item['check_out_time'] }}
                        @endif

                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        @if($item['number_late_time'] > 0)
                            @if($item['is_approve_late'] == 1)
                                <span>{{ $item['approve_late_name'] }} @lang('Đã duyệt đi trễ')</span><br>
                            @elseif ($item['number_late_time'] > session()->get('late_check_in') && session()->get('off_check_in') <= 0 || $item['number_late_time'] < session()->get('off_check_in'))
                                <span>@lang('Nhân viên đi trễ')</span><br>
                            @elseif($item['number_late_time'] > session()->get('off_check_in') && session()->get('off_check_in') > 0)
                                <span>@lang('Hệ thống duyệt nghỉ không lương')</span><br>
                            @endif

                        @endif

                        @if($item['number_time_back_soon'] > 0)
                            @if($item['is_approve_soon'] == 1)
                                <span>{{ $item['approve_soon_name'] }} @lang('Đã duyệt ra ca sớm')</span><br>
                            @elseif($item['number_time_back_soon'] > session()->get('back_soon_check_out') && session()->get('off_check_out') <= 0 || $item['number_time_back_soon'] < session()->get('off_check_out'))
                                <span>@lang('Nhân viên ra ca sớm')</span><br>
                            @elseif($item['number_time_back_soon'] > session()->get('off_check_out') && session()->get('off_check_out') > 0)
                                <span>@lang('Hệ thống duyệt nghỉ không lương')</span><br>
                            @endif
                        @endif

                        @if(\Carbon\Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('d/m/Y') != \Carbon\Carbon::now()->format('d/m/Y'))
                            @if(!isset($item['check_out_time']))
                                <span>@lang('Chưa ra ca')</span><br>
                            @endif
                        @endif

                        @if($item['check_in_by'] > 0 && $item['check_out_by'] > 0)
                            <span>{{ $item['check_in_name'] }} @lang('Chấm công hộ')</span><br>
                        @endif
                    </td>
                    <td nowrap="" style="vertical-align: middle;">
                        @if(($item['number_late_time'] > 0 && $item['is_approve_late'] != 1) || ( $item['number_time_back_soon'] > 0 && $item['is_approve_soon'] != 1) || (\Carbon\Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('d/m/Y') != \Carbon\Carbon::now()->format('d/m/Y') && !isset($item['check_out_time'])))
                            <span class="dropdown">
                                    <a href="javascript:void(0)"
                                       class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill"
                                       data-toggle="dropdown" aria-expanded="false">
                                      <i class="la la-ellipsis-h"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-32px, 27px, 0px);">
                                        @if($item['number_late_time'] > 0)
                                            @if($item['is_approve_late'] != 1)
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   onclick="attendances.approve({{ $item['time_working_staff_id'] }}, 1)"><i
                                                            class="la la-edit"></i> @lang('Duyệt đi trễ')</a>
                                            @endif
                                        @endif
                                        @if($item['number_time_back_soon'] > 0)
                                            @if($item['is_approve_soon'] != 1)
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   onclick="attendances.approve({{ $item['time_working_staff_id'] }}, 2)"><i
                                                            class="la la-edit"></i> @lang('Duyệt về sớm')</a>
                                            @endif
                                        @endif
                                        @if(\Carbon\Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('d/m/Y') != \Carbon\Carbon::now()->format('d/m/Y'))
                                            @if(!isset($item['check_out_time']))
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   onclick="attendances.showModalCheckin({{ $item['time_working_staff_id'] }}, 2)"><i
                                                            class="la la-edit"></i>Check out</a>
                                            @endif
                                        @endif

                                    </div>
                                </span>
                        @endif
                    </td>

                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

</div>
{{ $LIST->links('helpers.paging') }}