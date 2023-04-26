
<table class="table table-bordered">
    <thead class="rowGroup">
        <tr class="text-center table-primary">
            <th scope="col" class="text-left">@lang('Nguồn lead')</th>
            @if(isset($listJourney) && count($listJourney) > 0)
                @foreach($listJourney as $item)
                    <th scope="col">{{$item['journey_name']}}</th>
                @endforeach
            @endif
        </tr>
    </thead>
    <tbody>
    @if(isset($listCustomerSource) && count($listCustomerSource) > 0)
        @foreach($listCustomerSource as $key => $item)
            <tr>
                <th scope="row">{{$item['customer_source_name']}}</th>

                @if(isset($quantity[$key]) && count($quantity[$key]) > 0)
                    @foreach($quantity[$key] as $key2 => $item)
                        <td>
                            @if($quantity[$key][$key2] > 0)
                                <a href="javascript:void(0)" class="m-card-user__name line-name font-name"
                                   onclick="lead.renderPopupReportConvert('{{$listCustomerSource[$key]['customer_source_id']}}','{{(count($listJourney) > $key2 ? $listJourney[$key2]['journey_code'] : '')}}');">
                                    {{$quantity[$key][$key2] }}
                                </a>
                            @else
                                {{$quantity[$key][$key2] }}
                            @endif
                        </td>
                    @endforeach
                @endif
            </tr>
        @endforeach
    @endif
    </tbody>
</table>