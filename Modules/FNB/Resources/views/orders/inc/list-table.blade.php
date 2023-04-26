<input type="hidden" id="table_id_new" value="">
<div class="m-portlet__body" style="spadding: 0px;padding-top:30px; width:950px">
    <div class="table-responsive">
        <table class="table  m-table ss--header-table">
            <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th ss--text-center">{{__('Khu vực')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Tên bàn')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Số ghế')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($listTable) && count($listTable) != 0)
                @foreach($listTable as $item)
                    <tr class="ss--font-size-13 ss--nowrap table-selected" data-table-id="{{$item['table_id']}}" data-location="{{$item['area_name'].' - '.$item['table_name']}}" data-seat="{{$item['seats']}}" onclick="order.selectTablePopup(this)">
                        <td class="ss--text-center">{{$item['area_name']}}</td>
                        <td class="ss--text-center">{{$item['table_name']}}</td>
                        <td class="ss--text-center">{{$item['seats']}}</td>
                    </tr>
                @endforeach
            @else
                <tr class="ss--font-size-13 ss--nowrap">
                    <td colspan="5">
                        <div class="not_find" style="text-align: center;font-weight: bold;">
                            <span>Chưa có bàn nào</span>
                        </div>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        {{ $listTable->links('fnb::orders.helpers.paging-move') }}
    </div>
</div>
