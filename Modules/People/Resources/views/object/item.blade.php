<tr>
    <td class="ss--font-size-13">{{ $list->perpage()*($list->currentpage()-1)+($key+1) }}</td>
    <td class="ss--font-size-13">{{ $item['code'] }}</td>
    <td class="ss--font-size-13">
        @if($item['people_object_name']==$item['people_object_group_name'])
            {{$item['people_object_name']}}
        @else
            {{$item['people_object_group_name']}} - {{$item['people_object_name']}}
        @endif
    </td>
    <td class="ss--font-size-13">{{ $item['full_name'] }}</td>
    <td class="ss--font-size-13">{{ date_format($item['created_at']??'', 'H:i d/m/Y')}}</td>
    <td class="ss--font-size-13 ajax-object-status-edit" method="POST" action="{{route('people.object.ajax-change-status')}}">
        <input type="hidden" name="people_object_id" value="{{$item['people_object_id']}}">
        <input type="hidden" name="is_active" value="0">
        @if(in_array('people.object.ajax-change-status',session('routeList')))
            @if ($item['is_active'])
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label class="ss--switch">
                        <input type="checkbox"
                               checked class="manager-btn" name="is_active">
                        <span></span>
                    </label>
                </span>
            @else
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label class="ss--switch">
                        <input type="checkbox"
                               class="manager-btn" name="is_active">
                        <span></span>
                    </label>
                </span>
            @endif
        @else
            @if ($item['is_active'])
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           checked class="manager-btn" disabled>
                                    <span></span>
                                </label>
                            </span>
            @else
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           class="manager-btn" disabled>
                                    <span></span>
                                </label>
                            </span>
            @endif
        @endif
    </td>

    <td class="ss--font-size-13">
        @if(in_array('people.object.ajax-edit-modal',session('routeList')))
            <button

                    method = "POST"
                    action = "{{route('people.object.ajax-edit-modal')}}"
                    data-people_object_id="{{$item['people_object_id']}}"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill ajax submit"
                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
            </button>
        @endif
        @if(in_array('people.object.ajax-delete',session('routeList')))
            <button
                    onclick="people_object_delete(this)"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                    method="POST"
                    action="{{route('people.object.ajax-delete')}}"
                    data-people_object_id="{{$item['people_object_id']}}"
                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
            </button>
        @endif
    </td>
    <td></td>
</tr>