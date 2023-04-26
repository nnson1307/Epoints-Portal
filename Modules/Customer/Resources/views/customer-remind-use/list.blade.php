<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('#')</th>
            <th class="tr_thead_list">@lang('MÃ ĐƠN HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('LOẠI ĐỐI TƯỢNG')</th>
            <th class="tr_thead_list">@lang('TÊN ĐỐI TƯỢNG')</th>
            <th class="tr_thead_list">@lang('NGÀY GỬI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item['order_code']}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>
                        @switch($item['object_type'])
                            @case('product')
                            @lang('Sản phẩm')
                            @break
                            @case('service')
                            @lang('Dịch vụ')
                            @break
                            @case('service_card')
                            @lang('Thẻ dịch vụ')
                            @break
                        @endswitch
                    </td>
                    <td>
                        @if(in_array('customer-remind-use.modal-care', session('routeList')))
                            <a href="{{route('customer-remind-use.show', $item['customer_remind_use_id'])}}">
                                {{$item['object_name']}}
                            </a>
                        @else
                            {{$item['object_name']}}
                        @endif
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['sent_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if($item['is_finish'] == 0 && in_array('customer-remind-use.edit',session('routeList')))
                            <a href="{{route('customer-remind-use.edit', $item['customer_remind_use_id'])}}"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Sửa')}}">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('customer-remind-use.modal-care', session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="remindUse.modalCare('{{$item['customer_remind_use_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chăm sóc khách hàng')">
                                <i class="la  la-gratipay"></i>
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
