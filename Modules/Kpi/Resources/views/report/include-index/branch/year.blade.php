@if (count($arrBranch) > 0)
    <div class="table-responsive">
        <table class="table table-bordered m-table m-table--border-success">
            <thead>
            <tr>
                <th></th>

                @foreach($arrBranch as $v)
                    <th colspan="4" class="text-center">{{$v['branch_name']}}</th>
                @endforeach

            </tr>

            <tr>
                <th class="text-center">@lang('Năm') {{$getMonth['year']}}</th>

                @foreach($arrBranch as $v)
                    <th class="text-center">@lang('Tổng % KPI')</th>
                    <th class="text-center">@lang('Tên tiêu chí')</th>
                    <th class="text-center">@lang('Chỉ tiêu')</th>
                    <th class="text-center">@lang('Độ quan trọng')</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($data as $k => $v)
                    <?php $index = 0 ?>
                @if(count($v['data_detail']) > 0)
                    <tr>
                        <td class="text-center" rowspan="{{$v['number_row'] > 0 ? $v['number_row'] + 1 : 1}}">
                            @lang('Tháng') {{$v['month']}}
                        </td>
                    </tr>
                    @for($z = 0; $z < count($v['criteria_name']); $z++)

                        <tr>
                            @for($x = 0; $x < count($arrBranch); $x++)
                                @if($index == 0)
                                    <td class="text-center" rowspan="{{$v['number_row'] > 0 ? $v['number_row'] : 1}}">
                                        {{round($v['data_detail'][$x]['total_percent'], 2)}} %
                                    </td>
                                @endif
                                    <?php $dataKPI = $v['data_detail'][$x]['data'][$z] ?>

                                @if($dataKPI != null)
                                    <td class="text-center">{{$dataKPI['kpi_criteria_name']}}</td>
                                    <td class="text-center">
                                        <span style="color: red;">
                                            {{number_format($dataKPI['total'], 2)}}
                                        </span>
                                        / {{number_format($dataKPI['kpi_value'], 2)}}
                                    </td>
                                    <td class="text-center">{{$dataKPI['priority']}} %</td>

                                @else
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                @endif

                            @endfor
                        </tr>
                            <?php $index += 1; ?>
                    @endfor
                @else
                    <tr>
                        <td class="text-center">@lang('Tháng') {{$v['month']}}</td>
                        @for($x = 0; $x < count($arrBranch); $x++)
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                        @endfor
                    </tr>

                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center">
        {{ __('Chưa có dữ liệu') }}
    </div>
@endif

