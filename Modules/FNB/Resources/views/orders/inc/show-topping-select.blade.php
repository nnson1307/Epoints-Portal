@if(isset($product_attribute_id)) <span> @foreach(array_values($product_attribute_id) as $key => $item){{$product_attribute_name[$item]}} @if($key != count($product_attribute_id) - 1) , @endif @endforeach</span><br>@endif
@if(isset($topping) && count($topping) != 0)
    <span><b>Topping</b> : @foreach($topping as $key => $value) @if($key == 0) {{$topping_name[$value]}} @else , {{$topping_name[$value]}} @endif @endforeach</span>
@endif