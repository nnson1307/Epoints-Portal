<div class="table-responsive" style="max-height: 450px;">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list text-center">#</th>
            @if($optionConfigShow['show_column_customer_name'] == '1')
                <th class="tr_thead_list text-center">@lang('Tên khách hàng')</th>
            @endif

            @if($optionConfigShow['show_column_customer_phone'] == '1')
                <th class="tr_thead_list text-center">@lang('Số điện thoại')</th>
            @endif

            @if($optionConfigShow['show_column_customer_type'] == '1')
                <th class="tr_thead_list text-center">@lang('Loại khách hàng')</th>
            @endif

            @if($optionConfigShow['show_column_customer_request_type'] == '1')
                <th class="tr_thead_list text-center">@lang('Loại yêu cầu')</th>
            @endif

            @if($optionConfigShow['show_column_customer_request_content'] == '1')
                <th class="tr_thead_list text-center">@lang('Nội dung yêu cầu')</th>
            @endif

            @if($optionConfigShow['show_column_customer_request_date'] == '1')
                <th class="tr_thead_list text-center">@lang('Ngày yêu cầu')</th>
            @endif

            @if($optionConfigShow['show_column_customer_request_staff_receipt'] == '1')
                <th class="tr_thead_list text-center">@lang('Nhân viên tiếp nhận')</th>
            @endif

            @if($optionConfigShow['show_column_customer_request_staff_assign'] == '1')
                <th class="tr_thead_list text-center">@lang('Nhân viên phụ trách')</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td class="text-center" style="vertical-align: middle;">
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    @if($optionConfigShow['show_column_customer_name'] == '1')
                        <td class="text-center" style="vertical-align: middle;">
                            @if($item['object_type'] == 'customer_lead')
                                <a href="javascript:void(0)" onclick="callCenter.showModalCustomerInfoSuccess('{{$item['customer_request_id']}}')">
                                    {{$item['customer_lead_name']}}
                                </a>
                            @else
                            <a href="javascript:void(0)" onclick="callCenter.showModalCustomerInfoSuccess('{{$item['customer_request_id']}}')">
                                    {{$item['customer_name']}}
                                </a>
                            @endif
                        
                        </td>
                    @endif
        
                    @if($optionConfigShow['show_column_customer_phone'] == '1')
                        <td class="text-center" style="vertical-align: middle;">
                            {{$item['customer_request_phone']}}
                        </td>
                    @endif
        
                    @if($optionConfigShow['show_column_customer_type'] == '1')
                        <td class="text-center" style="vertical-align: middle;">
                            @if($item['object_type'] == 'customer_lead')
                                @lang('Khách hàng tiềm năng')
                            @else
                                @lang('Khách hàng')
                            @endif
                        
                        </td>
                    @endif
        
                    @if($optionConfigShow['show_column_customer_request_type'] == '1')
                        <td class="text-center" style="vertical-align: middle;">
                            @switch($item['customer_request_type'])
                                @case('quote')
                                    @lang('Yêu cầu báo giá')
                                    @break
                                @case('consult')
                                    @lang('Yêu cầu tư vấn')
                                    @break
                                @default
                                    @lang('Khác')
                            @endswitch
                        </td>
                    @endif
        
                    @if($optionConfigShow['show_column_customer_request_content'] == '1')
                        <td class="text-center " style="vertical-align: middle;">
                            <span class="text-row-3">
                                {{ $item['customer_request_note'] }}
                            </span>
                        </td>
                    @endif
        
                    @if($optionConfigShow['show_column_customer_request_date'] == '1')
                        <td class="text-center" style="vertical-align: middle;">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $item['created_at'])->format('d-m-Y H:s') }}
                        </td>
                    @endif
        
                    @if($optionConfigShow['show_column_customer_request_staff_receipt'] == '1')
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{route('admin.staff.show', $item['staff_id'])}}" target="_blank">
                                {{$item['staff_name']}}
                            </a>
                        </td>
                    @endif
        
                    @if($optionConfigShow['show_column_customer_request_staff_assign'] == '1')
                        <td class="text-center" style="vertical-align: middle;">
                            @if(isset($item['sale_id']))
                            <a href="{{route('admin.staff.show', $item['sale_id'])}}" target="_blank">
                                {{$item['sale_name']}}
                            </a>
                            @endif
                        </td>
                    @endif
                   
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

</div>
