
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('Tên ngày lễ')</th>
            <th class="tr_thead_list">@lang('Ngày bắt đầu')</th>
            <th class="tr_thead_list">@lang('Ngày kết thúc')</th>
            <th class="tr_thead_list">@lang('Số ngày')</th>
            <th class="tr_thead_list">@lang('Người tạo')</th>
            <th class="tr_thead_list">@lang('Ngày tạo')</th>
            <th class="tr_thead_list"></th>
        </tr>
        </thead>
        <tbody>
            @if(isset($LIST))
                @foreach ($LIST as $key => $item)
                    <tr>
                        <td>
                            @if(isset($page))
                                {{ ($page-1)*10 + $key+1}}
                            @else
                                {{$key+1}}
                            @endif
                        </td>
                        <td>{{$item['staff_holiday_title']}}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $item['staff_holiday_start_date'])->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $item['staff_holiday_end_date'])->format('d/m/Y') }}</td>
                        <td>{{ $item['staff_holiday_number'] }}</td>
                        <td>{{ $item['staff_name'] }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $item['created_at'])->format('d/m/Y H:s:i') }}</td>
                        <td nowrap="">
                            <a href="javascript:void(0)" onclick="holiday.showModalEdit({{ $item['staff_holiday_id'] }})" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View">
                                <i class="la la-edit"></i>
                            </a>
                            <button onclick="holiday.delete({{ $item['staff_holiday_id'] }})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
                                <i class="la la-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    
</div>
{{ $LIST->links('helpers.paging') }}