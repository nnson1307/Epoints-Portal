<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Hành động')}}</th>
            <th class="tr_thead_list text-center">{{__('Chính sách hoa hồng')}}</th>
            <th class="tr_thead_list">{{__('Hoa hồng')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái hoa hồng')}}</th>
            <th class="tr_thead_list">{{__('Ngày tạo')}}</th>
            <th class="tr_thead_list">{{__('Ngày ghi nhận')}}</th>
        </tr>
        </thead>
        <tbody style="font-size: 13px">
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
                    <td>{{($list->currentPage() - 1)*$list->perPage() + $key+1 }}</td>
                    <td>
                        @if(in_array($item['rpc_status'], ['new' , 'approve']) )
                            <a href="javascript:void(0)" onclick="referralProgramInvite.rejectCommission({{$item['referral_program_commission_id']}})"><img src="{{asset('static/backend/images/cancel_affiliate.png')}}"></a>
                        @endif

                        @if($item['rpc_status'] == 'reject')
                            <a href="javascript:void(0)" onclick="referralProgramInvite.showRejectCommission({{$item['referral_program_invite_id']}})"><img src="{{asset('static/backend/images/edit_affiliate.png')}}"></a>
                        @endif
                    </td>
                    <td class="ss--text-center"> {{$item['referral_program_name'].' - '.($item['type'] == 'cpi' ? $item['refer_customer_name'] : $item['obj_code'])}}</td>
                    <td class="ss--text-center"> {{number_format($item['total_money'])}}</td>
{{--                    <td class="ss--text-center ">--}}
{{--                        <p class="status_background" style="background-color : {{$item['status'] == 'new' ? '#fff' : ($item['status'] == 'approve' ? '#2F6F76' : ($item['status'] == 'reject' ? '#2F6F76' : ''))}}">{{$item['status'] == 'new' ? __('Mới') : ($item['status'] == 'approve' ? __('Đã ghi nhận') : ($item['status'] == 'reject' ? __('Đã từ chối') : ''))}}</p>--}}
{{--                    </td>--}}
                    <?php
                    $background = '';
                    if($item['status'] == 'new'){
                        $background = '#00BCD4';
                    } else if($item['status'] == 'approve'){
                        $background = '#2F6F76';
                    } else if($item['status'] == 'reject'){
                        $background = '#FF0000';
                    } else if($item['status'] == 'waiting_payment') {
                        $background = '#4fc4cb';
                    } else if($item['status'] == 'payment') {
                        $background = '#1365C6';
                    }
                    ?>
                    <td class="ss--text-center "><p class="status_background" style="background-color : {{$background}}">
                            @if($item['rpc_status'] == 'new')
                                {{__('Mới')}}
                            @elseif($item['rpc_status'] == 'approve')
                                {{__('Đã ghi nhận')}}
                            @elseif($item['rpc_status'] == 'reject')
                                {{__('Từ chối')}}
                            @elseif($item['rpc_status'] == 'waiting_payment')
                                {{__('Chờ thanh toán')}}
                            @elseif($item['rpc_status'] == 'payment')
                                {{__('Đã thanh toán')}}
                            @endif
                        </p>
                    </td>
                    <td class="ss--text-center">{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}} </td>
                    <td class="ss--text-center"> {{$item['approve_date'] ? \Carbon\Carbon::parse($item['approve_date'])->format('d/m/Y H:i') : 'N/A'}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-center">{{__('Không có dữ liệu')}}</td>
            </tr>
        @endif
        </tbody>

    </table>
</div>
{{ $list->links('helpers.paging') }}
