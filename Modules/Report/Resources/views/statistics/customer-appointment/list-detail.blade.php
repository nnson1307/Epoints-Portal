<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('MÃ LỊCH HẸN')</th>
            <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('TÊN CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY HẸN')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>{{$item['customer_appointment_code']}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['status']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['date'])->format('d/m/Y') . ' ' . \Carbon\Carbon::parse($item['time'])->format('h:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
