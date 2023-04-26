<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN DỊCH VỤ')</th>
            <th class="tr_thead_list">@lang('NHÓM DỊCH VỤ')</th>
            <th class="tr_thead_list">@lang('TÊN CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('DOANH THU')</th>
            <th class="tr_thead_list">@lang('NGÀY MUA HÀNG')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>{{$item['order_code']}}</td>
                    <td>
                        @if(in_array('admin.customer.detail',session('routeList')))
                            <a href="{{route("admin.customer.detail",$item['customer_id'])}}" target="_blank">
                                {{$item['customer_name']}}
                            </a>
                        @else
                            {{$item['customer_name']}}
                        @endif
                    </td>
                    <td>{{$item['object_name']}}</td>
                    <td>{{$item['service_category_name']}}</td>
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
