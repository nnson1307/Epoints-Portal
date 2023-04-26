@if(count($listProduct) != 0)
    @foreach($listProduct as $key => $item)
        <tr class="blockListIncurred">
            <td class="col_1_Incurred">{{$key+1}}</td>
            <input type="hidden" class="form-control p-1 productIdIncurred" name="incurred[{{$key}}][product_id]" value="{{isset($item['product_id']) ? $item['product_id'] : ''}}">
            <input type="hidden" class="form-control p-1 " name="incurred[{{$key}}][product_code]" value="{{$item['product_code']}}">
            <input type="hidden" class="form-control p-1 " name="incurred[{{$key}}][product_name]" value="{{$item['product_name']}}">
            <input type="hidden" class="form-control p-1 " name="incurred[{{$key}}][product_quantity]" value="{{$item['product_quantity']}}">
            <input type="hidden" class="form-control p-1 " name="incurred[{{$key}}][product_unit]" value="{{$item['product_unit']}}">
            <input type="hidden" class="form-control p-1 " name="incurred[{{$key}}][product_money]" value="{{$item['product_money']}}">
            <td>{{$item['product_code']}}</td>
            <td>{{$item['product_name']}}</td>
{{--            <td class="text-center">--}}
{{--                <div>--}}
{{--                    <button type="button" class="d-inline form-control" disabled style="width: inherit"> - </button>--}}
{{--                    <input type="text"  class="form-control d-inline text-center p-1 " disabled style="width : 45%" value="{{$item['product_quantity']}}" >--}}
{{--                    <button type="button" class="d-inline form-control" style="width: inherit" disabled > + </button>--}}
{{--                </div>--}}
{{--            </td>--}}
            <td class="text-center">
                {{$item['product_quantity']}}
            </td>
            <td class="text-center">{{$item['product_unit']}}</td>
            <td class="text-center">{{ $item['product_money'] }} VNĐ</td>
            <td>
{{--                <button type="button" onclick="Acceptance.deleteRowIncurredMain(`{{$item['product_code']}}`,`{{$item['product_id']}}`)" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">--}}
                <button type="button" onclick="Acceptance.deleteRowIncurredMain(`{{$item['product_name']}}`,`{{$item['product_id']}}`)" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    @endforeach
@else
    <tr class="text-center">
        <td colspan="7">{{__('ticket::acceptance.no_data')}}</td>
    </tr>
@endif
