<span class="m-badge {{isset($column['option'][$data]['color'])? 'm-badge--'.$column['option'][$data]['color'] : ''}} m-badge--wide"
@if(isset($column['attribute']) && is_array($column['attribute']))
    @foreach ($column['attribute'] as $attr_key => $attr_value)
        {{' '.$attr_key.'='.$attr_value.''}}
    @endforeach
@endif
>
{{$column['option'][$data]['name']}}
</span>