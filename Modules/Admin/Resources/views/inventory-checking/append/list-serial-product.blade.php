<div class="pl-2 mt-3">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr>
                @if($type == 'detail')
                    <th width="33%" class="ss--font-size-th">{{__('Số Seri')}}</th>
                    <th width="33%" class="ss--font-size-th">{{__('Trạng thái')}}</th>
                @else
                    <th width="25%" class="ss--font-size-th">{{__('Số Seri')}}</th>
                    <th width="25%" class="ss--font-size-th">{{__('Trạng thái')}}</th>
                    @if($data['type_list'] == 'import')
                        <th width="25%" class="ss--font-size-th"></th>
                    @endif
                @endif
            </tr>
        </thead>
        <tbody>
        @if (count($listSerial) != 0)
            @foreach($listSerial as $item)
                <tr>
                    <td class="">{{$item['serial']}}</td>
                    <td class="">{{$item['inventory_checking_status_name']}}</td>
                @if($type == 'edit' && $data['type_list'] == 'import')
                    <td>
                        <a href="javascript:void(0)"><i class="fas fa-trash pl-2 pr-2" onclick="InventoryChecking.removeSerialProduct(`{{$item['inventory_checking_detail_serial_id']}}`,`{{$item['inventory_checking_detail_id']}}`,`{{$item['product_code']}}`,`{{$item['serial']}}`,'list')"></i></a>
                    </td>
                @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10">{{__('Không tìm thấy dữ liệu')}}</td>
            </tr>
        @endif
        </tbody>
    </table>


</div>
{{ $listSerial->links('admin::inventory-checking.helpers.paging-serial-product') }}