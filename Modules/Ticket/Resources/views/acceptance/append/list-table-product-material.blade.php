
@if(count($listProduct) != 0)
    @foreach($listProduct as $key => $item)
        <tr id="block_product_{{$item['ticket_request_material_detail_id']}}">
            <input type="hidden" name="ticket_request_material_detail[{{$item['ticket_request_material_detail_id']}}][ticket_request_material_detail_id]" value="{{$item['ticket_request_material_detail_id']}}">
            <input type="hidden" name="ticket_request_material_detail[{{$item['ticket_request_material_detail_id']}}][quantity_approve]" value="{{$item['quantity_approve'] == '' || $item['quantity_approve'] == null ? 0 : $item['quantity_approve']}}">
            <td>{{$key+1}}</td>
            <td>{{$item['product_code']}}</td>
            <td>{{$item['product_name']}}</td>
            <td class="text-center">{{$item['quantity_approve'] == '' || $item['quantity_approve'] == null ? 0 : $item['quantity_approve']}}</td>
            <td class="text-center">
                <div id="block_material_product_{{$item['ticket_request_material_detail_id']}}">
                    @if(isset($type) && $type == 'detail')
                        <button type="button" disabled class="d-inline form-control" style="width: inherit" > - </button>
                        <input type="text" disabled name="ticket_request_material_detail[{{$item['ticket_request_material_detail_id']}}][quantity_reality]" id="material_product_{{$item['ticket_request_material_detail_id']}}" class="form-control w-25 d-inline text-center" value="{{$item['quantity_reality'] == '' || $item['quantity_reality'] == null ? 0 : $item['quantity_reality']}}" >
                        <button type="button" disabled class="d-inline form-control" style="width: inherit" > + </button>
                    @else
                        <button type="button" class="d-inline form-control" style="width: inherit" onclick="Acceptance.changeNumber({{$item['ticket_request_material_detail_id']}},{{$item['quantity_approve']}},'sub')"> - </button>
                        <input type="text" name="ticket_request_material_detail[{{$item['ticket_request_material_detail_id']}}][quantity_reality]" id="material_product_{{$item['ticket_request_material_detail_id']}}" onfocusout="Acceptance.changeNumber({{$item['ticket_request_material_detail_id']}},{{$item['quantity_approve']}},'')" class="form-control w-25 d-inline text-center" value="{{$item['quantity_reality'] == '' || $item['quantity_reality'] == null ? 0 : $item['quantity_reality']}}" >
                        <button type="button" class="d-inline form-control" style="width: inherit" onclick="Acceptance.changeNumber({{$item['ticket_request_material_detail_id']}},{{$item['quantity_approve']}},'plus')"> + </button>
                    @endif
                </div>
            </td>
            <td class="text-center" id="quantity_return_{{$item['ticket_request_material_detail_id']}}">{{$item['quantity_return'] == '' || $item['quantity_return'] == null ? 0 : $item['quantity_return']}}</td>
            <td class="text-center">{{$item['unit_name']}}</td>
        </tr>
    @endforeach
@else
    <tr class="text-center">
        <td colspan="7">{{__('ticket::acceptance.no_data')}}</td>
    </tr>
@endif
