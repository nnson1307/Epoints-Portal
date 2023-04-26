<td><input type="text" class="form-control" id="input_product_{{$inventory_checking_detail_id}}" onkeydown="InventoryChecking.addSerialProduct(event,`{{$code}}`,`{{$inventory_checking_detail_id}}`)" placeholder="{{__('Nhập số serial và enter')}}"></td>
<td colspan="6" >
    <h5 style="white-space: initial">
        @foreach($listSerial[$inventory_checking_detail_id] as $key => $itemSerial)
            @if($key <= 9)
            <span class="badge badge-pill badge-secondary mr-3 mb-3" >{{$itemSerial['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="InventoryChecking.removeSerial(`{{$itemSerial['inventory_checking_detail_serial_id']}}`,{{$inventory_checking_detail_id}})"></i></span>
            @endif
        @endforeach
    </h5>
</td>
<td class="text-center">
    @if(count($listSerial[$inventory_checking_detail_id]) > 9)
    <a href="javascript:void(0)" onclick="InventoryChecking.showPopupListSerial({{$inventory_checking_detail_id}})">{{__('Xem thêm')}}</a>
    @endif
</td>