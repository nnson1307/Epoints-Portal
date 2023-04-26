<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('TÊN DỊCH VỤ')</th>
            <th class="tr_thead_list">@lang('NGÀY MUA HÀNG')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>{{$item['order_code']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['object_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
