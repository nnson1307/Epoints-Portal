
   <div class="table-responsive" style="max-height: 300px;">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            @if (isset($optionConfigShow['show_column_customer_request_type']) && $optionConfigShow['show_column_customer_request_type'] == '1')
                <th class="tr_thead_list">@lang('Loại khách hàng')</th>
            @endif
            
            <th class="tr_thead_list">@lang('Tên khách hàng')</th>
            <th class="tr_thead_list">@lang('Số điện thoại')</th>
            <th class="tr_thead_list text-center">@lang('Chức năng')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
            <tr>
                @if (isset($optionConfigShow['show_column_customer_request_type']) && $optionConfigShow['show_column_customer_request_type'] == '1')
                    <td>
                        @if($item['type'] == 'customer_lead')
                            @lang('Khách hàng tiềm năng')
                        @else
                            @lang('Khách hàng')
                        @endif
                    </td>
                @endif
               
                <td>
                    {{$item['full_name']}}
                </td>
                <td>
                    {{$item['phone']}}
                </td>
                <td class="text-center">
                    <button type="button" onclick="callCenter.showModalCustomerInfo('{{$item['customer_id']}}','{{$item['type']}}');"
                            class="btn color_button m-btn--icon m--margin-left-10">
                            <span>
                            <span>{{__('VÀO')}}</span>
                            <i class="la la-arrow-right" style="padding-left: 5px;"></i>
                            </span>
                    </button>
                </td>
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
   </div>
