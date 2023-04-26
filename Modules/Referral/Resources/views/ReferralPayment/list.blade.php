<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--text-center">#</th>
            <th class=" ss--text-center">{{__('Tên kỳ hoa hồng')}}</th>
            <th class="ss--text-center">{{__('Kỳ trả')}}</th>
            <th class="ss--text-center">{{__('Tổng hoa hồng')}}</th>
{{--            <th class="ss--text-center">{{__('Trạng thái')}}</th>--}}
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach ($list as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
                    <td>{{($list->currentPage() - 1)*$list->perPage() + $key+1 }}</td>
                    <td class="ss--text-center"><a href="{{route('referral.referral-payment-member.index',['id' => $item['referral_payment_id']])}}" >{{$item['name']}}</a></td>
                    <td class="ss--text-center">{{$item['period']}}/{{\Carbon\Carbon::now()->format('Y')}}</td>
                    <td class="ss--text-center"> {{number_format($item['total_money'])}}</td>
{{--                    <td class="ss--text-center">Đã Thanh toán</td>--}}
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
