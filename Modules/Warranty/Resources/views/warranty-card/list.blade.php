<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>#</th>
            <th class="tr_thead_list">@lang('HÀNH ĐỘNG')</th>
            <th class="tr_thead_list">@lang('MÃ THẺ BẢO HÀNH')</th>
            <th class="tr_thead_list">@lang('ĐỐI TƯỢNG BẢO HÀNH')</th>
            <th class="tr_thead_list text-center">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list text-center">@lang('SỐ LẦN ĐƯỢC BẢO HÀNH')</th>
            <th class="tr_thead_list">@lang('NGÀY KÍCH HOẠT')</th>
            <th class="tr_thead_list">@lang('NGÀY HẾT HẠN')</th>
            {{--<th class="tr_thead_list">@lang('NGÀY TẠO')</th>--}}
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>
                        @if($item['status'] == 'new')
                            <a href="{{route('warranty-card.edit', $item['warranty_card_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="listCard.active('{{$item['warranty_card_id']}}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Kích hoạt')">
                                <i class="la la-check"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="listCard.cancel('{{$item['warranty_card_id']}}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Huỷ')">
                                <i class="la la-scissors"></i>
                            </a>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('warranty-card.show', $item['warranty_card_id'])}}">
                            {{$item['warranty_card_code']}}
                        </a>
                    </td>
                    <td>
                        {{$item['object_name']}}
                    </td>
                    <td class="text-center">
                        @switch($item['status'])
                            @case('new')
                            <span class="m-badge m-badge--success m-badge--wide"
                                  style="width: 80%">{{__('Mới')}}</span>
                            @break
                            @case('actived')
                            <span class="m-badge m-badge--info m-badge--wide"
                                  style="width: 80%"> {{__('Kích hoạt')}} </span>
                            @break
                            @case('cancel')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 80%">{{__('Huỷ')}}</span>
                            @break
                            @case('finish')
                            <span class="m-badge m-badge--primary m-badge--wide"
                                  style="width: 80%">{{__('Hoàn thành')}} </span>
                            @break
                        @endswitch
                    </td>
                    <td class="text-center">{{$item['quota'] != 0 ? $item['quota'] : __('Vô hạn')}}</td>
                    <td>
                        @if (in_array($item['status'], ['actived', 'finish']))
                            {{$item['date_actived'] ? \Carbon\Carbon::parse($item['date_actived'])->format('d/m/Y') : ''}}
                        @endif
                    </td>
                    <td>
                        @if (in_array($item['status'], ['actived', 'finish']))
                            @if ($item['date_expired'] == null)
                                {{__('Vô hạn')}}
                            @else
                                {{$item['date_expired'] ? \Carbon\Carbon::parse($item['date_expired'])->format('d/m/Y') : ''}}
                            @endif
                        @endif
                    </td>
                    {{--<td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>--}}
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
