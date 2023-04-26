{{-- 0 ẩn --}}
{{-- 1 show edit --}}
{{-- 2 show delete --}}
{{-- 3 show view --}}
@if($data == 0)

@elseif($data == 1)
<a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
href="{{route('ticket.add', $item['ticket_id'])}}">
    <i class="la la-edit"></i>
</a>
@elseif($data == 2)
<a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
    href="{{route('ticket.add', $item['ticket_id'])}}">
        <i class="la la-edit"></i>
    </a>
@elseif($data == 3)

@endif