<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>#</th>
            <th class="tr_thead_list">@lang('HÀNH ĐỘNG')</th>
            <th class="tr_thead_list">@lang('MÃ PHIẾU BẢO TRÌ')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('NHÂN VIÊN THỰC HIỆN')</th>
            <th class="tr_thead_list">@lang('NGÀY BẢO TRÌ')</th>
            <th class="tr_thead_list">@lang('NGÀY TRẢ HÀNG DỰ KIẾN')</th>
            <th class="tr_thead_list">@lang('TỔNG TIỀN PHẢI TRẢ')</th>
            <th class="tr_thead_list">@lang('ĐÃ THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('CÒN LẠI')</th>
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
                        @if($item['total_amount_pay'] > 0 && $item['total_amount_pay'] > $item['total_receipt'] && $item['status'] != 'cancel')
                            <a href="javascript:void(0)" onclick="receipt.modalReceipt('{{$item['maintenance_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Thanh toán')">
                                <i class="la la-cc-paypal"></i>
                            </a>
                        @endif
                        {{--                        @if(!in_array($item['delivery_status'], ['delivered', 'cancel', 'fail']) && in_array('delivery.edit',session('routeList')))--}}
                        @if(!in_array($item['status'], ['cancel', 'finish']))
                            <a href="{{route('maintenance.edit', $item['maintenance_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('maintenance.show', $item['maintenance_id'])}}">
                            {{$item['maintenance_code']}}
                        </a>
                    </td>
                    <td>
                        @if($item['status']=='new')
                            <span class="m-badge m-badge--success" style="width: 60%">@lang('Mới')</span>
                        @elseif($item['status']=='received')
                            <span class="m-badge m-badge--success" style="width: 60%">@lang('Đã nhận hàng')</span>
                        @elseif($item['status']=='processing')
                            <span class="m-badge m-badge--info" style="width: 60%">@lang('Đang xử lý')</span>
                        @elseif($item['status']=='ready_delivery')
                            <span class="m-badge m-badge--info" style="width: 60%">@lang('Sẵn sàng trả hàng')</span>
                        @elseif($item['status']=='finish')
                            <span class="m-badge m-badge--primary" style="width: 60%">@lang('Hoàn tất')</span>
                        @elseif($item['status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 60%">@lang('Đã hủy')</span>
                        @endif
                    </td>
                    <td>{{$item['customer_name']}}</td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>{{\Carbon\Carbon::parse($item['date_estimate_delivery'])->format('d/m/Y H:i')}}</td>
                    <td>
                        {{number_format($item['total_amount_pay'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>
                        {{number_format($item['total_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>
                        {{number_format($item['total_amount_pay'] - $item['total_receipt'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
