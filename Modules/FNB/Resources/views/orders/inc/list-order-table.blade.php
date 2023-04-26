@if(count($list) != 0)
<div class="tag__header">
    @if(isset($orderId) && isset($list[$orderId]))
        <div class="btn btn-info btn-info-update background-style-1">
            <a href="{{route('fnb.orders.receipt',['id'=> $list[$orderId]['order_id'] ,'type' => 'order'])}}">
{{--                {{ $list[$orderId]['table_name'].' - '.$list[$orderId]['order_code'] }}--}}
                {{ 'GH - '.$list[$orderId]['order_code'] }}
            </a>
        </div>
    @endif
    @foreach($list as $item)
        @if(!isset($orderId) || (isset($orderId) && $item['order_id'] != $orderId ))
            <div class="btn btn-info btn-info-update">
                <a href="{{route('fnb.orders.receipt',['id'=> $item['order_id'] ,'type' => 'order'])}}">
{{--                    {{ $item['table_name'].' - '.$item['order_code'] }}--}}
                    {{ 'GH - '.$item['order_code'] }}
                </a>
    {{--            @if(isset($orderId) && $orderId == $item['order_id'])--}}
    {{--                <span class="la la-close" onclick="order.removeOrder('{{$item['order_id']}}','list')"></span>--}}
    {{--            @else--}}
    {{--                <span class="la la-close" onclick="order.removeOrder('{{$item['order_id']}}')"></span>--}}
    {{--            @endif--}}
            </div>
        @endif
    @endforeach
</div>
@endif