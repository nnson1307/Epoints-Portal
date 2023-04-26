<a href="{{$link}}" 
    @if(isset($column['attribute']) && is_array($column['attribute']))
        @foreach ($column['attribute'] as $attr_key => $attr_value)
            {{' '.$attr_key.'='.$attr_value.''}}
        @endforeach
    @endif
>
    {{$data}}
</a>