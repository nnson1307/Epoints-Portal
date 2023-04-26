<input type="hidden" id="order_id_new" value="">
<div class="row">
    <div class="col">
        <span class="note-font">Bàn:</span>
        <span class="location_name_text"></span>
    </div>
    <div class="col">
        <div class="price_size">
            <span class="note-font">Số ghế:</span>
            <span class="seat_text"></span>
        </div>
    </div>
</div>
<div class="m-portlet__body" style="spadding: 0px;padding-top:30px; width:950px">
    <div class="table-responsive">
        <table class="table  m-table ss--header-table">
            <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th ss--text-center">{{__('Khách hàng')}}</th>
                <th class="ss--font-size-th  ss--text-center">{{__('Mã đơn hàng')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Thời gian đặt')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Số lượng món')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Tổng tiền')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($listOrder) && count($listOrder) != 0)
                @foreach($listOrder as $item)
                    <tr class="ss--font-size-13 ss--nowrap table-selected" data-order-id="{{$item['order_id']}}" data-location="{{$item['areas_name'].' - '.$item['table_name']}}" data-seat="{{$item['table_seats']}}" onclick="order.selectOrder(this)">
                        <td class="ss--text-center">{{$item['full_name']}}</td>
                        <td class="ss--text-center">{{$item['order_code']}}</td>
                        <td class="ss--text-center">{{\Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y')}}</td>
                        <td class="ss--text-center">{{$item['total_product']}}</td>
                        <td class="ss--text-center">{{number_format($item['amount'])}}đ</td>
                    </tr>
                @endforeach
            @else
                <tr class="ss--font-size-13 ss--nowrap">
                    <td colspan="5">
                        <div class="not_find" style="text-align: center;font-weight: bold;">
                            <span>Chưa có hóa đơn nào</span>
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        {{ $listOrder->links('fnb::orders.helpers.paging-merge') }}
    </div>
</div>
