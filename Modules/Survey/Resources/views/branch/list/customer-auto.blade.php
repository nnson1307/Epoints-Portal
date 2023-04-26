<table class="table table-striped m-table m-table--head-bg-default">
    <thead class="bg">
        <tr>
            <th></th>
            <th class="tr_thead_list">@lang('Tên nhóm khách hàng')</th>
            <th class="tr_thead_list">@lang('Loại nhóm')</th>
            <th class="tr_thead_list">@lang('Thời gian tạo')</th>
        </tr>
    </thead>
    <tbody id="tbody-add-group-customer">
        @if (isset($list) && count($list) > 0)
            @foreach ($list as $item)
                <tr>
                    <td>
                        <label class="m-radio cus">
                            <input class="checkbox_item" type="radio" value={{ $item->id }} name="checked_group">
                            <span></span>
                        </label>
                    </td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['filter_group_type'] == 'user_define' ? __('Nhóm được định nghĩa') : __('Nhóm tự động') }}
                    </td>
                    <td>{{ $item['created_at'] }}</td>
                </tr>
            @endforeach
        @else
            <td colspan="5" class="text-center">
                @lang('Không có dữ liệu')
            </td>
        @endif
    </tbody>
</table>
@if (isset($list))
    {{ $list->links('survey::branch.helpers.paging-customer-auto') }}
@endif
