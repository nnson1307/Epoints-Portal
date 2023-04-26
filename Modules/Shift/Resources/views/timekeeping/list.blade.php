<div id="table-scroll" class="table-scroll">
    <div class="table-wrap">
        <table class="main-table">
            <thead class="bg">
            <tr>
                <th class="fixed-side" scope="col">@lang('Nhân viên')</th>
                <th class="text-center" scope="col">@lang('Tổng ca làm')</th>
                <th class="text-center" scope="col">@lang('Tổng ca tăng ca')</th>
                <th class="text-center" scope="col">@lang('Số giờ làm yêu cầu tối thiểu')</th>
                <th class="text-center" scope="col">@lang('Tổng giờ làm')</th>
                <th class="text-center" scope="col">@lang('tổng giờ tăng ca')</th>
                <th class="text-center" scope="col">@lang('Số lần đi trễ')</th>
                <th class="text-center" scope="col">@lang('Số lần về sớm')</th>
                <th class="text-center" scope="col">@lang('Số ca nghỉ không lương')</th>
                <th class="text-center" scope="col">@lang('Số ca nghỉ có lương')</th>
                {{-- <th class="text-center" scope="col">@lang('Số ca không chấm công vào')</th>
                <th class="text-center" scope="col">
                    @lang('Số ca không chấm công ra')
                  
                </th> --}}
            </tr>
            </thead>
            <tbody>
            @if(isset($LIST) && count($LIST) > 0)
                @foreach ($LIST as $key => $v)
                    <tr>
                        <td class="fixed-side">
                            <div>
                                <a target="_blank" href="/shift/timekeeping/detail-staff?id={{ $v['staff_id'] }}&m={{ $month }}&y={{ $year }}">{{$v['full_name']}}</a>
                            </div>

                            <i class="la la-bank"></i> {{$v['branch_name']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalShift']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalShiftOt']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalHourMinShift']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalHourShift']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalHourOt']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalWorkLate']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalBackSoon']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalLeaveUnPaid']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalLeavePaid']}}
                        </td>
                        {{-- <td class="text-center">
                            {{$v['totalNotCheckIn']}}
                        </td>
                        <td class="text-center">
                            {{$v['totalNotCheckOut']}}
                        </td> --}}
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

{{ $LIST->links('helpers.paging') }}

