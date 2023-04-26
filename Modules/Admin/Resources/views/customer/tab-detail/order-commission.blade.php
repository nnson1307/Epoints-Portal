
    <div class="table-responsive">
        <table class="table table-striped m-table m-table--head-bg-default">
            <thead class="bg">
            <tr>
                <th>{{__('MÃ ĐƠN HÀNG')}}</th>
                <th>{{__('NGƯỜI TẠO')}}</th>
                <th>@lang("SỐ TIỀN")</th>
                <th>{{__('NGÀY TẠO')}}</th>
                <th>{{__('TRẠNG THÁI')}}</th>
            </tr>
            </thead>
            @if(count($data) > 0)
            <tbody>
            @foreach($data as $order_commission)
                <tr>
                    <td>
                        {{$order_commission['order_code']}}
                    </td>
                    <td>{{$order_commission['full_name']}}</td>
                    <td>{{number_format($order_commission['refer_money'])}}</td>
                    <td>{{date("d/m/Y",strtotime($order_commission['created_at']))}}</td>
                    <td>
                        @if($order_commission['status']=='approve')
                            <span class="m-badge m-badge--success m-badge--wide"
                                  style="width: 80%">@lang("Đã phê duyệt")</span>
                        @elseif($order_commission['status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 80%">{{__('Đã hủy')}}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            @endif
        </table>
    </div>
