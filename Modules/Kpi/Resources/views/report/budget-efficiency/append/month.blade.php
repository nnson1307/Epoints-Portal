<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th"></th>
            <th class="ss--font-size-th">{{__('Tiêu chí')}}</th>
            @for($i = 1; $i <= 12 ; $i++)
                <th class="ss--font-size-th">{{__('Tháng')}} {{$i}}</th>
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
                <td align="center" colspan="30">{{ __('Chưa có dữ liệu') }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>