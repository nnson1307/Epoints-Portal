<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Mã lịch hẹn')}}</th>
            <th class="tr_thead_list">{{__('Tên khách hàng')}}</th>
            <th class="tr_thead_list text-center">{{__('Số lượng khách')}}</th>
            <th class="tr_thead_list text-center">{{__('Ngày hẹn')}}</th>
            <th class="tr_thead_list text-center">{{__('Nguồn')}}</th>
            <th class="tr_thead_list text-center">{{__('Nguồn')}}</th>
            <th class="tr_thead_list text-center">{{__('Trạng thái')}}</th>

        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>{{$item['customer_appointment_code']}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td class="text-center">
                        {{$item['customer_quantity']}}
                    </td>
                    <td class="text-center">
                        {{date("H:i",strtotime($item['time'])).' '.date("d/m/Y",strtotime($item['date']))}}
                    </td>
                    <td class="text-center">
                        @if($item['customer_appointment_type']=='appointment')
                            {{__('Đặt lịch trước')}}
                        @else
                            {{__('Đến trực tiếp')}}
                        @endif
                    </td>
                    <td class="text-center">
                        {{$item['appointment_source_name']}}
                    </td>
                    <td class="text-center">
                        @if($item['status']=='cancel')
                            <span class="m-badge m-badge--danger m-badge--wide width-85">{{__('Hủy')}}</span>
                        @elseif($item['status']=='new')
                            <span class="m-badge m-badge--success m-badge--wide width-85">{{__('Mới')}}</span>
                        @elseif($item['status']=='wait')
                            <span class="m-badge m-badge--warning m-badge--wide width-85">{{__('Chờ phục vụ')}}</span>
                        @elseif($item['status']=='confirm')
                            <span class="m-badge m-badge--accent m-badge--wide width-85">{{__('Xác nhận')}}</span>
                        @elseif($item['status']=='finish')
                            <span class="m-badge m-badge--primary m-badge--wide width-85">{{__('Hoàn thành')}}</span>
                        @endif

                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
