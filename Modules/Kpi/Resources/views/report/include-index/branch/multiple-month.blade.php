<div class="table-responsive">
    <table class="table table-bordered m-table m-table--border-success">
        <thead>
        <tr>
            <th></th>
            @if (count($getMonth['arrMonth']) > 0)
                @foreach($getMonth['arrMonth'] as $v)
                    <th colspan="4" class="text-center">@lang('Tháng') {{$v['month']}}</th>
                @endforeach
            @endif
        </tr>

        <tr>
            <th class="text-center">@lang('Chi nhánh')</th>

            @if (count($getMonth['arrMonth']) > 0)
                @foreach($getMonth['arrMonth'] as $v)
                    <th class="text-center">@lang('Tổng % KPI')</th>
                    <th class="text-center">@lang('Tên tiêu chí')</th>
                    <th class="text-center">@lang('Chỉ tiêu')</th>
                    <th class="text-center">@lang('Độ quan trọng')</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>

        @if (count($data) > 0)
            @foreach($data as $k => $v)
                <tr>
                    <th class="text-center" rowspan="{{count($v['total_criteria_name']) + 1}}">{{$v['branch_name']}}</th>
                </tr>

                <tr>
                    @if (count($getMonth['arrMonth']) > 0)
                        @foreach($getMonth['arrMonth'] as $v1)
                            <td class="text-center" rowspan="{{count($v['total_criteria_name']) + 1}}">
                                {{isset($v['data_detail'][$v1['month']]['total_percent']) ? round($v['data_detail'][$v1['month']]['total_percent'], 2) : 0}}
                                %
                            </td>

                            @if (count($v['total_criteria_name']) > 0)
                                @foreach($v['total_criteria_name'] as $k2 => $v2)
                                    @if ($k2 == 0)
                                        @if (isset($v['data_detail'][$v1['month']]['detail'][$v2]))
                                            <td class="text-center">{{$v['data_detail'][$v1['month']]['detail'][$v2]['kpi_criteria_name']}}</td>
                                            <td class="text-center">
                                                 <span style="color: red;">
                                                     {{number_format($v['data_detail'][$v1['month']]['detail'][$v2]['total'], 2)}}
                                                                                            </span>
                                                / {{number_format($v['data_detail'][$v1['month']]['detail'][$v2]['kpi_value'], 2)}}
                                            </td>
                                            <td class="text-center">{{$v['data_detail'][$v1['month']]['detail'][$v2]['priority']}} %</td>
                                        @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </tr>


                @if (count($v['total_criteria_name']) > 0)
                    @foreach($v['total_criteria_name'] as $k1 => $v1)
                        @if ($k1 > 0)
                            <tr>
                                @foreach($getMonth['arrMonth'] as $k2 => $v2)

                                    @if (isset($v['data_detail'][$v2['month']]['detail'][$v1]))
                                        <td class="text-center">{{$v['data_detail'][$v2['month']]['detail'][$v1]['kpi_criteria_name']}}</td>
                                        <td class="text-center">
                                                 <span style="color: red;">
                                                     {{number_format($v['data_detail'][$v2['month']]['detail'][$v1]['total'], 2)}}
                                                                                            </span>
                                            / {{number_format($v['data_detail'][$v2['month']]['detail'][$v1]['kpi_value'], 2)}}
                                        </td>
                                        <td class="text-center">{{$v['data_detail'][$v2['month']]['detail'][$v1]['priority']}} %</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                @endforeach
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @else
            <tr>
                <td align="center" colspan="30">{{ __('Chưa có dữ liệu') }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>