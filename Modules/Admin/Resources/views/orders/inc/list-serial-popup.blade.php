<div class="pl-2 mt-3">
    <table class="w-100">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @if (count($listSerial) != 0)
            <tr>
                <td>
                    <h4>
                        @foreach($listSerial as $item)
                            @if($type_view == 'edit')
                                <span class="badge badge-pill badge-secondary" >{{$item['serial']}} <i class="fas fa-times pl-2 pr-2" onclick="order.removeSerial('{{$session}}','{{$id}}','{{$product_code}}','{{$numberRow}}','{{$item['serial']}}',true)"></i></span>
                            @else
                                <span class="badge badge-pill badge-secondary" >{{$item['serial']}} <i class="fas fa-times pl-2 pr-2"></i></span>
                            @endif
                        @endforeach
                    </h4>
                </td>
            </tr>
        @else
            <tr>
                <td colspan="10">{{__('Không tìm thấy dữ liệu')}}</td>
            </tr>
        @endif
        </tbody>
    </table>


</div>
{{ $listSerial->links('admin::orders.helpers.paging') }}