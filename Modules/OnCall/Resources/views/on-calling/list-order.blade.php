<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="ss--font-size-th ss--text-center">
                {{ __('Thời gian tạo') }}</th>
            <th class="ss--text-center ss--font-size-th">{{ __('Mã đơn hàng') }}
            </th>
            <th class="ss--text-center ss--font-size-th">
                {{ __('Sản phẩm/Dịch vụ') }}</th>
            <th class="ss--text-center ss--font-size-th">{{ __('Tổng tiền') }}
            </th>
            <th class="ss--text-center ss--font-size-th">{{ __('Trạng thái') }}
            </th>
            <th class="ss--text-center ss--font-size-th">{{ __('Ghi chú') }}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST_ORDER))
            @foreach ($LIST_ORDER as $key => $value)
                <tr>
                    <td class="text-center">{{date("d/m/Y",strtotime($value['created_at']))}}</td>
                    <td>
                        <a class="m-link" style="color:#464646" title="{{__('Chi tiết')}}" target="_blank"
                           href="{{route('admin.order.detail', $value['order_id'])}}">
                            {{$value['order_code']}}
                        </a>
                    </td>
                    <td>{{$value['list_product']}}</td>
                    <td>{{number_format($value['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td class="text-center">
                        @if($value['process_status']=='paysuccess')
                        <span class="m-badge m-badge--primary m-badge--wide" style="width: 100%">{{__('Đã thanh toán')}}</span>
                        @elseif($value['process_status']=='pay-half')
                            <span class="m-badge m-badge--info m-badge--wide"
                                  style="width: 100%">{{__('Thanh toán còn thiếu')}}</span>
                        @elseif($value['process_status']=='new')
                            <span class="m-badge m-badge--success m-badge--wide"
                                  style="width: 100%">{{__('Mới')}}</span>
                        @elseif($value['process_status']=='ordercancle')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 100%">{{__('Đã hủy')}}</span>
                        @elseif($value['process_status']=='confirmed')
                            <span class="m-badge m-badge--warning m-badge--wide"
                                  style="width: 100%">{{__('Đã xác nhận')}}</span>
                        @endif
                    </td>
                    <td>
                        {{$value['order_description']}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@if(isset($LIST))
{{--{{ $LIST->links('helpers.paging') }}--}}
@endif
