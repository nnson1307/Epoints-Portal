<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th"> </th>
            @foreach($arrMonth as $item)
                <th colspan="4" class="ss--font-size-th text-center">{{ __('Tháng') }} {{$item}}</th>
            @endforeach
        </tr>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th text-center">{{ __('Nhân viên') }}</th>
            @foreach($arrMonth as $item)
                <th class="ss--font-size-th text-center">{{ __('Tổng % KPI') }}</th>
                <th class="ss--font-size-th text-center">{{ __('Tên tiêu chí') }}</th>
                <th class="ss--font-size-th text-center">{{ __('Chỉ tiêu') }}</th>
                <th class="ss--font-size-th text-center">{{ __('Độ quan trọng') }}</th>
            @endforeach
        </tr>
        </thead>

        <tbody>
        @if (isset($data) && count($data) != 0)
            <?php $tmp = 0 ?>
            @foreach ($data['categoriesList'] as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
                    @foreach($arrMonth as $keyMonth => $itemMonth)
                            @if($keyMonth == 0)
                                <td rowspan="{{$rowSpan == 0 ? 1 : $rowSpan}}" class="text-center">{{$item['full_name']}}</td>
                            @endif
                                <td rowspan="{{$rowSpan == 0 ? 1 : $rowSpan}}" class="text-center">{{$data['month'][$keyMonth]['data'][$key]}}%</td>

                            <?php $n = [] ?>
                            @if(isset($listCriteria[$item['staff_id'].'_'.$itemMonth]))
                                @foreach($listCriteria[$item['staff_id'].'_'.$itemMonth] as $itemCriteria)
                                    @if($tmp == 0)
                                        @if(isset($data['month'][$keyMonth]['list']) && isset($data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]) && count($data['month'][$keyMonth]['list']) != 0)
                                            <td class="text-center"><strong>{{$data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['unit_name'] }}</strong></td>
                                            <td class="text-center"><span class="{{$data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['kpi_criteria_trend'] == 1 ? 'text-success' : 'text-danger'}}">{{round($data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['total_kpi'],2)}}</span>/{{$data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['kpi_value'] }}</td>
                                            <td class="text-center">{{ $data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['priority'].'%' }}</td>
                                        @else
                                            <?php $n[] = $itemCriteria['kpi_criteria_name']; ?>
                                        @endif
                                        <?php $tmp = 1 ?>
                                    @endif
                                @endforeach
                            @else
                                <td><strong></strong></td>
                                <td></td>
                                <td></td>
                            @endif
                            @for($i = 0 ; $i < count($n) ; $i++)
                                    <td class="text-center"><strong>{{$n[$i]}}</strong></td>
                                    <td></td>
                                    <td></td>
                            @endfor

                    @endforeach
                </tr>
                @for($tmpRow = 1 ; $tmpRow <= $rowSpan - 1 ; $tmpRow++)
                    <?php $tmp = 0 ?>
                    <tr class="ss--font-size-13 ss--nowrap">
                        @foreach($arrMonth as $keyMonth => $itemMonth)
                            <?php $n = [] ?>

                            @if(isset($listCriteria[$item['staff_id'].'_'.$itemMonth]))
                                @foreach($listCriteria[$item['staff_id'].'_'.$itemMonth] as $itemCriteria)
                                    @if($tmp == $tmpRow)
                                        @if(isset($data['month'][$keyMonth]['list']) && isset($data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]) && count($data['month'][$keyMonth]['list']) != 0)
                                            <td class="text-center"><strong>{{$data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['unit_name'] }}</strong></td>
                                            <td class="text-center"><span class="{{$data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['kpi_criteria_trend'] == 1 ? 'text-success' : 'text-danger'}}">{{round($data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['total_kpi'],2)}}</span>/{{$data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['kpi_value'] }}</td>
                                            <td class="text-center">{{ $data['month'][$keyMonth]['list'][$item['staff_id'].'_'.$itemMonth.'_'.$itemCriteria['kpi_criteria_id']]['priority'].'%' }}</td>
                                        @else
                                            <?php $n[] = $itemCriteria['kpi_criteria_name']; ?>
                                        @endif
                                    @endif
                                    <?php $tmp += 1 ?>
                                @endforeach
                            @else
                                <td><strong></strong></td>
                                <td></td>
                                <td></td>
                            @endif
                            @for($i = 0 ; $i < count($n) ; $i++)
                                <td class="text-center"><strong>{{$n[$i]}}</strong></td>
                                <td></td>
                                <td></td>
                            @endfor

                        @endforeach
                    </tr>
                @endfor
            @endforeach
        @else
            <tr>
                <td align="center" colspan="30">{{ __('Chưa có dữ liệu') }}</td>
            </tr>
        @endif

        </tbody>
    </table>
</div>