<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('SỐ NGƯỜI GỌI')</th>
            <th class="tr_thead_list">@lang('SĐT NGƯỜI NHẬN')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN BẮT ĐẦU')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN KẾT THÚC')</th>
            <th class="tr_thead_list">@lang('THỜI LƯỢNG')</th>
            <th class="tr_thead_list">@lang('LOẠI CUỘC GỌI')</th>
            <th class="tr_thead_list">@lang('TÊN NGƯỜI GỌI')</th>
            <th class="tr_thead_list">@lang('TÊN NGƯỜI NHẬN')</th>
            <th class="tr_thead_list">@lang('NGUỒN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$item['extension_number']}}</td>
                    <td>{{$item['object_phone']}}</td>
                    <td>
                        @if ($item['start_time'] != null)
                            {{\Carbon\Carbon::parse($item['start_time'])->format('d/m/Y H:i:s')}}
                        @endif
                    </td>
                    <td>
                        @if ($item['end_time'] != null)
                            {{\Carbon\Carbon::parse($item['end_time'])->format('d/m/Y H:i:s')}}
                        @endif
                    </td>
                    <td>{{$item['total_reply_time']}}</td>
                    <td>
                        @if ($item['history_type'] == "out")
                            @lang('Cuộc gọi đi')
                        @else
                            @lang('Cuộc gọi đến')
                        @endif
                    </td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{$item['object_name']}}</td>
                    <td>{{$item['source_name']}}</td>
                    <td>
                        @if ($item['status'] == 0)
                            @lang('Thất bại')
                        @else
                            @lang('Thành công')
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
