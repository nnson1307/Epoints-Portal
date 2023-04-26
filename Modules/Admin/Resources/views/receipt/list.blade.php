<div class="form-group">
    @if(count($LIST)>0)
        <?php $total_debt = 0; ?>
        @foreach($LIST as $item)
            @if($item['status'] != 'cancel')
                <?php $total_debt += $item['amount'] - $item['amount_paid']; ?>
            @else
                <?php $total_debt += 0; ?>
            @endif
        @endforeach
        <label>
            {{__('Tổng công nợ')}}:
            <strong>{{number_format($total_debt, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
        </label>
    @else
        <label>
            {{__('Tổng công nợ')}}: <strong>0{{__('đ')}}</strong>
        </label>
    @endif
</div>
<div class="form-group">
    <div class="table-responsive">
        <table class="table table-striped m-table m-table--head-bg-default">
            <thead class="bg">
            <tr>
                <th class="tr_thead_list">#</th>
                <th class="tr_thead_list text-center">{{__('MÃ CÔNG NỢ')}}</th>
                <th class="tr_thead_list text-center">{{__('CHI NHÁNH')}}</th>
                <th class="tr_thead_list text-center">{{__('MÃ ĐƠN HÀNG')}}</th>
                <th class="tr_thead_list text-center">{{__('KHÁCH HÀNG')}}</th>
                <th class="tr_thead_list text-center">{{__('MÃ KHÁCH HÀNG')}}</th>
                <th class="tr_thead_list text-center">{{__('NGƯỜI TẠO')}}</th>
                <th class="tr_thead_list text-center">{{__('SỐ TIỀN NỢ')}}</th>
                <th class="tr_thead_list text-center">{{__('ĐÃ THANH TOÁN')}}</th>
                <th class="tr_thead_list text-center">{{__('CÒN NỢ')}}</th>
                <th class="tr_thead_list text-center">{{__('NGÀY TẠO')}}</th>
                <th class="tr_thead_list text-center">{{__('TRẠNG THÁI')}}</th>
                <th class="tr_thead_list text-center">{{__('GHI CHÚ')}}</th>
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
                        <td class="text-center">
                            <a href="javascript:void(0)"
                               onclick="indexDebt.detail('{{$item['customer_debt_id']}}')">{{$item['debt_code']}}
                            </a>
                        </td>
                        <td class="text-center">
                            {{$item['branch_name']}}
                        </td>
                        <td class="text-center">
                            @if(in_array('admin.order.detail',session('routeList')) && $item['order_code'] != null)
                                <a href="{{route("admin.order.detail", $item['order_id'])}}" target="_blank">
                                    {{$item['order_code']}}
                                </a>
                            @else
                                {{$item['order_code']}}
                            @endif
                        </td>
                        <td class="text-center">
                            {{$item['customer_name']}}
                        </td>
                        <td class="text-center">
                            @if(in_array('admin.customer.detail',session('routeList')))
                                <a href="{{route("admin.customer.detail",$item['customer_id'])}}" target="_blank">
                                    {{$item['customer_code']}}
                                </a>
                            @else
                                {{$item['customer_code']}}
                            @endif
                        </td>
                        <td class="text-center">
                            {{$item['staff_name']}}
                        </td>
                        <td class="text-center">
                            {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                        <td class="text-center">
                            {{number_format($item['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                        <td class="text-center">
                            {{number_format($item['amount'] - $item['amount_paid'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                        <td class="text-center">
                            {{date("d/m/Y H:i",strtotime($item['created_at']))}}
                        </td>
                        <td class="text-center">
                            @if($item['status']=='paid')
                                <span class="m-badge m-badge--success m-badge--wide"
                                      style="width: 80%">{{__('Đã thanh toán')}}</span>
                            @elseif($item['status']=='part-paid')
                                <span class="m-badge m-badge--warning m-badge--wide"
                                      style="width: 80%">{{__('Thanh toán một phần')}}</span>
                            @elseif($item['status']=='unpaid')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width: 80%">{{__('Chưa thanh toán')}}</span>
                            @elseif($item['status']=='cancel')
                                <span class="m-badge m-badge--danger m-badge--wide"
                                      style="width: 80%">{{__('Đã hủy')}}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{$item['note']}}
                        </td>
                        <td>
                            @if($item['status']=='unpaid' || $item['status']=='part-paid')
                                <a href="javascript:void(0)" onclick="indexDebt.receipt('{{$item['customer_debt_id']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Thanh toán')}}">
                                    <i class="la la-cc-paypal"></i>
                                </a>
                            @endif
                            @if($item['debt_type']=='first' && $item['status'] == 'unpaid')
                                <a href="javascript:void(0)" onclick="indexDebt.cancle('{{$item['customer_debt_id']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Hủy')}}">
                                    {{--                                    <i class="la la-cc-paypal"></i>--}}
                                    <i class="fa fa-times" aria-hidden="true"></i>

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
</div>
