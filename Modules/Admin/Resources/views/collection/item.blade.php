<tr class="tr_collection">

    <td class="ss--font-size-13">{{ $list->perpage()*($list->currentpage()-1)+($key+1) }}</td>

    <td class="ss--font-size-13">
        <img src="{{ $item['image_web'] }}" style="height:40px;">
    </td>

    <td class="ss--font-size-13">
        <img src="{{ $item['image_app'] }}" style="height:40px;">
    </td>

    <td class="ss--font-size-13">
       {{ $item['source'] }}
    </td>
    <td class="ss--font-size-13">
        {{ $item['link'] }}
    </td>
    <td class="ss--font-size-13">
        {{ \Carbon\Carbon::parse($item['created_at'])->format('H:i d/m/Y') }}
    </td>

    <td class="ss--font-size-13">
        @if(1||in_array('admin.collection.ajax-edit-modal',session('routeList')))
            <button
                    method = "POST"
                    action = "{{route('admin.collection.ajax-edit-modal')}}"
                    data-checkin_collection_id="{{$item['checkin_collection_id']}}"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill ajax submit"
                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
            </button>
        @endif
        @if(1||in_array('admin.collection.ajax-delete',session('routeList')))
            <button
                    onclick="item_delete(this)"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill "
                    method="POST"
                    action="{{route('admin.collection.ajax-delete')}}"
                    data-checkin_collection_id="{{$item['checkin_collection_id']}}"
                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
            </button>
        @endif

    </td>

    <td></td>
</tr>