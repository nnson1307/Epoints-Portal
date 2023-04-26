<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('TÊN NHÂN VIÊN')</th>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('LOẠI')</th>
            <th class="tr_thead_list">@lang('TÊN SẢN PHẨM')</th>
            <th class="tr_thead_list">@lang('GIÁ BÁN')</th>
            <th class="tr_thead_list">@lang('SỐ LƯỢNG')</th>
            <th class="tr_thead_list">@lang('GIẢM')</th>
            <th class="tr_thead_list">@lang('THÀNH TIỀN')</th>
            <th class="tr_thead_list">@lang('NGÀY PHỤC VỤ')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>{{$item['staff_name']}}</td>
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
                    <td>{{$item['branch_name']}}</td>
                    <td>
                        @if ($item['object_type'] == 'product')
                            @lang('Sản phẩm')
                        @elseif($item['object_type'] == 'service')
                            @lang('Dịch vụ')
                        @elseif($item['object_type'] == 'service_card')
                            @lang('Thẻ dịch vụ')
                        @else
                            @lang('Thẻ thành viên')
                        @endif
                    </td>
                    <td>{{$item['object_name']}}</td>
                    <td>
                        {{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>{{$item['quantity']}}</td>
                    <td>
                        {{number_format($item['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
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
