<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Mã phiếu')}}</th>
            <th class="tr_thead_list">{{__('Loại người nhận')}}</th>
{{--            <th class="tr_thead_list">{{__('Người nhận')}}</th>--}}
            <th class="tr_thead_list">{{__('Người tạo')}}</th>
            <th class="tr_thead_list">{{__('Tổng tiền')}}</th>
            <th class="tr_thead_list">{{__('Chi nhánh')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th class="tr_thead_list">{{__('Ngày ghi nhận')}}</th>
            <th class="tr_thead_list">{{__('Hành động')}}</th>
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
                            <a href="javascript:void(0)"
                                    onclick="payment.popupDetail({{$item['payment_id']}},false)"
                                    class="ss--text-black"
                                    title="{{__('Chi tiết')}}">{{$item['payment_code']}}
                            </a>
                        </td>
                        <td>{{$item['object_accounting_type_name_vi']}}</td>
{{--                        @if(isset($item['object_accounting_type_code']))--}}
{{--                            @switch($item['object_accounting_type_code'])--}}
{{--                                @case('OAT_CUSTOMER'):--}}
{{--                                    <td>{{$item['customer_name']}}</td>--}}
{{--                                    @break--}}
{{--                                @case('OAT_SUPPLIER'):--}}
{{--                                    <td>{{$item['supplier_name']}}</td>--}}
{{--                                    @break--}}
{{--                                @case('OAT_EMPLOYEE'):--}}
{{--                                    <td>{{$item['employee_name']}}</td>--}}
{{--                                    @break--}}
{{--                                @default:--}}
{{--                                    <td>{{$item['accounting_name']}}</td>--}}
{{--                            @endswitch--}}
{{--                        @endif--}}
                        <td>{{$item['staff_name']}}</td>
                        <td>
                            {{number_format($item['total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                        </td>
                        <td>{{$item['branch_name']}}</td>
                        <td>
                            @switch($item['status'])
                                @case('new') {{__('Mới')}} @break;
                                @case('approved') {{__('Đã xác nhận')}} @break;
                                @case('paid') {{__('Đã chi')}} @break;
                                @case('unpaid') {{__('Đã huỷ chi')}} @break;
                            @endswitch
                        </td>
                        <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                        <td>
                            <a href="javascript:void(0)" onclick="payment.printBill('{{$item['payment_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('In hóa đơn')}}">
                                <i class="la la-print"></i>
                            </a>
                            <button value="{{$item['payment_id']}}"
                                    onclick="payment.popupDetail({{$item['payment_id']}},false)"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Chi tiết')}}">
                                <i class="la la-info"></i>
                            </button>
                            @if($item['status'] != 'paid')
                                <button value="{{$item['payment_id']}}"
                                        onclick="payment.popupEdit({{$item['payment_id']}},false)"
                                        class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Sửa')}}"
                                        id="edit1">
                                    <i class="la la-edit"></i>
                                </button>
                                <button onclick="payment.remove(this, {{$item['payment_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Xoá')}}">
                                    <i class="la la-trash"></i>
                                </button>
                            @endif
                        </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
