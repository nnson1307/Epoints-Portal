<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">{{ __('Phòng ban') }}</th>
            <th class="ss--font-size-th">{{ __('Tổng % KPI') }}</th>
            <th class="ss--font-size-th">{{ __('Tên tiêu chí') }}</th>
            <th class="ss--font-size-th">{{ __('Chỉ tiêu') }}</th>
            <th class="ss--font-size-th">{{ __('Độ quan trọng') }}</th>
        </tr>
        </thead>

        <tbody>
        @if (isset($data) && count($data) != 0)
            @foreach ($data as $key => $item)
                @if(count($listCriteria) != 0)
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td rowspan="{{count($listCriteria)+1}}"><strong>{{ $item['name'] }}</strong></td>
                        <td rowspan="{{count($listCriteria)+1}}"><strong>{{ round($item['y'],2).'%' }}</strong></td>
                    </tr>
                    <?php $n = [] ?>
                    @foreach($listCriteria as $itemCriteria)
                        @if(isset($item['list'][$itemCriteria['kpi_criteria_id']]))
                            <tr>
                                <td><strong>{{$item['list'][$itemCriteria['kpi_criteria_id']]['unit_name'] }}</strong></td>
                                <td><span class="{{$item['list'][$itemCriteria['kpi_criteria_id']]['kpi_criteria_trend'] == 1 ? 'text-success' : 'text-danger'}}">{{round($item['list'][$itemCriteria['kpi_criteria_id']]['total_kpi'],2)}}</span>/{{$item['list'][$itemCriteria['kpi_criteria_id']]['kpi_value'] }}</td>
                                <td>{{ $item['list'][$itemCriteria['kpi_criteria_id']]['priority'].'%' }}</td>
                            </tr>
                        @else
                            <?php $n[] = $itemCriteria['kpi_criteria_name']; ?>
                        @endif
                    @endforeach
                    @for($i = 0 ; $i < count($n) ; $i++)
                        <tr>
                            <td><strong>{{$n[$i]}}</strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
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