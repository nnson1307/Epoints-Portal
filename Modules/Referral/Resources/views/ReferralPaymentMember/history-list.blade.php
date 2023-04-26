<div class="table-responsive" style="padding: 0px;padding-top:12px">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--text-center">#</th>
            <th class="ss--text-center">{{__('Phiếu chi')}}</th>
            <th class="ss--text-center">{{__('Người giới thiệu')}}</th>
            <th class="ss--text-center">{{__('Người thanh toán')}}</th>
            <th class="ss--text-center">{{__('Ngày thanh toán')}}</th>
            <th class="ss--text-center">{{__('Hình thức')}}</th>
            <th class="ss--text-center">{{__('Số tiền')}}</th>
            <th class="ss--text-center">{{__('Nhóm người nhận')}}</th>
            <th class="ss--text-center">{{__('Trạng thái')}}</th>
{{--            <th class="ss--text-center">{{__('Trạng thái')}}</th>--}}
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
                    <td>{{($list->currentPage() - 1)*$list->perPage() + $key+1 }}</td>
                    <td class="ss--text-center"><a href="{{route('payment',['view'=> 'detail' , 'payment_code' => $item['payment_code']])}}"> {{$item['payment_code']}}</a></td>
                    <td class="ss--text-center"><a href="{{route('referral.referral-member.detailReferral',['id' => $item['referral_member_id']])}}">{{$item['full_name']}}</a></td>
                    <td class="ss--text-center">{{$item['staff_full_name']}}</td>
                    <td class="ss--text-center">{{isset($item['payment_date']) ? \Carbon\Carbon::parse($item['payment_date'])->format('d/m/Y H:i') : ''}}</td>
                    <td class="ss--text-center">{{$item[getValueByLang('payment_method_name_')]}}</td>
                    <td class="ss--text-center">{{number_format($item['total_money'])}}</td>
                    <td class="ss--text-center">{{__('Khách hàng')}}</td>
                    <td class="ss--text-center"><p class="status_background" style="background-color : {{$item['status'] == 'reject' ? '#FF0000' : '#10B482'}}">{{$item['status'] == 'reject' ? 'Từ chối' : 'Đã thanh toán'}}</p></td>
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
