<tr class="tr-cart">

    <td class="ss--font-size-13">{{ $list->perpage()*($list->currentpage()-1)+($key+1) }}</td>

    <td class="ss--font-size-13">
        {{ $item['customer_name']??'' }}
    </td>

    <td class="ss--font-size-13">
        {{ $item['customer_phone']??'' }}
    </td>

    <td class="ss--font-size-13">
        {{ $item['branch_name']??'' }}
    </td>

    <td class="ss--font-size-13 ajax submit"
        action="{{route('admin.cart.ajax-detail-modal')}}"
        method="POST"
        data-cart_id="{{$item['cart_id']}}"
    >
        {{ $item['cart_total']??'' }}
    </td>

    <td></td>
</tr>