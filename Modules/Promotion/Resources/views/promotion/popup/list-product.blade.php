<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">
                <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                    <input class="check_all" name="check_all" type="checkbox" onclick="view.chooseAll(this, 'product')">
                    <span></span>
                </label>
            </th>
            <th class="tr_thead_list">{{__('TÊN SẢN PHẨM')}}</th>
            <th class="tr_thead_list">{{__('MÃ SẢN PHẨM')}}</th>
            <th class="tr_thead_list">{{__('DANH MỤC')}}</th>
            <th class="tr_thead_list">{{__('GIÁ BÁN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($list) > 0)
            @foreach($list as $v)
                <tr>
                    <td>
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input class="check_one" name="check_one" type="checkbox" {{isset($arrProductTemp[$v['product_code']]) ? 'checked' : ''}}
                                   onclick="view.choose(this, 'product')">
                            <span></span>
                            <input type="hidden" class="product_name" value="{{$v['product_child_name']}}">
                            <input type="hidden" class="product_id" value="{{$v['product_id']}}">
                            <input type="hidden" class="product_code" value="{{$v['product_code']}}">
                            <input type="hidden" class="base_price" value="{{$v['price']}}">
                        </label>
                    </td>
                    <td>{{$v['product_child_name']}}</td>
                    <td>{{$v['product_code']}}</td>
                    <td>{{$v['category_name']}}</td>
                    <td>{{number_format($v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $list->links('helpers.paging') }}
</div>

