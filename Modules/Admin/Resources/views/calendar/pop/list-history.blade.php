<div>{{__("Danh sách lịch hẹn")}}:</div>
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tên dịch vụ')}}</th>
            <th class="tr_thead_list">{{__('Ngày hẹn')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td class="text_middle">{{ ($page-1)*5 + $key+1}}</td>
                    @else
                        <td class="text_middle">{{$key+1}}</td>
                    @endif
                    <td>{{$item['object_name']}}</td>
                    @if($item['end_date'] == null)
                        <td>{{\Carbon\Carbon::parse($item['date'])->format('d/m/Y') . ' ' .
                            \Carbon\Carbon::parse($item['time'])->format('H:i')}}</td>
                    @else
                        <td>{{__("Từ") . ' ' . \Carbon\Carbon::parse($item['date'])->format('d/m/Y') . ' ' .
                            \Carbon\Carbon::parse($item['time'])->format('H:i') . ' ' .
                             __("đến") . ' ' . \Carbon\Carbon::parse($item['end_date'])->format('d/m/Y') . ' ' .
                             \Carbon\Carbon::parse($item['end_time'])->format('H:i')}}</td>
                    @endif
                    <td>
                        @if($item['status']=='new')
                            <span class="m-badge m-badge--success m-badge--dot"></span> {{__('Mới')}}</span>
                        @elseif($item['status']=='confirm')
                            <span class="m-badge m-badge--accent m-badge--dot"></span> {{__('Xác nhận')}}</span>
                        @elseif($item['status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--dot"></span> {{__('Hủy')}}</span>
                        @elseif($item['status']=='finish')
                            <span class="m-badge m-badge--primary m-badge--dot"></span> {{__('Hoàn thành')}}</span>
                        @elseif($item['status']=='wait')
                            <span class="m-badge m-badge--warning m-badge--dot"></span> {{__('Chờ phục vụ')}}</span>
                        @elseif($item['status']=='processing')
                            <span class="m-badge m-badge--info m-badge--dot"></span> {{__('Đang thực hiện')}}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
