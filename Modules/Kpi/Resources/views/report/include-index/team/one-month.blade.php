<div class="table-responsive">
    <table class="table table-bordered m-table m-table--border-success">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th text-center">{{ __('Nhóm') }}</th>
            <th class="ss--font-size-th text-center">{{ __('Tổng % KPI') }}</th>
            <th class="ss--font-size-th text-center">{{ __('Tên tiêu chí') }}</th>
            <th class="ss--font-size-th text-center">{{ __('Chỉ tiêu') }}</th>
            <th class="ss--font-size-th text-center">{{ __('Độ quan trọng') }}</th>
        </tr>
        </thead>

        <tbody>
        @if (isset($data) && count($data) > 0)
            @foreach ($data as $key => $item)
                @if(count($item['kpi_detail']) > 0)
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td class="text-center" rowspan="{{count($item['kpi_detail']) + 1}}"><strong>{{ $item['team_name'] }}</strong></td>
                        <td class="text-center" rowspan="{{count($item['kpi_detail']) + 1}}">
                            <strong>{{ round($item['total_percent'],2).'%' }}</strong></td>
                    </tr>
                    @foreach($item['kpi_detail'] as $v)
                        <tr>
                            <td class="text-center">{{$v['kpi_criteria_name']}}</td>
                            <td class="text-center">
                                <span style="color: red;">
                                    {{number_format($v['total'], 2)}}
                                </span>
                                / {{number_format($v['kpi_value'], 2)}}
                            </td>
                            <td class="text-center">{{$v['priority']}} %</td>
                        </tr>
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