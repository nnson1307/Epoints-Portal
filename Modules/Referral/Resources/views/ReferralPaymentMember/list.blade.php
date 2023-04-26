<div class="table-responsive" style="padding: 0px;padding-top:12px">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--text-center">
                <a onclick="$('#select-all').trigger('click')" id="select-all-div" href="javascript:void(0)">
                    {{__('Tất cả')}}
                </a>
                <input onclick="referralPaymentMember.clickAll()" type="checkbox" name="select-all" id="select-all" />
                | #
            </th>
            <th class=" ss--text-center">{{__('Hành động')}}</th>
            <th class="ss--text-center">{{__('Phiếu chi')}}</th>
            <th class="ss--text-center">{{__('Người giới thiệu')}}</th>
            <th class="ss--text-center">{{__('Nhóm người nhận')}}</th>
            <th class="ss--text-center">{{__('Hình thức')}}</th>
            <th class="ss--text-center">{{__('Số tiền')}}</th>
            <th class="ss--text-center">{{__('Trạng thái')}}</th>
{{--            <th class="ss--text-center">{{__('Trạng thái')}}</th>--}}
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
                    <td>
                        <input type="checkbox" class="checkbox-all" name="referral_payment_member_id[{{$item['referral_payment_member_id']}}]" value="{{$item['referral_payment_member_id']}}" id="checkbox-1" /> - {{($list->currentPage() - 1)*$list->perPage() + $key+1 }}
                    </td>
                    <td class="ss--text-center">
                        @if(isset($item['status']) && $item['status'] != 'reject')
                            <a href="javascript:void(0)" onclick="referralPaymentMember.reject({{$item['referral_payment_member_id']}})"><img src="{{asset('static/backend/images/cancel_affiliate.png')}}"></a>
                        @endif
                        <a class="ml-3" href="{{route('payment',['referral'=> true,'payment_id' => $item['payment_id']])}}" target="_blank">
                            <img width="20px" height="20px" src="{{asset('static/backend/images/edit_affiliate.png')}}">
                        </a>
                    </td>
                    <td class="ss--text-center">{{$item['payment_code']}}</td>
                    <td class="ss--text-center"><a href="{{route('referral.referral-member.detailReferral',['id' => $item['referral_member_id']])}}">{{$item['full_name']}}</a></td>
                    <td class="ss--text-center">{{__('Khách hàng')}}</td>
                    <td class="ss--text-center">{{$item[getValueByLang('payment_method_name_')]}}</td>
                    <td class="ss--text-center">{{number_format($item['total_money'])}}</td>
                    <td class="ss--text-center"><p class="status_background" style="background-color : #2296F3">{{$item['status'] == 'new' ? 'Mới' : ''}}</p></td>
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
{{$list->appends($filters)->links('helpers.paging')}}
