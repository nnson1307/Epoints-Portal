<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('THÔNG TIN GIAO HÀNG')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN ĐẶT HÀNG')</th>
            <th class="tr_thead_list text-center">@lang('DỰ KIẾN')</th>
            <th class="tr_thead_list text-center">@lang('ĐÃ GIAO')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
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
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>
                        @lang('Người nhận'): <strong>{{$item['contact_name']}}</strong> <br>
                        @lang('Sđt'): <strong>{{$item['contact_phone']}}</strong> <br>
                        @lang('Địa chỉ'): <strong>{{$item['contact_address']}}</strong>
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($item['time_order'])->format('d/m/Y H:i')}}
                    </td>
                    <td class="text-center">{{$item['total_transport_estimate']}}</td>
                    <td class="text-center">
                        {{$item['total_success']}}
                    </td>
                    <td>
                        @if($item['delivery_status']=='packing')
                            <span class="m-badge m-badge--success" style="width: 80%">
                                @lang('Đóng gói')
                            </span>
                        @elseif($item['delivery_status']=='preparing')
                            <span class="m-badge m-badge--primary"
                                  style="width: 80%">@lang('Chuẩn bị')</span>
                        @elseif($item['delivery_status']=='delivering')
                            <span class="m-badge m-badge--info"
                                  style="width: 80%">@lang('Đang giao')</span>
                        @elseif($item['delivery_status']=='delivered')
                            <span class="m-badge m-badge--metal"
                                  style="width: 80%">@lang('Đã giao')</span>
                        @elseif($item['delivery_status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 80%">@lang('Đã hủy')</span>
                        @endif
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if(!in_array($item['delivery_status'], ['delivered', 'cancel']) &&in_array('delivery.create-history',session('routeList')))
                            <a href="{{route('delivery.create-history', $item['delivery_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Tạo phiếu giao hàng')">
                                <i class="la la-plus"></i>
                            </a>
                        @endif
                        @if(!in_array($item['delivery_status'], ['delivered', 'cancel', 'fail']) && in_array('delivery.edit',session('routeList')))
                            <a href="{{route('delivery.edit', $item['delivery_id'])}}"
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
