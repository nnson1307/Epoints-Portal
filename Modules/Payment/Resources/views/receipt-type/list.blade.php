<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ LOẠI PHIẾU THU')</th>
            <th class="tr_thead_list">@lang('TÊN LOẠI (VI)')</th>
            <th class="tr_thead_list">@lang('TÊN LOẠI (EN)')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('HỆ THỐNG')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$item['receipt_type_code']}}</td>
                    <td>{{$item['receipt_type_name_vi']}}</td>
                    <td>{{$item['receipt_type_name_en']}}</td>
                    <td>
                        @if ($item['is_system'] == 0)
                            @if ($item['is_active'] == 1)
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox" class="manager-btn" checked
                                               onchange="listReceiptType.updateStatus('{{$item['receipt_type_id']}}', 0)">
                                        <span></span>
                                    </label>
                                </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox" class="manager-btn"
                                               onchange="listReceiptType.updateStatus('{{$item['receipt_type_id']}}', 1)">
                                        <span></span>
                                    </label>
                                </span>
                            @endif
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox" class="manager-btn" {{$item['is_active']==1?'checked':''}} disabled>
                                        <span></span>
                                    </label>
                                </span>
                        @endif
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" class="manager-btn" {{($item['is_system']==1)?'checked':''}} disabled>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if ($item['is_system'] == 0)
                            <a href="{{route('receipt-type.edit', $item['receipt_type_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                            {{--                        @if(in_array('receippt.delete', session('routeList')))--}}
                            <a href="javascript:void(0)"
                               onclick="listReceiptType.delete('{{$item['receipt_type_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                            {{--                        @endif--}}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}