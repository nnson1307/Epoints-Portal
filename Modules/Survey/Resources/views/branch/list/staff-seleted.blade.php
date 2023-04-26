<table class="table table-striped m-table m-table--head-bg-default">
    <thead class="bg">
        <tr>
            <th>#</th>
            <th class="tr_thead_list">
                @lang('Mã nhân viên')
            </th>
            <th class="tr_thead_list">
                @lang('Tên nhân viên')
            </th>
            <th class="tr_thead_list">
                @lang('Số điện thoại')
            </th>
            <th class="tr_thead_list">
                @lang('Địa chỉ')
            </th>
            <th class="tr_thead_list">
                @lang('Chi nhánh')
            </th>
            <th class="tr_thead_list">
                @lang('Phòng ban')
            </th>
            <th class="tr_thead_list">
                @lang('Chức vụ')
            </th>
            <th class="tr_thead_list">
                @lang('Trạng thái')
            </th>
            @if (!isset($isShow) || $isShow == 0)
                <th class="tr_thead_list text-center">
                    @lang('Hành động')
                </th>
            @endif
        </tr>
    </thead>
    <tbody id="tbody-add-staff-seleted">
        @if (isset($list) && count($list) > 0)
            @foreach ($list as $key => $item)
                <tr>
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>{{ $item['staff_code'] }}</td>
                    <td>{{ $item['full_name'] }}</td>
                    <td>{{ $item['phone1'] ?? $item['phone2'] }}</td>
                    <td>{{ $item['address'] }}</td>
                    <td>{{ $item['branch_name'] }}</td>
                    <td>{{ $item['department_name'] }}</td>
                    <td>{{ $item['staff_title_name'] }}</td>
                    <td>{{ $item['is_deleted'] == 0 && $item['is_actived'] == 1 ? __('Hoạt động') : __('Đã huỷ') }}</td>
                    @if (!isset($isShow) || $isShow == 0)
                        <td class="td-trash">
                            <a href="javascript:void(0)"
                                onclick="branch.removeItemSelectedStaff(this, '{{ $item['staff_id'] }}', '{{ $list->currentPage() ?? 1 }}')"
                                title="Xoá">
                                <i class="la la-trash"></i>
                            </a>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
@if (isset($list))
    {{ $list->links('survey::branch.helpers.paging-staff-selected') }}
@endif
