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
            <th class="tr_thead_list text-center">{{__('THỜI GIAN THANH TOÁN GẦN NHẤT')}}</th>
            <th class="tr_thead_list">{{__('HÌNH THỨC THANH TOÁN GẦN NHẤT')}}</th>
            {{-- <th class="tr_thead_list">{{__('NGUỒN')}}</th> --}}
            <th class="tr_thead_list">{{__('CHI NHÁNH')}}</th>
            <th class="tr_thead_list text-center">{{__('CÁCH THỨC NHẬN HÀNG')}}</th>
            <th class="tr_thead_list text-center" style="min-width: 160px;">{{__('TRẠNG THÁI')}}</th>
            <th class="tr_thead_list">{{__('GHI CHÚ')}}</th>
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
                        @if ($item['order_source_id'] == 1)
                            <a class="m-link" target="_blank" title="{{__('Chi tiết')}}"
                               href="{{route('admin.order.detail',$item['order_id'])}}">
                                {{$item['order_code']}}
                            </a>
                        @elseif($item['order_source_id'] == 2)
                            <a class="m-link" target="_blank" title="{{__('Chi tiết')}}"
                               href="{{route('admin.order-app.detail',$item['order_id'])}}">
                                {{$item['order_code']}}
                            </a>
                        @endif
                    </td>
                    <td>
                        <a class="m-link" title="{{__('Chi tiết')}}"
                           href="{{route('admin.customer.detail',$item['customer_id'])}}">
                            {{$item['full_name_cus']}}
                        </a>
                    </td>
                    <td class="text-center">{{$item['full_name']}}</td>
                    <td>{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>
                        @if(isset($receipt[$item['order_id']]))
                            {{number_format($receipt[$item['order_id']]['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        @if ($item['receipt_last'] != null)
                            {{\Carbon\Carbon::parse($item['receipt_last']['created_at'])->format('d/m/Y H:i')}}
                        @endif
                    </td>
                    <td>
                        @if (count($item['receipt_detail_last']) > 0)
                            @foreach($item['receipt_detail_last'] as $k => $v)
                                 {{$v['payment_method_name']}} {{$k+1 != count($item['receipt_detail_last']) ? '+' : ''}}
                            @endforeach
                        @endif
                    </td>
                    {{-- <td>{{$item['order_source_name']}}</td> --}}
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['receive_at_counter'] == 0 ? __('Địa chỉ khách hàng') : __('Nhận hàng tại quầy')}}</td>
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
                    <td>

                        {{$item['order_description']}}
                        {{-- @if($item['process_status']=='new')
                            {{$item['order_description']}}
                        @elseif($item['process_status']=='paysuccess')
                            @if(isset($receipt[$item['order_id']]))
                                {{$receipt[$item['order_id']]['note']}}
                            @endif
                        @elseif($item['process_status']=='ordercancle')
                            {{$item['order_description']}}
                        @endif --}}
                    </td>
                    <td class="text-center">{{date("d/m/Y",strtotime($item['created_at']))}}</td>
                    <td style="width: 126px;">
                        @if($item['order_source_id'] == 2 && $item['process_status'] =='new')
                            <a href="javascript:void(0)" onclick="index.apply_branch('{{$item['order_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Chuyển chi nhánh')}}">
                                <i class="la la-bank"></i>
                            </a>
                        @endif
                        @if($item['process_status'] =='new' || $item['process_status'] == 'confirmed')
                            @if(in_array('admin.order.print-bill2',session('routeList')))
                                <a href="javascript:void(0)" onclick="print_bill.print('{{$item['order_id']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('In hóa đơn')}}">
                                    <i class="la la-print"></i>
                                </a>
                            @endif
                            @if(in_array('admin.order.receipt-after',session('routeList')))
                                <a href="{{route('admin.order.receipt-after',$item['order_id']) . '?type=order'}}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Chỉnh sửa & Thanh toán')}}">
                                    <i class="la la-file-text"></i>
                                </a>
                            @endif
                            @if(in_array('admin.order.remove',session('routeList')))
                                @if ($item['delivery_status'] != 'delivered')
                                    <button onclick="index.remove(this, {{$item['order_id']}})"
                                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                            title="{{__('Xóa')}}">
                                        <i class="la la-trash"></i>
                                    </button>
                                @endif
                            @endif
                        @endif
                        @if(in_array($item['process_status'], ['paysuccess', 'pay-half'])
                            && $item['branch_id'] == Auth::user()->branch_id
                            && date("Y-m-d",strtotime($item['created_at'])) == date('Y-m-d')
                            && $item['delivery_status'] != 'delivered'
                        )
                            @if(in_array('admin.order.cancel',session('routeList')))
                                <button onclick="cancel.modal_cancel({{$item['order_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__("Xóa")}}">
                                    <i class="la la-scissors"></i>
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
