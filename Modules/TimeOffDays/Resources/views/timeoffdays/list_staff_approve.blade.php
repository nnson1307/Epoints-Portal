<label>
    {{__('Người duyệt')}}:
</label>
<ul class="d-flex flex-row" style="list-style-type: none; padding-left: 0px;">
@if($staffApprove)
    <?php $index = 0; ?>
    @foreach ($staffApprove as $key => $item)
        <?php $index++; ?>
        @if($item['staff_id'] != Auth()->id())
            <li class="d-flex flex-column align-items-center">
                <img src="{{$item['staff_avatar']}}" onerror="if (this.src != '/static/backend/images/default-placeholder.png') this.src = '/static/backend/images/default-placeholder.png';" class="m--img-rounded m--marginless" alt="photo" width="50px" height="50px">    
                <p class="font-weight-bold  mt-2">{{$item['full_name']}}</p>
                <p>{{$item['staff_title']}}</p>
            </li>
            @if($index != count($staffApprove))
                <li class="d-flex flex-column align-self-center color" style="padding-left: 20px; padding-right: 20px;">
                    <i class="fa fa-thin fa-arrow-right"></i>
                </li>
            @endif
           
            <input type="hidden" value="{{$item['staff_id'] ?? 0}}" name="staff_id_level{{$index}}" id="staff_id_level{{$index}}"/>
        @endif
    @endforeach
@endif
</ul>