
@if(count($LIST) > 0)
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('STT')</th>
            <th class="tr_thead_list">@lang('Cơ hội bán hàng')</th>
            <th class="tr_thead_list">@lang('Giá trị')</th>
            <th class="tr_thead_list">@lang('HÀNH TRÌNH')</th>
            <th class="tr_thead_list">@lang('Thời gian tạo')/@lang('Cập nhật')</th>
            <th class="tr_thead_list">@lang('Thời gian phân công')/<br>@lang('Yêu cầu hoàn thành')</th>
            <th class="tr_thead_list">@lang('NGƯỜI TẠO')/<br>@lang('Người quản lý')</th>

            {{-- <th class="tr_thead_list">@lang('NGÀY DỰ KIẾN KẾT THÚC')</th>
            <th class="tr_thead_list">@lang('NGƯỜI TẠO')</th>
            <th class="tr_thead_list">@lang('MÃ DEAL')</th>
            <th class="tr_thead_list">@lang('PIPELINE')</th>
            <th class="tr_thead_list">@lang('HÀNH TRÌNH')</th>
            <th class="tr_thead_list">@lang('SẢN PHẨM')</th>
            <th class="tr_thead_list">@lang('TỔNG TIỀN')</th> --}}
        </tr>
        </thead>
        <tbody>
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ (($page-1)*6 + $key + 1) }}</td>
                    @else
                        <td class="ss--font-size-13">{{ ($key + 1) }}</td>
                    @endif
                    <td>
                        <div>[{{ $item['deal_code'] }}] - <span class="text-bold">{{ $item['journey_name'] }}</span></div>
                        <div>{{ $item['full_name'] }}</div>
                        @if($item['closing_date'] != null)
                        <div>
                            {{\Carbon\Carbon::createFromFormat('Y-m-d', $item['closing_date'])->format('d/m/Y')}}
                        </div>
                        @endif
                    </td>
                    <td>{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{$item['pipeline_name']}}</td>
                    <td>
                        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('d/m/Y H:i')}} <br/>
                        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['updated_at'])->format('d/m/Y H:i')}}
                    </td>
                    <td>
                        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('d/m/Y H:i')}} <br/>
                        {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['updated_at'])->format('d/m/Y H:i')}}
                    </td>
                    <td><div>{{ $item['full_name'] }}</div></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}

@else
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('STT')</th>
            <th class="tr_thead_list">@lang('Cơ hội bán hàng')</th>
            <th class="tr_thead_list">@lang('Giá trị')</th>
            <th class="tr_thead_list">@lang('HÀNH TRÌNH')</th>
            <th class="tr_thead_list">@lang('Thời gian tạo')/@lang('Cập nhật')</th>
            <th class="tr_thead_list">@lang('Thời gian phân công')/<br>@lang('Yêu cầu hoàn thành')</th>
            <th class="tr_thead_list">@lang('NGƯỜI TẠO')/<br>@lang('Người quản lý')</th>

            {{-- <th class="tr_thead_list">@lang('NGÀY DỰ KIẾN KẾT THÚC')</th>
            <th class="tr_thead_list">@lang('NGƯỜI TẠO')</th>
            <th class="tr_thead_list">@lang('MÃ DEAL')</th>
            <th class="tr_thead_list">@lang('PIPELINE')</th>
            <th class="tr_thead_list">@lang('HÀNH TRÌNH')</th>
            <th class="tr_thead_list">@lang('SẢN PHẨM')</th>
            <th class="tr_thead_list">@lang('TỔNG TIỀN')</th> --}}
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">Không có dữ liệu</td>
            </tr>
        </tbody>
    </table>
</div>
@endif