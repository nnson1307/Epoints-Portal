<td>
    <select class="form-control input_child_{{$numberRow}}" onkeydown="order.enterSerial(event,'{{$id}}','{{$numberRow}}')">
        <option value="">{{__('Nhập số serial và enter')}}</option>
    </select>
</td>
<td colspan="6" class="block_tr_child_{{$numberRow}}">
    @foreach($listSerial as $key => $item)
        @if($key <= 3)
            <span class="badge badge-pill badge-secondary" >{{$item['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="order.removeSerial('{{$session}}','{{$id}}','{{$product_code}}','{{$numberRow}}','{{$item['serial']}}')"></i></span>
        @endif
    @endforeach
</td>
<td>
    @if(count($listSerial) > 4)
        <a href="javascript:void(0)" onclick="order.showPopupSerial('{{$session}}','{{$id}}','{{$product_code}}','{{$numberRow}}')">{{__('Xem thêm')}}</a>
    @endif
</td>