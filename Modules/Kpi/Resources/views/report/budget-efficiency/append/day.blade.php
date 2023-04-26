<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th"></th>
            <th class="ss--font-size-th">{{__('Tiêu chí')}}</th>
            @for($i = 0 ; $i <= $totalDay ; $i++)
                <th class="ss--font-size-th">
                    {{\Carbon\Carbon::parse($data['start_month'])->addDays($i)->format('d/m')}}
                </th>
            @endfor
        </tr>
        </thead>

        <tbody>
        @if(count($arrData) != 0)
            @foreach($arrData as $item)
                @foreach($item as $key => $itemData)
                    <tr>
                        @if($key == 0)
                            <td rowspan="7">{{$itemData['name']}}</td>
                        @endif
                        <td>{{$itemData['kpi_criteria_name']}}</td>
                        @foreach($itemData['list'] as $monthData)
                            <td>
                                @if($monthData == '-')
                                    {{$monthData}}
                                @else
                                    {{number_format((double)$monthData)}}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        @else
            <tr>
                <td align="center" colspan="100">{{ __('Chưa có dữ liệu') }}</td>
            </tr>
        @endif

        </tbody>
    </table>
</div>