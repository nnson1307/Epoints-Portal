<td class="append-tr"><span class="{{$item['status'] == 'new' ? 'text-warning' : ($item['status'] == 'processing' ? 'text-primary' : 'text-success')}}">{{$item['status'] == 'new' ? __('Chưa xử lý') : ($item['status'] == 'processing' ? __('Đang xử lý') : __('Hoàn thành'))}}</span></td>
<td class="append-tr">{{$item['process_name']}}</td>
<td class="append-tr">{{$item['process_at'] != '' ? \Carbon\Carbon::parse($item['process_at'])->format('H:i d/m/Y') : ''}}</td>
<td class="block_status append-tr">
    @if(in_array($item['status'],['new','processing']))
        <button type="button" class="btn btn-info" onclick="PopupAction.confirmCustomerRequest('{{$item['table_id']}}','{{$item['customer_request_id']}}','{{$item['status'] == 'new' ? 'processing' : 'done'}}')">
            {{$item['status'] == 'new' ? __('Đang xử lý') : __('Hoàn thành')}}
        </button>
    @endif
</td>