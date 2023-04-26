<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ PHIẾU')</th>
            <th class="tr_thead_list">@lang('LOẠI PHIẾU')</th>
            <th class="tr_thead_list">@lang('ĐỐI TƯỢNG')</th>
            <th class="tr_thead_list">@lang('TÊN ĐỐI TƯỢNG')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGƯỜI TẠO')</th>
            <th class="tr_thead_list">@lang('SỐ TIỀN THU')</th>
            <th class="tr_thead_list">@lang('NGÀY GHI NHẬN')</th>
            <th class="tr_thead_list">@lang('NGÀY THANH TOÁN')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <a href="{{route('receipt.show', $item['receipt_id'])}}">
                            {{$item['receipt_code']}}
                        </a>
                    </td>
                    <td>{{$item['receipt_type_name']}}</td>
                    <td>
                        @if ($item['object_type'] != 'debt' && $item['order_id'] === 0)
                            {{$item['object_accounting_type_name']}}
                        @elseif ($item['object_type'] == 'debt')
                            @lang('Công nợ')
                        @else
                            @lang('Khách hàng')
                        @endif
                    </td>
                    <td>
                        @if ($item['object_type'] != 'debt' && $item['order_id'] === 0)
                            {{$item['object_accounting_name']}}
                        @elseif ($item['object_type'] == 'debt')
                            {{$item['customer_name_debt']}}
                        @else
                            {{$item['customer_name']}}
                        @endif
                    </td>
                    <td>
                        @switch($item['status'])
                            @case('unpaid') {{__('Chưa thanh toán')}} @break
                            @case('part-paid') {{__('Thanh toán một phần')}} @break
                            @case('paid') {{__('Đã thanh toán')}} @break
                            @case('cancel') {{__('Hủy')}} @break
                            @case('fail') {{__('Lỗi')}} @break
                        @endswitch
                    </td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{number_format($item['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if ($item['status'] == 'paid')
                            {{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y H:i')}}
                        @endif
                    </td>
                    <td>
                        <a href="javascript:void(0)"
                           onclick="listReceipt.printBill('{{$item['receipt_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('In')">
                            <i class="la la-print"></i>
                        </a>
                        @if ($item['type_insert'] == 'manual')
                            @if ($item['status'] == 'unpaid')
                                <a href="{{route('receipt.edit', $item['receipt_id'])}}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Chỉnh sửa')">
                                    <i class="la la-edit"></i>
                                </a>
                            @endif
                        @endif
                        @if(in_array('receipt.delete', session('routeList')) && $item['receipt_source'] != "delivery")
                            <a href="javascript:void(0)"
                               onclick="listReceipt.delete('{{$item['receipt_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
