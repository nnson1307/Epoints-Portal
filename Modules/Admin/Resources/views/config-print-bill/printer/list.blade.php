<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('Chi nhánh')</th>
            <th class="tr_thead_list">@lang('Tên máy in')</th>
            <th class="tr_thead_list">@lang('Địa chỉ IP')</th>
            <th class="tr_thead_list">@lang('Cổng')</th>
            <th class="tr_thead_list">@lang('Mặc định')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('HÀNH ĐỘNG')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $k => $item)
                <tr>
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['printer_name']}}</td>
                    <td>{{$item['printer_ip']}}</td>
                    <td>{{$item['printer_port']}}</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               onclick="listPrinters.changePrinterDefault(this, '{{$item['print_bill_device_id']}}')"
                                               {{$item['is_default'] == 1 ? 'checked': ''}} class="manager-btn" name="">
                                        <span></span>
                                    </label>
                        </span>
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               onclick="listPrinters.changeStatus(this, '{{$item['print_bill_device_id']}}')"
                                               {{$item['is_actived'] == 1 ? 'checked': ''}} class="manager-btn" name="">
                                        <span></span>
                                    </label>
                        </span>
                    </td>
                        <td>
                        <a href="javascript:void(0)" onclick="edit.popupEdit('{{$item['print_bill_device_id']}}', false)"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                           onclick="listPrinters.remove('{{$item['print_bill_device_id']}}')"
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
