<table class="table">
    <thead>
        <tr>
            <th>
                @lang('Tên chi nhánh')
            </th>
            <th>
                @lang('Mã chi nhánh')
            </th>
            <th>
                @lang('Mã đại diện')
            </th>
            <th>
                @lang('Số điện thoại')
            </th>
            <th>
                @lang('Địa chỉ')
            </th>
            @if (!isset($isShow) || $isShow == 0)
                <th>
                    @lang('Hành động')
                </th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if (isset($list) && count($list) > 0)
            @foreach ($list as $item)
                <tr>
                    <td title="{{ $item['branch_name'] }}">{{ $item['branch_name'] }}</td>
                    <td title="{{ $item['branch_code'] }}">{{ $item['branch_code'] }}</td>
                    <td title="{{ $item['representative_code'] }}">{{ $item['representative_code'] }}</td>
                    <td>{{ $item['phone'] }}</td>
                    <td title="{{ $item['address'] }}">{{ subString($item['address']) }}</td>
                    @if (!isset($isShow) || $isShow == 0)
                        <td class="td-trash">
                                <a href="javascript:void(0)"
                                    onclick="branch.removeItemSelected(this, '{{ $item['branch_id'] }}', '{{ $list->currentPage() ?? 1 }}')"
                                    title="Xoá"
                                    >
                                    <i class="la la-trash"></i>
                                </a>
                        </td>
                    @endif
                </tr>
            @endforeach
        @else
            <td colspan="8" class="text-center">
            </td>
        @endif
    </tbody>
</table>
@if (isset($list) && count($list) > 0)
    {{ $list->links('survey::branch.helpers.paging-branch-selected') }}
@endif
