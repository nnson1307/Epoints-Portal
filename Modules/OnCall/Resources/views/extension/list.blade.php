<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('SỐ EXTENSION')</th>
            <th class="tr_thead_list">@lang('NHÂN VIÊN')</th>
            <th class="tr_thead_list">@lang('USER AGENT')</th>
            <th class="tr_thead_list">@lang('EMAIL')</th>
            <th class="tr_thead_list">@lang('SĐT')</th>
            <th class="tr_thead_list">@lang('NHÂN VIÊN ĐƯỢC PHÂN BỔ')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$item['extension_number']}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>{{$item['user_agent']}}</td>
                    <td>{{$item['email']}}</td>
                    <td>{{$item['phone']}}</td>
                    <td>{{$item['staff_name']}}</td>
                    <td>
                        @if ($item['status'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn" checked
                                           onchange="index.updateStatusExtension('{{$item['extension_id']}}', 0)">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn"
                                           onchange="index.updateStatusExtension('{{$item['extension_id']}}', 1)">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if(in_array('extension.modal-assign', session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="list.showModalAssign('{{$item['extension_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Phân công')">
                                <i class="la la-user-plus"></i>
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
