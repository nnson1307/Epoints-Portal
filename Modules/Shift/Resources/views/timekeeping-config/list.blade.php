<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('Loại chấm công')</th>
            <th class="tr_thead_list">@lang('Tọa độ')</th>
            <th class="tr_thead_list">@lang('Tên wifi')</th>
            <th class="tr_thead_list">@lang('Địa chỉ IP')</th>
            <th class="tr_thead_list">@lang('CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('Trạng thái')</th>
            <th class="tr_thead_list">@lang('Ghi chú')</th>
            <th class="tr_thead_list">@lang('Hành động')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $k => $item)
                <tr>
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>@if($item['timekeeping_type'] == 'wifi') @lang('Wifi')  @else @lang('GPS')  @endif</td>
                    <td>@if($item['timekeeping_type'] == 'wifi')  @else {{$item['latitude']}},{{$item['longitude']}}  @endif</td>
                    <td>{{$item['wifi_name']}}</td>
                    <td>{{$item['wifi_ip']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           {{$item['is_actived'] == 1 ? 'checked': ''}} class="manager-btn"
                                           onchange="listTimekeepingConfig.changeStatus(this, '{{$item['timekeeping_config_id']}}')">
                                    <span></span>
                                </label>
                            </span>
                    </td>
                    <td>{!! str_limit($item['note'],100,'...') !!}</td>
                    <td>

                        <a href="javascript:void(0)"
                           onclick="edit.popupEdit('{{$item['timekeeping_config_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                           onclick="listTimekeepingConfig.remove('{{$item['timekeeping_config_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Xóa')">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
