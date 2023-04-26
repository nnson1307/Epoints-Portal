<tr class="tr_people">
    <td>
        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
            <input class="check_one" type="checkbox"
                   {{isset($arrCheck[$item['people_id']]) ? 'checked' : ''}}
                   onclick="index.choosePeople(this)">
            <span></span>

            <input type="hidden" class="people_id" value="{{$item['people_id']}}">
        </label>
    </td>
    <td class="ss--font-size-13">{{ $list->perpage()*($list->currentpage()-1)+($key+1) }}</td>
    <td class="ss--font-size-13">
        <a
                href="javascript:void(0)"
                @if(in_array('people.people.ajax-detail-modal',session('routeList')))
           class="ajax submit"
           method="POST"
           action="{{route('people.people.ajax-detail-modal')}}"
           data-people_id="{{$item['people_id']}}"
            @endif
        >{{ $item['full_name'] }}</a>
    </td>
    <td class="ss--font-size-13">{{ \Carbon\Carbon::parse($item['birthday'])->format('d/m/Y') }}</td>
    <td class="ss--font-size-13">{{ $item['id_number'] }}</td>
    <td class="ss--font-size-13">{{ $item['temporary_address'] }}</td>
    <td class="ss--font-size-13">
        @if($item['is_verified'])
            @if($item['people_object_name']==$item['people_object_group_name'])
                {{$item['people_object_name']}}
            @else
                {{$item['people_object_group_name']}} - {{$item['people_object_name']}}
            @endif

        @else
            Chưa phúc tra
        @endif
    </td>

    <td class="ss--font-size-13">

        @if(in_array('people.people.ajax-edit-modal',session('routeList')))
            <button
                    method = "POST"
                    action = "{{route('people.people.ajax-edit-modal')}}"
                    data-people_id="{{$item['people_id']}}"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill ajax submit"
                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
            </button>
        @endif
        @if(in_array('people.verify.ajax-add-modal',session('routeList')))
            <button
                    method = "POST"
                    action = "{{route('people.verify.ajax-add-modal')}}"
                    data-people_id="{{$item['people_id']}}"
                    data-people_verification_id="{{$param['people_verification_id']??0}}"

                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill button ajax submit"
                    title="{{__('Phúc tra')}}"><i class="fas fa-calendar-plus"></i>
            </button>
        @endif
        @if(in_array('people.people.ajax-delete',session('routeList')))
            <button
                    onclick="people_delete(this)"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill "
                    method="POST"
                    action="{{route('people.people.ajax-delete')}}"
                    data-people_id="{{$item['people_id']}}"
                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
            </button>
        @endif
        @if(in_array('people.people.print-preview',session('routeList')))
            <button
                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                    method="GET"
                    action="{{route('people.people.print-preview')}}"
                    data-people_id="{{$item['people_id']}}"
                    title="{{__('In')}}"><a target="_blank" href="{{route('people.people.print-preview')}}?people_id={{$item['people_id']}}"><i class="fas fa-print"></i></a>
            </button>
        @endif


    </td>

    <td></td>
</tr>