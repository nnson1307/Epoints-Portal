<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ PHIẾU BẢO DƯỠNG')</th>
            <th class="tr_thead_list">@lang('TÊN NHÂN VIÊN ĐƯA ĐI BẢO DƯỠNG')</th>
            <th class="tr_thead_list">@lang('CHI PHÍ BẢO DƯƠNG')</th>
            <th class="tr_thead_list">@lang('TỔNG CHI PHÍ')</th>
            <th class="tr_thead_list">@lang('THANH TOÁN')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY BẢO DƯỠNG')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <a href="{{route('repair.show', $item['repair_id'])}}">
                            {{$item['repair_code']}}
                        </a>
                    </td>
                    <td>{{$item['staff_name']}}</td>
                    <td>
                        {{number_format($item['repair_cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>
                        {{number_format($item['total_pay'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>
                        @if(isset($item['payment_status']) && $item['payment_status'] != null)
                            @lang('Đã thanh toán')
                        @else
                            @lang('Chưa thanh toán')
                        @endif
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
                    <td>{{\Carbon\Carbon::parse($item['repair_date'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if($item['status'] != 'cancel')
                            @if(!(isset($item['payment_status']) && $item['payment_status'] != null))
                                <a href="javascript:void(0)" onclick="payment.modalPayment('{{$item['repair_id']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Thanh toán')">
                                    <i class="la la-cc-paypal"></i>
                                </a>
                            @endif
                        @endif
                        @if(!in_array($item['status'], ['cancel', 'finish']))
                            <a href="{{route('repair.edit', $item['repair_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
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
