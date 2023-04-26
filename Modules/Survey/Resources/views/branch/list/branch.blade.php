<table class="table">
    <thead>
    <tr>
        <th style="width: 20px">
            <label class="kt-checkbox kt-checkbox--bold">
                <input type="checkbox" onclick="branch.checkedAllItem(this)">
                <span></span>
            </label>
        </th>
        <th>@lang('Tên chi nhánh')</th>
        <th><p>@lang('Mã chi nhánh')</p></th>
        <th><p>@lang('Mã đại diện')</p></th>
    </tr>
    </thead>
    <tbody id="tbody-add-user">
    @if(isset($list) && count($list) > 0)
        @foreach($list as $item)
            <tr>
                <td>
                    <label class="kt-checkbox kt-checkbox--bold">
                        <input class="checkbox_item"
                               type="checkbox"
                               onclick="branch.checkedOneItem(this, '{{$item['branch_id']}}')"
                                {{isset($itemTemp[$item['branch_id']]) ? 'checked' : ''}}>
                        <input type="hidden" value="{{$item['branch_id']}}" class="item_id">
                        <span></span>
                    </label>
                </td>
                <td>{{ $item['branch_name'] }}</td>
                <td>{{ $item['branch_code'] }}</td>
                <td>{{ $item['representative_code'] }}</td>
            </tr>
        @endforeach
    @else
        <td colspan="5" class="text-center">
            @lang('Không có dữ liệu')
        </td>
    @endif
    </tbody>
</table>
@if(isset($list))
    {{$list->links('survey::branch.helpers.paging-branch-popup')}}
@endif
