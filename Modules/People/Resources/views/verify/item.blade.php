<tr>
    <td class="ss--font-size-13">{{ $list->perpage()*($list->currentpage()-1)+($key+1) }}</td>
    <td class="ss--font-size-13">{{ $item['people_verification_name'] }}</td>
    <td class="ss--font-size-13">

            @if($item['people_object_name']==$item['people_object_group_name'])
                {{$item['people_object_name']}}
            @else
                {{$item['people_object_group_name']}} - {{$item['people_object_name']}}
            @endif

    </td>
    <td class="ss--font-size-13">{{ $item['content'] }}</td>
    <td class="ss--font-size-13">{{ $item['people_health_type_name'] }}</td>
    <td class="ss--font-size-13">{{ $item['note'] }}</td>

    <td class="ss--font-size-13">
        @if(in_array('people.verify.ajax-edit-modal',session('routeList')))
            <button
                    method = "POST"
                    action = "{{route('people.verify.ajax-edit-modal')}}"
                    data-people_verify_id="{{$item['people_verify_id']}}"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill ajax submit"
                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
            </button>
        @endif
            @if(in_array('people.verify.ajax-delete',session('routeList')))
                <button
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        onclick="people_verify_delete(this)"
                        method="POST"
                        action="{{route('people.verify.ajax-delete')}}"
                        data-people_verify_id="{{$item['people_verify_id']}}"
                        title="{{__('Xóa')}}"><i class="la la-trash"></i>
                </button>
            @endif

    </td>

    <td></td>
</tr>