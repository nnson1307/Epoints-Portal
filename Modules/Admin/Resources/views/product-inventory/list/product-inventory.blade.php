<div class="table-responsive" role="tabpanel">
    <table class="table m-table ss--header-table table-list-product-inventory" id="tb-product-inventory">
        <thead>
        <tr style="text-transform: uppercase;">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th" style="white-space:nowrap">{{__('MÃ SẢN PHẨM')}}</th>
            <th class="ss--font-size-th" style="white-space:nowrap">{{__('TÊN SẢN PHẨM')}}</th>
            <th class="ss--font-size-th ss--text-center ss--nowrap">{{__('NGÀY TẠO')}}</th>
            @if(Auth::user()->is_admin == 1)
                <th class="ss--text-center ss--font-size-th" style="white-space:nowrap">{{__('TẤT CẢ KHO')}}</th>
            @endif
            @foreach($wareHouse as $key => $value)
                <th class="ss--text-center ss--font-size-th" style="white-space:nowrap">{{$value}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(isset($productChild))
            @foreach($productChild as $key => $item)
                <tr>
                    <td class="ss--font-size-13">
                        {{($page - 1) * $display + ($key + 1)}}
                    </td>
                    <td class="product-code ss--font-size-13" style="width:130px;max-width: 150px">
                        {{$item['product_code']}}
                    </td>
                    <td class="product-name ss--font-size-13" style="max-width: 320px">
                        <a href="javascript:void(0)"
                           onclick="getHistory('{{ $item['product_code'] }}')"
                           data-toggle="modal" data-target="#history"
                           class="">{{$item['product_child_name']}}</a>
                    </td>
                    <td class="ss--font-size-13 ss--nowrap"
                        style="text-align: center">
                        @if($item['created_at'] != null)
                            {{(new DateTime($item['created_at']))->format('d/m/Y')}}
                        @endif
                    </td>
                    @if(Auth::user()->is_admin==1)
                        <td class="ss--font-size-13" style="text-align: center">
                            {{@$result[$item['product_child_id']]['total'] ?? 0}}
                        </td>
                    @endif
                    @foreach($wareHouse as $k => $i)
                        <td class="ss--text-center ss--font-size-13">
                            {{@$result[$item['product_child_id']][$k] ?? 0}}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $productChild->links('admin::product-inventory.helper.paging-product-inventory') }}