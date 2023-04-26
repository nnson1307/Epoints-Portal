<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th"> </th>
            @foreach($data['data'] as $item)
                <th colspan="4" class="ss--font-size-th text-center">{{$item['name']}}</th>
            @endforeach
        </tr>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th text-center">{{ __('Năm')}} {{$year}}</th>
            @foreach($data['data'] as $item)
                <th class="ss--font-size-th text-center">{{ __('Tổng % KPI') }}</th>
                <th class="ss--font-size-th text-center">{{ __('Tên tiêu chí') }}</th>
                <th class="ss--font-size-th text-center">{{ __('Chỉ tiêu') }}</th>
                <th class="ss--font-size-th text-center">{{ __('Độ quan trọng') }}</th>
            @endforeach
        </tr>
        </thead>

        <tbody>
        @if (isset($data['data']) && count($data['data']) != 0)
            <?php $tmp = 0 ?>
            @for($i = 1 ; $i <= 12 ; $i++ )
                <tr class="ss--font-size-13 ss--nowrap">
                    <td rowspan="{{$rowSpan == 0 ? 1 : $rowSpan}}">{{__('Tháng')}} {{$i}}</td>
                    <?php $n = 0 ; ?>
                    @foreach($data['data'] as $key => $item)
                        <?php $nCriteria = [] ?>
                        <td rowspan="{{$rowSpan == 0 ? 1 : $rowSpan}}">{{isset($detailKpi[$item['department_id'].'_'.$i]) ? round((double)$detailKpi[$item['department_id'].'_'.$i]['total_kpi'],2) : 0}}%</td>
                        @if(isset($listCriteria[$item['department_id'].'_'.$i]))
                            @foreach($listCriteria[$item['department_id'].'_'.$i] as $itemCriteria)
                                @if($n == 0)
                                    @if(isset($data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']]))
                                        <td class="text-center"><strong>{{$data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['unit_name'] }}</strong></td>
                                        <td class="text-center"><span class="{{$data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['kpi_criteria_trend'] == 1 ? 'text-success' : 'text-danger'}}">{{round($data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['total_kpi'],2)}}</span>/{{$data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['kpi_value'] }}</td>
                                        <td class="text-center">{{ $data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['priority'].'%' }}</td>
                                    @else
                                        <?php $nCriteria[] = $itemCriteria['kpi_criteria_name']; ?>
                                    @endif
                                @endif
                                <?php $n = 1; ?>
                            @endforeach
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        <?php $n = 1; ?>
                        @for($iCriteria = 0 ; $iCriteria < count($nCriteria) ; $iCriteria++)
                            <td class="text-center"><strong>{{$nCriteria[$iCriteria]}}</strong></td>
                            <td></td>
                            <td></td>
                        @endfor
                    @endforeach
                </tr>

                @for($iTmp = 1 ; $iTmp <= $rowSpan-1 ; $iTmp++ )
                    <?php $tmp = 0 ?>
                    <tr class="ss--font-size-13 ss--nowrap">
                        @foreach($data['data'] as $key => $item)
                            <?php $nCriteria = [] ?>
                            @if(isset($listCriteria[$item['department_id'].'_'.$i]))
                                @foreach($listCriteria[$item['department_id'].'_'.$i] as $itemCriteria)
                                    @if($tmp == $iTmp)
                                        @if(isset($data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']]))
                                            <td class="text-center"><strong>{{$data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['unit_name'] }}</strong></td>
                                            <td class="text-center"><span class="{{$data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['kpi_criteria_trend'] == 1 ? 'text-success' : 'text-danger'}}">{{round($data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['total_kpi'],2)}}</span>/{{$data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['kpi_value'] }}</td>
                                            <td class="text-center">{{ $data['month'][$i]['list'][$item['department_id'].'_'.$i.'_'.$itemCriteria['kpi_criteria_id']][0]['priority'].'%' }}</td>
                                        @else
                                            <?php $nCriteria[] = $itemCriteria['kpi_criteria_name']; ?>
                                        @endif
                                    @endif
                                    <?php $tmp += 1 ?>
                                @endforeach
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                            @for($iCriteria = 0 ; $iCriteria < count($nCriteria) ; $iCriteria++)
                                <td class="text-center"><strong>{{$nCriteria[$iCriteria]}}</strong></td>
                                <td></td>
                                <td></td>
                            @endfor
                        @endforeach
                    </tr>
                @endfor
            @endfor
        @else
            <tr>
                <td align="center" colspan="30">{{ __('Chưa có dữ liệu') }}</td>
            </tr>
        @endif

        </tbody>
    </table>
</div>