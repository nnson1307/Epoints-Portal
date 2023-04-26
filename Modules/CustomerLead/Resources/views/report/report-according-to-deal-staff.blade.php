
<table class="table table-bordered">
    <thead class="rowGroup">
    <tr class="text-center table-primary">
        <th scope="col" class="text-right">@lang('Nhân viên')</th>
        @if(isset($listJourney) && count($listJourney) > 0)
            @foreach($listJourney as $item)
                <th scope="col" colspan="2">{{$item['journey_name']}}</th>
            @endforeach
        @endif
        <th scope="col" colspan="2">@lang('Tổng')</th>
    </tr>
    <tr class="text-center">
        <th scope="col" class="text-left"></th>
        @if(isset($listJourney) && count($listJourney) > 0)
            @foreach($listJourney as $item)
                <th scope="col">@lang('Số lượng')</th>
                <th scope="col">%</th>
            @endforeach
        @endif
        <th scope="col">@lang('Số lượng')</th>
        <th scope="col">%</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row">@lang('Tổng')</th>
        @if(isset($quantity['sumColumn']) && count($quantity['sumColumn']) > 0)
            @foreach($quantity['sumColumn'] as $key => $item)
                <td>
                    @if($item > 0)
                        <a href="javascript:void(0)" class="m-card-user__name line-name font-name"
                           onclick="deal.renderPopupReportDealStaff('no_staff','{{(count($listJourney) > $key ? $listJourney[$key]['journey_code'] : '')}}');">
                            {{$item}}
                        </a>
                    @else
                        {{$item}}
                    @endif
                </td>
                <td>
                    @if($item > 0)
                        100
                    @else
                        0
                    @endif
                </td>
            @endforeach
        @endif
    </tr>
    @if(isset($listStaff) && count($listStaff) > 0)
        @foreach($listStaff as $key => $item)
            <tr>
                <th scope="row">{{$item['full_name']}}</th>

                @if(isset($quantity[$key]) && count($quantity[$key]) > 0)
                    @foreach($quantity[$key] as $key2 => $item)
                        <td>
                            @if($quantity[$key][$key2] > 0)
                                <a href="javascript:void(0)" class="m-card-user__name line-name font-name"
                                   onclick="deal.renderPopupReportDealStaff('{{$listStaff[$key]['staff_id']}}','{{(count($listJourney) > $key2 ? $listJourney[$key2]['journey_code'] : '')}}');">
                                    {{$quantity[$key][$key2] }}
                                </a>
                            @else
                                {{$quantity[$key][$key2] }}
                            @endif
                        </td>
                        <td>
                            @if($quantity[$key][$key2] > 0)
                                {{round($quantity[$key][$key2] * 100 / $quantity['sumColumn'][$key2], 2)}}
                            @else
                                0
                            @endif
                        </td>
                    @endforeach
                @endif
            </tr>
        @endforeach
    @endif
    </tbody>
</table>