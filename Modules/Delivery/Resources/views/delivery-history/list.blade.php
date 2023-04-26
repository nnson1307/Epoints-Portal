<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('Đối tác giao hàng')</th>
            <th class="tr_thead_list">@lang('Người giao hàng')</th>
            <th class="tr_thead_list">@lang('Thời gian')</th>
            <th class="tr_thead_list">@lang('Thông tin khách hàng')</th>
            <th class="tr_thead_list text-center">@lang('Số tiền cần thu')</th>
            <th class="tr_thead_list text-center">@lang('Trạng thái')</th>
            <th class="tr_thead_list text-center">@lang('Trạng thái GHN')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>
                        <a href="{{route('delivery.detail',  $item['delivery_id'])}}">
                            {{$item['order_code']}}
                        </a>
                    </td>
                    <td>{{$item['transport_name']}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>
                        @lang('Thời gian giao hàng dự kiến'):
                        <strong>{{\Carbon\Carbon::parse($item['time_ship'])->format('d/m/Y H:i')}}</strong>
                        <br>
                        @lang('Thời gian lấy hàng'):
                        <strong>{{$item['time_pick_up'] != null ? \Carbon\Carbon::parse($item['time_pick_up'])->format('d/m/Y H:i') : ''}}</strong>
                        <br>
                        @lang('Thời gian giao hàng'):
                        <strong>{{$item['time_drop'] != null ? \Carbon\Carbon::parse($item['time_drop'])->format('d/m/Y H:i') : ''}}</strong>
                    </td>
                    <td>
                        @lang('Người nhận'): <strong>{{$item['contact_name']}}</strong> <br>
                        @lang('Sđt'): <strong>{{$item['contact_phone']}}</strong> <br>
                        @lang('Địa chỉ'): <strong>{{$item['contact_address']}}</strong><br>
                        @lang('Nơi lấy hàng'): <strong>{{isset($item['address'])?$item['address']:$item['pick_up']}}</strong>
                    </td>

                    <td class="text-center">
                        {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">
                        @if($item['status']=='new')
                            <span class="m-badge m-badge--success" style="width:90%;">@lang('Đóng gói')</span>
                        @elseif($item['status']=='inprogress')
                            <span class="m-badge m-badge--primary" style="width:90%;">@lang('Đã nhận hàng')</span>
                        @elseif($item['status']=='success')
                            <span class="m-badge m-badge--info" style="width:90%;">@lang('Đã giao hàng')</span>
                        @elseif($item['status']=='confirm')
                            <span class="m-badge m-badge--metal"
                                  style="width:90%;">@lang('Xác nhận đã giao hàng')</span>
                        @elseif($item['status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width:90%;">@lang('Hủy')</span>
                        @elseif($item['status']=='fail')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width:90%;">@lang('Thất bại')</span>
                        @elseif($item['status']=='pending')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width:90%;">@lang('Đang chờ xử lý')</span>
                        @endif
                    </td>
                    <td>
                        @if($item['status_ghn'] == 'ready_to_pick')
                            {{__('Chờ lấy hàng')}}
                        @elseif($item['status_ghn'] == 'picking')
                            {{__('Đang lấy hàng')}}
                        @elseif($item['status_ghn'] == 'money_collect_picking')
                            {{__('Đã tương tác với người gửi')}}
                        @elseif($item['status_ghn'] == 'picked')
                            {{__('Lấy hàng thành công')}}
                        @elseif($item['status_ghn'] == 'storing')
                            {{__('Nhập kho')}}
                        @elseif($item['status_ghn'] == 'transporting')
                            {{__('Đang trung chuyển')}}
                        @elseif($item['status_ghn'] == 'sorting')
                            {{__('Sorting')}}
                        @elseif($item['status_ghn'] == 'delivering')
                            {{__('Đang giao hàng')}}
                        @elseif($item['status_ghn'] == 'delivered')
                            {{__('Giao hàng thành công')}}
                        @elseif($item['status_ghn'] == 'money_collect_delivering')
                            {{__('Đang tương tác với người nhận')}}
                        @elseif($item['status_ghn'] == 'delivery_fail')
                            {{__('Giao hàng không thành công')}}
                        @elseif($item['status_ghn'] == 'waiting_to_return')
                            {{__('Chờ xác nhận giao lại')}}
                        @elseif($item['status_ghn'] == 'return')
                            {{__('Chuyển hoàn')}}
                        @elseif($item['status_ghn'] == 'return_transporting')
                            {{__('Đang trung chuyển hàng hoàn')}}
                        @elseif($item['status_ghn'] == 'return_sorting')
                            {{__('Đang phân loại hàng hoàn')}}
                        @elseif($item['status_ghn'] == 'returning')
                            {{__('Đang hoàn hàng')}}
                        @elseif($item['status_ghn'] == 'return_fail')
                            {{__('Hoàn hàng không thành công')}}
                        @elseif($item['status_ghn'] == 'waiting_to_finish')
                            {{__('Chờ hoàn tất')}}
                        @elseif($item['status_ghn'] == 'finish')
                            {{__('Hoàn tất')}}
                        @elseif($item['status_ghn'] == 'returned')
                            {{__('Hoàn hàng thành công')}}
                        @elseif($item['status_ghn'] == 'cancel')
                            {{__('Đơn huỷ')}}
                        @elseif($item['status_ghn'] == 'exception')
                            {{__('Hàng ngoại lệ')}}
                        @elseif($item['status_ghn'] == 'lost')
                            {{__('Thất lạc')}}
                        @elseif($item['status_ghn'] == 'damage')
                            {{__('Hư hỏng')}}
                        @endif
                    </td>
                    <td>
                        @if($item['ghn_order_code'] != null && $item['partner'] == 'ghn')
                            <a href="javascript:void(0)" onclick="listHistory.print(`{{$item['ghn_order_code']}}`,`{{$item['partner']}}`,`{{$item['ghn_shop_id']}}`)"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-print"></i>
                            </a>
                        @endif
                        <a href="{{route('delivery-history.show', $item['delivery_history_id'])}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-eye"></i>
                        </a>
                        @if($item['delivery_status'] !='delivered' && !in_array($item['status'], ['success', 'confirm', 'cancel', 'fail']))
                            <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               href="{{route('delivery-history.edit', $item['delivery_history_id'])}}">
                                <i class="la la-edit"></i>
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
