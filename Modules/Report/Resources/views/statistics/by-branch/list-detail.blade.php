<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('LOẠI')</th>
            <th class="tr_thead_list">@lang('TÊN')</th>
            <th class="tr_thead_list">@lang('SỐ LƯỢNG')</th>
            <th class="tr_thead_list">@lang('NGÀY MUA HÀNG')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>{{$item['order_code']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    @if($item['object_type'] == 'product')
                        <td>{{__("Sản phẩm")}}</td>
                    @elseif($item['object_type'] == 'service')
                        <td>{{__("Dịch vụ")}}</td>
                    @elseif($item['object_type'] == 'service_card')
                        <td>{{__("Thẻ dịch vụ")}}</td>
                    @else
                        <td>{{__("Thẻ thành viên")}}</td>
                    @endif
                    <td>{{$item['object_name']}}</td>
                    <td>{{$item['quantity']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
