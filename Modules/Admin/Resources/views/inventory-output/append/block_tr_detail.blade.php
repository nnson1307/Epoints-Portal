<td><input type="text" class="form-control" id="input_product_{{$inventory_output_detail_id}}" onkeydown="InventoryOutput.addSerialProduct(event,`{{$code}}`,`{{$inventory_output_detail_id}}`)" placeholder="{{__('Nhập số serial và enter')}}"></td>
<td colspan="8" >
    <h5 style="white-space: initial">
        @if(isset($listSerial[$inventory_output_detail_id]))
            @foreach($listSerial[$inventory_output_detail_id] as $key => $itemSerial)
                @if($key <= 9)
                <span class="badge badge-pill badge-secondary mr-3 mb-3" >{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryOutput.removeSerial(`{{$itemSerial['inventory_output_detail_serial_id']}}`,{{$inventory_output_detail_id}})"></i></span>
                @endif
            @endforeach
        @endif
    </h5>
</td>
<td class="text-center">
    @if(isset($listSerial[$inventory_output_detail_id]) && count($listSerial[$inventory_output_detail_id]) > 9)
        <a href="javascript:void(0)" onclick="InventoryOut.showPopupListSerial({{$inventory_output_detail_id}})">{{__('Xem thêm')}}</a>
    @endif
</td>