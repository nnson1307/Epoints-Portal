<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('MÃ CHI PHÍ GIAO HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN CHI PHÍ GIAO HÀNG')</th>
            <th class="tr_thead_list">@lang('CHI PHÍ')</th>
            <th class="tr_thead_list">@lang('MẶC ĐỊNH')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
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
                        <a>
                            {{$item['delivery_cost_code']}}
                        </a>
                    </td>
                    <td>{{$item['delivery_cost_name']}}</td>
                    <td>{{number_format($item['delivery_cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td class="text_middle">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" class="manager-btn" {{($item['is_system']==1)?'checked':''}} disabled>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}
                    </td>

                    <td>
{{--                        @if(in_array('delivery-cost.edit', session('routeList')))--}}
                            <a href="{{route('delivery-cost.edit', $item['delivery_cost_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
{{--                        @endif--}}
{{--                        @if(in_array('delivery-cost.destroy', session('routeList')))--}}
                            @if($item['is_system'] == 0)
                            <a href="javascript:void(0)" onclick="list.delete({{$item['delivery_cost_id']}})"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                            @endif
{{--                        @endif--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
