<ul>
    @foreach($list as $item)
        <li class="child-li-{{$item['referral_member_id']}}">
            <a @if($item['total_node_nearest']) onclick="loadChild(this, {{$item['referral_member_id']}}, {{$lv}})" @endif data-rand="{{uniqid(5)}}" data-id="{{$item['referral_member_id']}}" href="javascript:void(0);">LV : {{$lv}} | {{$item['full_name']}} | SL : {{$item['total_node_nearest']}}</a>
            <div class="vertical-tree-container"></div>
        </li>
    @endforeach
</ul>
