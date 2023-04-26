<tr class="tr-product-category-parent">

    <td class="ss--font-size-13">{{ $list->perpage()*($list->currentpage()-1)+($key+1) }}</td>

    <td class="ss--font-size-13">
        {{ $item['product_category_parent_name'] }}
    </td>

    <td class="ss--font-size-13">
        <img src="{{ $item['icon_image'] }}" style="height:40px;">
    </td>



    <td class="ss--font-size-13">
        @if(1||in_array('admin.product-category-parent.ajax-edit-modal',session('routeList')))
            <button
                    method = "POST"
                    action = "{{route('admin.product-category-parent.ajax-edit-modal')}}"
                    data-product_category_parent_id="{{$item['product_category_parent_id']}}"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill ajax submit"
                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
            </button>
        @endif
        @if(1||in_array('admin.product-category-parent.ajax-delete',session('routeList')))
            <button
                    onclick="item_delete(this)"
                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill "
                    method="POST"
                    action="{{route('admin.product-category-parent.ajax-delete')}}"
                    data-product_category_parent_id="{{$item['product_category_parent_id']}}"
                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
            </button>
        @endif

    </td>

    <td></td>
</tr>