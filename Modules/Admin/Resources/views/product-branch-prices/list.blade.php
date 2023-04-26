<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table" id="tb-branch-price">

        <thead>
        <tr class="ss--font-size-th ss--nowrap">
            <th>#</th>
            <th>{{__('PHIÊN BẢN')}}</th>
            <th class="ss--text-center">{{__('NHÓM')}}</th>
            <th class="ss--text-center">{{__('GIÁ CHUẨN')}}</th>
            @foreach ($BRANCH as $key => $value)
                <th class="ss--width-max-width-350 ss--text-center">{{ $value }}</th>
            @endforeach
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr class="ss--font-size-13">
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td class="ss--width-max-width-350">{{$item['product_child_name']}}</td>
                    <td class="ss--text-center ss--nowrap">{{$item['category_name']}}</td>
                    <td class="ss--text-center ss--nowrap">{{ number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) }}</td>
                    @foreach ($item['branchPrice'] as $v)
                        <td class="ss--text-center ss--nowrap">{{ ($v == 0) ? 'Không có' : number_format($v, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)  }}</td>
                    @endforeach
                    <td>
                        @if(in_array('admin.product-branch-price.edit',session('routeList')))
                            <a href="{{route('admin.product-branch-price.edit',array ('id'=>$item['product_child_id']))}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="View">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}


