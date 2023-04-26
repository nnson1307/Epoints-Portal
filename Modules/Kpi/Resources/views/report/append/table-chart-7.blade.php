<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>

        <tr class="ss--nowrap">
            <th colspan="2"></th>
            @if(count($listCriteria) != 0)
                @foreach($listCriteria as $item)
                    <th colspan="2">{{$item['kpi_criteria_name']}}</th>
                @endforeach
            @endif
        </tr>

        <tr class="ss--nowrap">
            <th class="ss--font-size-th">{{ __('Nhân viên') }}</th>
            <th class="ss--font-size-th">{{ __('Tổng % KPI') }}</th>
            @if(count($listCriteria) != 0)
                @foreach($listCriteria as $item)
                    <th class="ss--font-size-th">{{ __('Chỉ tiêu') }}</th>
                    <th class="ss--font-size-th">{{ __('Độ quan trọng') }}</th>
                @endforeach
            @endif

        </tr>
        </thead>

        <tbody>
        @if (isset($data) && count($data) != 0)
            @foreach ($data as $key => $item)
                @if(count($listCriteria) != 0)
                    <tr class="ss--font-size-13 ss--nowrap">
                            <td><strong>{{ $item['name'] }}</strong></td>
                            <td><strong>{{ round($item['y'],2).'%' }}</strong></td>
                        <?php $n = [] ?>
                        @foreach($listCriteria as $itemCriteria)
                            @if(isset($item['list'][$itemCriteria['kpi_criteria_id']]))
                                    <td><span class="{{$item['list'][$itemCriteria['kpi_criteria_id']]['kpi_criteria_trend'] == 1 ? 'text-success' : 'text-danger'}}">{{round($item['list'][$itemCriteria['kpi_criteria_id']]['total_kpi'],2)}}</span>/{{$item['list'][$itemCriteria['kpi_criteria_id']]['kpi_value'] }}</td>
                                    <td>{{ $item['list'][$itemCriteria['kpi_criteria_id']]['priority'].'%' }}</td>

                            @else
                                <?php $n[] = $itemCriteria['kpi_criteria_name']; ?>
                            @endif
                        @endforeach
                        @for($i = 0 ; $i < count($n) ; $i++)
                                <td></td>
                                <td></td>
                        @endfor
                    </tr>
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