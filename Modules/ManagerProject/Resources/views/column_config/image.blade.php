<img src="{{$data}}" class=" {{ isset($class) ? $class : '' }}" 
@if(isset($column['attribute']) && is_array($column['attribute']))
    @foreach ($column['attribute'] as $attr_key => $attr_value)
        {{' '.$attr_key.'='.$attr_value.''}}
    @endforeach
@endif>