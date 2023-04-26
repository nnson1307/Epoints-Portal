<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN NHÃN HIỆU SẢN PHẨM')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr>
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ (($page-1)*10 + $key + 1) }}</td>
                    @else
                        <td class="ss--font-size-13">{{ ($key + 1) }}</td>
                    @endif
                    <td class="ss--font-size-13">{{ $item['product_model_name'] }}</td>
                    <td class="ss--font-size-13 ss--text-center">{{ date_format($item['created_at'], 'd/m/Y')}}</td>
                    <td class="pull-right ss--font-size-13">
                        @if(in_array('admin.product-model.submit-edit',session('routeList')))
                            <button onclick="productModel.edit({{$item['product_model_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i></button>
                        @endif
                        @if(in_array('admin.product-model.remove',session('routeList')))
                            <button onclick="productModel.remove(this, '{{ $item['product_model_id'] }}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}"><i class="la la-trash"></i></button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}