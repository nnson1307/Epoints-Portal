<table class="table table-striped m-table ss--header-table ss--nowrap">
    <thead class="bg">
    <tr>
        <th class="ss--font-size-th">{{__('stt')}}</th>
        <th class="ss--font-size-th">{{__('Loại thiết bị/người dùng')}}</th>
        <th class="ss--font-size-th">{{__('Thời gian')}}</th>
        <th class="ss--font-size-th">{{__('Bàn')}}</th>
    </tr>
    </thead>
    <tbody>
        @if(isset($list) && count($list) != 0)
            @foreach($list as $key => $item)
                <tr>
                    <td>
                        {{$list->perpage()*($list->currentpage()-1)+($key+1)}}
                    </td>
                    <td>{{$item['device_name']}}</td>
                    <td>{{isset($item['created_at']) ? \Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y') : ''}}</td>
                    <td>{{$item['table_name']}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

@if(isset($list))
    {{ $list->links('fnb::qr-code.helpers.paging') }}
@endif