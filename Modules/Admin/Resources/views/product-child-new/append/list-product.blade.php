<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TỒN KHO')}}</th>
            <th class="ss--font-size-th">{{__('GIÁ BÁN')}}</th>
            <th class="ss--font-size-th">{{__('GIÁ GỐC')}}</th>
            <th class="ss--font-size-th">{{__('CHI NHÁNH')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($listInventory))
            @foreach ($listInventory as $key=>$item)
                <tr class="ss--font-size-13 ss--nowrap">
                    @if(isset($page))
                        <td>{{ (($page-1)*10 + $key + 1) }}</td>
                    @else
                        <td>{{ ($key + 1) }}</td>
                    @endif
                    <td>
                        @if(in_array($item['inventory_management'],['all','serial']))
                            <a href="javascript:void(0)" onclick="detailProduct.showPopup(`{{$item['warehouse_id']}}`,`{{$item['product_code']}}`)">
                                {{$item['quantity']}}
                            </a>
                        @else
                            {{$item['quantity']}}
                        @endif
                    </td>
                    <td>
                        {{number_format($item['price'])}}
                    </td>
                    <td>
                        {{number_format($item['cost'])}}
                    </td>
                    <td>
                        {{$item['warehouse_name']}}
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
    {{ $listInventory->links('admin::product-child-new.helpers.paging') }}
</div>
