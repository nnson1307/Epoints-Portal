<div class="table-responsive">
    <table id="table-product"
           class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th width="33%" class="ss--font-size-th">{{__('Số serial')}}</th>
            <th width="33%" class="ss--font-size-th">{{__('Trạng thái')}}</th>
            <th width="33%" class="ss--font-size-th">{{__('Chi nhánh')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($listSerial as $key=>$value)
            <tr class="ss--select2-mini">
                <td class="stt ss--font-size-13">{{$value['serial']}}</td>
                <td class="ss--font-size-13">{{ $value['inventory_checking_status_name'] }}</td>
                <td class="ss--font-size-13">{{ $value['warehouses_name'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $listSerial->links('admin::product.helpers.paging') }}
</div>