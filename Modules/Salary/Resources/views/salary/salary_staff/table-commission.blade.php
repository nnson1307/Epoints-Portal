<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="text-center ss--font-size-th">#</th>
            <th class="text-center ss--font-size-th">{{__('Vai trò')}}</th>
            <th class="text-center ss--font-size-th">{{__('Mã hợp đồng')}}</th>
            <th class="text-center ss--text-center ss--font-size-th">{{__('Tên hợp đồng')}}</th>
            @if(isset($check->type_view) && $check->type_view == "kt")
                <th class="text-center ss--text-center ss--font-size-th">{{__('Mã ticket')}}</th>
            @endif
            <th class="text-center ss--text-center ss--font-size-th">{{__('Giá trị thanh toán')}}</th>
            <th class="text-center ss--text-center ss--font-size-th">{{__('Trạng thái')}}</th>
            <th class="text-center ss--text-center ss--font-size-th">{{__('Tỉ lệ hoa hồng')}}</th>
            <th class="text-center ss--text-center ss--font-size-th">{{__('Giá trị hoa hồng nhận được')}}</th>
        </tr>
        </thead>
        <tbody>
            @foreach($list as $key => $item)
                <tr>
                    <td class="text-center">{{($list->currentPage() - 1)*$list->perPage() + ($key+1)}}</td>
                    <td class="text-center">@if($item['role'] == 'sale') Kinh doanh @else Kỹ thuật @endif</td>
                    <td class="text-center">{{$item['contract_code']}}</td>
                    <td class="text-center">{{$item['contract_name']}}</td>
                        @if(isset($check->type_view) && $check->type_view == "kt")
                            <td class="text-center">
                                <a href="{{route('ticket.detail',['id' => $item['ticket_id']])}}">
                                    {{$item['ticket_code']}}
                                </a>
                            </td>
                        @endif
                    <td class="text-center">
                        {{number_format($item['value'])}} VND
                    </td>
                    <td class="text-center">{{$item['status_name']}}</td>
                    <td class="text-center">{{number_format($item['percent'],2)}}</td>
                    <td class="text-center">{{number_format($item['commission'])}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $list->links('Salary::salary.salary_staff.paging') }}
<div class="row mt-4">
    <div class="col-6 text-left">
        <h4>{{__('Tổng giá trị thanh toán hợp đồng')}} : {{$totalValue != null ? number_format($totalValue['total_value']) : 0}} VND</h4>
    </div>
    <div class="col-6 text-right">
        <h4>{{__('Tổng giá trị hoa hồng nhận được')}} : {{ number_format($totalValue['total_commisson'])}} VND</h4>
    </div>
</div>


