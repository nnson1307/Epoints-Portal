<table class="table table-striped m-table m-table--head-bg-default">
    <thead class="bg">
        <tr>
            <th class="tr_thead_list" style="width: 20px">
                <label class="kt-checkbox kt-checkbox--bold">
                    <input type="checkbox" onclick="branch.checkedAllItemStaff(this)">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">@lang('Mã nhân viên')</th>
            <th class="tr_thead_list">@lang('Tên nhân viên')</th>
            <th class="tr_thead_list">@lang('Số điện thoại')</th>
            <th class="tr_thead_list">@lang('Địa chỉ')</th>
        </tr>
    </thead>
    <tbody id="tbody-add-user">
        @if (isset($list) && count($list) > 0)
            @foreach ($list as $item)
                <tr>
                    <td>
                        <label class="kt-checkbox kt-checkbox--bold">
                            <input class="checkbox_item" type="checkbox"
                                onclick="branch.checkedOneItemStaff(this, '{{ $item['staff_id'] }}')"
                                {{ isset($itemTemp[$item['staff_id']]) ? 'checked' : '' }}>
                            <input type="hidden" value="{{ $item['staff_id'] }}" class="item_id">
                            <span></span>
                        </label>
                    </td>
                    <td>{{ $item['staff_code'] }}</td>
                    <td>{{ $item['full_name'] }}</td>
                    <td>{{ $item['phone1'] ?? $item['phone2'] }}</td>
                    <td>{{ $item['address'] }}</td>
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
    {{ $list->links('survey::branch.helpers.paging-staff-popup') }}
@endif
