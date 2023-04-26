<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN SẢN PHẨM')</th>
            <th class="tr_thead_list">@lang('TÊN CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('DOANH THU')</th>
            <th class="tr_thead_list">@lang('NGÀY MUA HÀNG')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>
                        <a class="m-link" style="color:#464646" title="{{__('Chi tiết')}}" target="_blank"
                           href="{{route('admin.order.detail',$item['order_id'])}}">
                            {{$item['order_code']}}
                        </a>
                    </td>
                    <td>{{$item['object_name']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>
                        {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
