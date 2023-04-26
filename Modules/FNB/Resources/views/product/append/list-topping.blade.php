@if(count($listTopping) != 0)
    @foreach(array_values($listTopping) as $key => $item)
        <tr class="product_child_id_{{$item['product_child_id']}}">
            <td>{{$key+1}}</td>
            <td>
                <input type="hidden" class="product_child_id" name="list[{{$item['product_child_id']}}][product_child_id]" value="{{$item['product_child_id']}}">
                <input type="hidden" class="product_child_name" name="list[{{$item['product_child_id']}}][product_child_name]" value="{{$item['product_child_name']}}">
                {!! $item['product_child_name'] !!}
            </td>
            <td><input class="quantity text-center" id="quantity_{{$item['product_child_id']}}" onchange="product.changeSelectTopping(true,'{{$item["product_child_id"]}}')" type="text" value="{{$item['quantity']}}" name="list[{{$item['product_child_id']}}][quantity]"></td>
            <td>
                <a class="remove_product" href="javascript:void(0)" style="color: #a1a1a1" onclick="product.removeTopping('{{$item["product_child_id"]}}')">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif