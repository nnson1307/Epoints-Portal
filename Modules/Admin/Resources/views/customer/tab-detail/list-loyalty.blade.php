<div class="table-responsive">
    <table class="table m-table m-table--head-separator-metal"
           style="border-collapse: collapse;">
        <thead class="bg">
        <tr>
            <th>{{__('LOẠI TÍCH LŨY')}}</th>
            <th>{{__('MÃ ĐƠN HÀNG')}}</th>
            <th>{{__('THÀNH TIỀN')}}</th>
            <th>@lang("SỐ ĐIỂM")</th>
            <th>{{__('NGÀY TẠO')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($LIST as $item2)
            <tr>
                <td>
                    @switch($item2['point_description'])
                        @case('plus')
                        {{__('Thanh toán đơn hàng')}}
                        @break
                        @case('actived_app')
                        {{__('Cài đặt app')}}
                        @break
                        @case('refer')
                        {{__('Giới thiệu khách hàng')}}
                        @break
                        @case('birthday')
                        {{__('Chúc mừng sinh nhật')}}
                        @break
                        @case('review')
                        {{__('Phản hồi dịch vụ')}}
                        @break
                        @case('appointment_app')
                        {{__('Đặt lịch từ app')}}
                        @break
                        @case('order_app')
                        {{__('Mua hàng từ app')}}
                        @break
                        @case('appointment_direct')
                        {{__('Đặt lịch trực tiếp')}}
                        @break
                        @case('appointment_fb')
                        {{__('Đặt lịch từ facebook')}}
                        @break
                        @case('appointment_zalo')
                        {{__('Đặt lịch từ zalo')}}
                        @break
                        @case('appointment_call')
                        {{__('Đặt lịch gọi điện')}}
                        @break
                        @case('appointment_online')
                        {{__('Đặt lịch từ web online')}}
                        @break
                        @case('order_direct')
                        {{__('Mua hàng trực tiếp')}}
                        @break
                        @case('rating')
                        {{__('Cộng điểm đánh giá')}}
                        @break
                    @endswitch
                </td>
                <td>
                    @if($item2['order_id'] != null)
                        <a class="m-link" style="color:#464646" title="Chi tiết" target="_blank"
                           href="{{route('admin.order.detail', $item2['order_id'])}}">
                            {{$item2['order_code']}}
                        </a>
                    @endif
                </td>
                <td>
                    @if($item2['amount'] != null)
                        {{number_format($item2['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    @endif
                </td>
                <td>{{$item2['type'] == 'plus' ? '+' : '-' }} {{$item2['point']}}</td>
                <td>{{date("d/m/Y",strtotime($item2['created_at']))}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{ $LIST->links('helpers.paging') }}