<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('TÊN KHO')}}</th>
            <th class="tr_thead_list">{{__('TÊN SẢN PHẨM')}}</th>
            <th class="tr_thead_list text-center">{{__('TỒN KHO')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr class="ss--font-size-13 ss--nowrap">
                    @if(isset($page))
                        <td>{{ (($page-1)*10 + $key + 1) }}</td>
                    @else
                        <td>{{ ($key + 1) }}</td>
                    @endif
                    <td>{{$item['warehouseName']}}</td>
                    <td>{{ $item['productChildName']}}</td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
