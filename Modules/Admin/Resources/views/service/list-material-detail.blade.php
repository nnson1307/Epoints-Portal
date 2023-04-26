<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table_product">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Mã sản phẩm')}}</th>
            <th class="tr_thead_list">{{__('Sản phẩm')}}</th>
            <th class="tr_thead_list">{{__('Số lượng')}}</th>
            <th class="tr_thead_list">{{__('Đơn vị tính')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($itemMaterial as $key=>$value)
            <tr>
                <td>
                    @if(isset($page))
                        {{ ($page-1)*$display + $key+1}}
                    @else
                        {{$key+1}}
                    @endif
                </td>
                <td>{{$value['material_code']}}</td>
                <td>{{$value['product_name']}}</td>
                <td>{{$value['quantity']}}</td>
                <td>{{$value['name']}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
{{$itemMaterial->links('helpers.paging') }}