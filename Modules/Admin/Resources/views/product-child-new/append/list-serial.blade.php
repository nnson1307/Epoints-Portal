<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('SỐ SERIAL')}}</th>
            <th class="ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--font-size-th">{{__('CHI NHÁNH')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($listSerial))
            @foreach ($listSerial as $key=>$item)
                <tr class="ss--font-size-13 ss--nowrap">
                    @if(isset($page))
                        <td>{{ (($page-1)*10 + $key + 1) }}</td>
                    @else
                        <td>{{ ($key + 1) }}</td>
                    @endif
                    <td>
                        {{$item['serial']}}
                    </td>
                    <td>
                        {{$item['inventory_checking_status_name']}}
                    </td>
                    <td>
                        {{$item['warehouses_name']}}
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="ss--font-size-13 ss--nowrap">
                <td>{{__('Không có dữ liệu')}}</td>
            </tr>
        @endif
        </tbody>
    </table>
    {{ $listSerial->links('admin::product-child-new.helpers.paging-serial') }}
</div>
