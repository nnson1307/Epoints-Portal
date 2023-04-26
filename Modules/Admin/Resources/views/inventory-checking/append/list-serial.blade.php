<div class="pl-2 mt-3">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr>
                @if($type == 'detail')
                    <th width="33%" class="ss--font-size-th">{{__('Số Seri')}}</th>
                    <th width="33%" class="ss--font-size-th">{{__('Trạng thái')}}</th>
                    <th width="33%" class="ss--font-size-th">{{__('Xử lý')}}</th>
                @else
                    <th width="25%" class="ss--font-size-th">{{__('Số Seri')}}</th>
                    <th width="25%" class="ss--font-size-th">{{__('Trạng thái')}}</th>
                    <th width="25%" class="ss--font-size-th">{{__('Xử lý')}}</th>
                    <th width="25%" class="ss--font-size-th"></th>
                @endif
            </tr>
        </thead>
        <tbody>
        @if (count($listSerial) != 0)
            @foreach($listSerial as $item)
                <tr>
                    <td class="">{{$item['serial']}}</td>
                    <td class="">{{$item['name']}}</td>
                    <td class="">
                        @if(!isset($listProductSerial[$item['product_code'].'-'.$item['serial']]))
                            {{$item['is_new'] == 1 ? __('Nhập kho') : ($item['type_resolve'] == 0 ? __('Xuất kho') : '')}}
                        @else
                            @if($item['is_new'] == 1)
                                {{__('Nhập kho')}}
                            @else
                                {{__('Tồn kho')}}
                            @endif
                        @endif
                    </td>
                @if($type == 'edit')
                    <td>
                        <a href="javascript:void(0)"><i class="fas fa-trash pl-2 pr-2" onclick="InventoryChecking.removeSerial(`{{$item['inventory_checking_detail_serial_id']}}`,`{{$item['inventory_checking_detail_id']}}`,`{{$item['product_code']}}`,`{{$item['serial']}}`,'list')"></i></a>
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
{{ $listSerial->links('admin::inventory-checking.helpers.paging') }}