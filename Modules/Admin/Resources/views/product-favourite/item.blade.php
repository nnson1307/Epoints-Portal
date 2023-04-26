<tr class="tr-product-favourite">

    <td class="ss--font-size-13">{{ $list->perpage()*($list->currentpage()-1)+($key+1) }}</td>

    <td class="ss--font-size-13">
        {{ $item['customer_name']??'' }}
    </td>

    <td class="ss--font-size-13">
        {{ $item['customer_phone']??'' }}
    </td>

    <td class="ss--font-size-13 ajax submit"
        action="{{route('admin.product-favourite.ajax-detail-modal')}}"
        method="POST"
        data-customer_id="{{$item['user_id']}}"
    >
        {{ $item['product_favourite_total']??'' }}
    </td>

    <td></td>
</tr>