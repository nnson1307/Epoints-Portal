<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">Trạng thái</th>
            <th class="tr_thead_list">Team Marketing</th>
            <th class="tr_thead_list">Đường dẫn google sheet</th>
            <th class="tr_thead_list">Phòng ban</th>
            <th class="tr_thead_list">Phân bổ xoay vòng tự động</th>
            <th class="tr_thead_list">Hành động</th>

        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{($LIST->currentPage() - 1)* $LIST->perPage() + $key + 1 }}</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" {{$item['is_active'] == 1 ? 'checked' : ''}} disabled="" class="manager-btn">
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>
                        {{$item['team_name']}}
                    </td>
                    <td><a href="{{$item['link']}}">{{$item['link']}}</a> </td>
                    <td>
                        @foreach($item['list_department'] as $itemDepartment)
                            <span class="background-department">{{$itemDepartment['department_name']}}</span>
                        @endforeach
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" {{$item['is_rotational_allocation'] == 1 ? 'checked' : ''}} disabled="" class="manager-btn">
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>
                        <a href="javascript:void(0)" onclick="config.showPopup('{{$item['cpo_customer_lead_config_source_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                           onclick="config.remove('{{$item['cpo_customer_lead_config_source_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Xóa')">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">{{__('Không có dữ liệu')}}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
