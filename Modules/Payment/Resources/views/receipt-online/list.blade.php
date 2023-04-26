<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('#')</th>
            <th class="tr_thead_list">@lang('HÀNH ĐỘNG')</th>
            <th class="tr_thead_list">@lang('ĐỐI TƯỢNG')</th>
            <th class="tr_thead_list">@lang('MÃ GIAO DỊCH')</th>
            <th class="tr_thead_list">@lang('MÃ THAM CHIẾU')</th>
            <th class="tr_thead_list">@lang('NGƯỜI THỰC HIỆN')</th>
            <th class="tr_thead_list">@lang('SỐ ĐIỆN THOẠI')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('PHƯƠNG THỨC THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('HÌNH THỨC XÁC NHẬN')</th>
            <th class="tr_thead_list">@lang('TIỀN THANH TOÁN')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{isset($page) ? ($page-1)*10 + $key+1 : $key+1}}</td>
                    <td>
                        @if ($item['status'] == 'inprocess' && $item['payment_method_code'] == 'TRANSFER')
                            <a href="javascript:void(0)"
                               onclick="listReceiptOnline.success('{{$item['receipt_online_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-check"></i>
                            </a>
                            {{--@if(in_array('receippt.delete', session('routeList')))--}}
                            <a href="javascript:void(0)"
                               onclick="listReceiptOnline.cancel('{{$item['receipt_online_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-close"></i>
                            </a>
                            {{--@endif--}}
                        @endif
                    </td>
                    <td>
                        @switch($item['object_type'])
                            @case('order')
                            @lang('Đơn hàng')
                            @break

                            @case('order_online')
                            @lang('Đơn hàng online')
                            @break

                            @case('receipt')
                            @lang('Phiếu thu')
                            @break

                            @case('debt')
                            @lang('Công nợ')
                            @break

                            @case('maintenance')
                            @lang('Phiếu bảo trì')
                            @break

                            @case('delivery_history')
                            @lang('Phiếu giao hàng')
                            @break
                        @endswitch
                    </td>
                    <td>
                        @switch($item['object_type'])
                            @case('order')
                            <a href="{{route('admin.order.detail', $item['object_id'])}}"
                               target="_blank">{{$item['object_code']}}</a>
                            @break

                            @case('order_online')
                            <a href="{{route('admin.order-app.detail', $item['object_id'])}}"
                               target="_blank">{{$item['object_code']}}</a>
                            @break

                            @case('receipt')
                            <a href="{{route('receipt.show', $item['object_id'])}}"
                               target="_blank">{{$item['object_code']}}</a>
                            @break

                            @case('debt')
                            {{$item['object_code']}}
                            @break

                            @case('maintenance')
                            <a href="{{route('maintenance.show', $item['object_id'])}}"
                               target="_blank">{{$item['object_code']}}</a>
                            @break

                            @case('delivery_history')
                            <a href="{{route('delivery-history.show', $item['object_id'])}}"
                               target="_blank">{{$item['object_code']}}</a>
                            @break
                        @endswitch
                    </td>
                    <td>{{$item['payment_transaction_code']}}</td>
                    <td>{{$item['performer_name']}}</td>
                    <td>{{$item['performer_phone']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['payment_time'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @switch($item['status'])
                            @case('inprocess') {{__('Đang thực hiện')}} @break
                            @case('success') {{__('Thành công')}} @break
                            @case('cancel') {{__('Huỷ')}} @break
                        @endswitch
                    </td>
                    <td>{{$item['payment_method_name']}}</td>
                    <td>
                        @switch($item['type'])
                            @case('auto') {{__('Tự động')}} @break
                            @case('manual') {{__('Thủ công')}} @break
                        @endswitch
                    </td>
                    <td>{{number_format($item['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
