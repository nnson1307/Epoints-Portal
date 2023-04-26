<td><input type="text" class="form-control" id="input_product_{{$inventory_input_detail_id}}" onkeydown="InventoryInput.addSerialProduct(event,`{{$code}}`,`{{$inventory_input_detail_id}}`)" placeholder="{{__('Nhập số serial và enter')}}"></td>
<td colspan="6" >
    <h5 style="white-space: initial">
        @if(isset($listSerial[$inventory_input_detail_id]))
            @foreach($listSerial[$inventory_input_detail_id] as $key => $itemSerial)
                @if($key <= 9)
                    <span class="badge badge-pill badge-secondary mr-3 mb-3" >{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryInput.removeSerial(`{{$itemSerial['inventory_input_detail_serial_id']}}`,{{$inventory_input_detail_id}})"></i></span>
                @endif
            @endforeach
        @endif
    </h5>
</td>
<td class="text-center">
    @if(isset($listSerial[$inventory_input_detail_id]) && count($listSerial[$inventory_input_detail_id]) > 9)
        <a href="javascript:void(0)" onclick="InventoryInput.showPopupListSerial({{$inventory_input_detail_id}})">{{__('Xem thêm')}}</a>
    @endif
</td>