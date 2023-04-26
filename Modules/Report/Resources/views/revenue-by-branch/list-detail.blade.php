<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('LOẠI')</th>
            <th class="tr_thead_list">@lang('TÊN SẢN PHẨM')</th>
            <th class="tr_thead_list">@lang('TIỀN ĐÃ THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('TIỀN CHƯA THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('NGÀY MUA')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>{{$item['order_code']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>
                        @if ($item['object_type'] == 'product')
                            @lang('Sản phẩm')
                        @elseif($item['object_type'] == 'service')
                            @lang('Dịch vụ')
                        @elseif($item['object_type'] == 'service_card')
                            @lang('Thẻ dịch vụ')
                        @endif
                    </td>
                    <td>{{$item['object_name']}}</td>
                    <td>
                        {{number_format($item['total_money_order_pay_success'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>
                        {{number_format($item['total_money_order_new'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
