<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('MÃ ĐƠN HÀNG')}}</th>
            <th class="tr_thead_list">{{__('KHÁCH HÀNG')}}</th>
            <th class="tr_thead_list text-center">{{__('NGƯỜI TẠO')}}</th>
            <th class="tr_thead_list">{{__('TỔNG TIỀN')}}</th>
            <th class="tr_thead_list">{{__('ĐÃ THANH TOÁN')}}</th>
            <th class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
            <th class="tr_thead_list text-center" style="width: 160px;">{{__('TRẠNG THÁI')}}</th>
            <th class="tr_thead_list text-center">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>
                        <a class="m-link" style="color:#464646" title="{{__('Chi tiết')}}"
                           href="{{route('admin.order.detail',$item['order_id'])}}">
                            {{$item['order_code']}}
                        </a>
                    </td>
                    <td>
                        <a class="m-link" style="color:#464646" title="{{__('Chi tiết')}}"
                           href="{{route('admin.customer.detail',$item['customer_id'])}}">
                            {{$item['full_name_cus']}}
                        </a>
                    </td>
                    <td class="text-center">{{$item['full_name']}}</td>
                    <td>{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{number_format($item['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                  
                    <td>{{$item['branch_name']}}</td>
                    
                    <td class="text-center">
                        @if($item['process_status']=='paysuccess')
                            <span class="m-badge m-badge--primary m-badge--wide"
                                  style="width: 80%">{{__('Đã thanh toán')}}</span>
                        @elseif($item['process_status']=='pay-half')
                            <span class="m-badge m-badge--info m-badge--wide"
                                  style="width: 80%">{{__('Thanh toán còn thiếu')}}</span>
                        @elseif($item['process_status']=='new')
                            <span class="m-badge m-badge--success m-badge--wide"
                                  style="width: 80%">{{__('Mới')}}</span>
                        @elseif($item['process_status']=='ordercancle')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 80%">{{__('Đã hủy')}}</span>
                        @elseif($item['process_status']=='confirmed')
                            <span class="m-badge m-badge--warning m-badge--wide"
                                  style="width: 80%">{{__('Đã xác nhận')}}</span>
                        @endif
                    </td>
                    <td class="text-center">{{date("d/m/Y",strtotime($item['created_at']))}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
