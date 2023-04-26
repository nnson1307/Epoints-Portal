<div class="table-responsive" style="padding: 0px;padding-top:12px">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--text-center">#</th>
            <th class="ss--text-center">{{__('Hành động')}}</th>
            <th class=" ss--text-center">{{__('Chính sách hoa hồng')}}</th>
            <th class="ss--text-center">{{__('Người mua')}}</th>
            <th class="ss--text-center">{{__('Hoa hồng')}}</th>
            <th class="ss--text-center">{{__('Trạng thái hoa hồng')}}</th>
            <th class="ss--text-center">{{__('Ngày tạo')}}</th>
            <th class="ss--text-center">{{__('Ngày ghi nhận')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
                    <td><a href="{{route('referral.commission-order.commissionOrderDetail', ['id' => $item['referral_program_invite_id']])}}">{{($list->currentPage() - 1)*$list->perPage() + $key+1 }}</a></td>
                    <td>
                        <a href="{{route('referral.commission-order.commissionOrderDetail', ['id' => $item['referral_program_invite_id']])}}"><i class="fa fa-eye" aria-hidden="true"></i></a>

                        @if(in_array($item['status'], ['new', 'approve']))
                            <a href="javascript:void(0)" onclick="referralProgramInvite.reject({{$item['referral_program_invite_id']}})"><img src="{{asset('static/backend/images/cancel_affiliate.png')}}"></a>
                        @endif

                        @if($item['status'] == 'reject')
                            <a href="javascript:void(0)" onclick="referralProgramInvite.showReject({{$item['referral_program_invite_id']}})">
                                <i class="far fa-clipboard"></i>
                            </a>
                        @endif
                    </td>

                    <td class="ss--text-center"> <a href="{{route('referral.detailCommission',['id' => $item['referral_program_id']])}}">{{$item['referral_program_name']}}</a> -
                        @if($item['type'] == 'cpi')
                            <a href="{{route('referral.referral-member.detailReferral',['id' => $item['refer_referral_member_id']])}}">{{$item['refer_customer_name']}}</a>
                        @else
                            <a href="{{route('admin.order.detail',['id' => $item['obj_id']])}}">{{$item['obj_code']}}</a>
                        @endif
                    </td>
                    <td class="ss--text-center"><a href="{{route('referral.referral-member.detailReferral',['id' => $item['member_buyer_referral_member_id']])}}"> {{$item['customer_buyer_full_name']}}</a></td>

                    <td class="ss--text-center"> {{number_format($item['total_money'])}}</td>
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
                            @if($item['status'] == 'new')
                                {{__('Mới')}}
                            @elseif($item['status'] == 'approve')
                                {{__('Đã ghi nhận')}}
                            @elseif($item['status'] == 'reject')
                                {{__('Từ chối')}}
                            @elseif($item['status'] == 'waiting_payment')
                                {{__('Chờ thanh toán')}}
                            @elseif($item['status'] == 'payment')
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
{{ $list->links('helpers.referral-paging') }}
