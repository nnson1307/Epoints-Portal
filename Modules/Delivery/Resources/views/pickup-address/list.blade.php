<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('MÃ ĐỊA CHỈ LẤY HÀNG')</th>
            <th class="tr_thead_list">@lang('ĐỊA CHỈ')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
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
                            {{$item['pickup_address_code']}}
                        </a>
                    </td>
                    <td>{{$item['address']}}</td>
                    <td class="text_middle">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" class="manager-btn"
                                       {{($item['is_actived']==1)?'checked':''}} disabled>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}
                    </td>

                    <td>
                        @if(in_array('pickup-address.edit', session('routeList')))
                        <a href="{{route('pickup-address.edit', $item['pickup_address_id'])}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
                        @endif
                        @if(in_array('pickup-address.destroy', session('routeList')))
                        <a href="javascript:void(0)" onclick="list.remove('{{$item['pickup_address_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Xóa')">
                            <i class="la la-trash"></i>
                        </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
