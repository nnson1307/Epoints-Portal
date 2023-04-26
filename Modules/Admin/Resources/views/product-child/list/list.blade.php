<div class="table-content">
    <div class="table-responsive">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th">#</th>
                <th class="ss--font-size-th">{{__('TÊN')}}</th>
                <th class="ss--font-size-th">{{__('DANH MỤC')}}</th>
                <th class="ss--font-size-th">{{__('NHÃN')}}</th>
                <th class="ss--font-size-th">{{__('GIÁ')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('TÌNH TRẠNG')}}</th>
                @if($typeTab == 'sale')
                    <th class="ss--font-size-th ss--text-center">% {{__('GIẢM GIÁ')}}</th>
                @endif
                <th class="ss--font-size-th"></th>
            </tr>
            </thead>
            <tbody>
            @if (isset($LIST))
                @foreach ($LIST as $key => $item)
                    <tr>
                        <td>{{$stt + $key}}</td>
                        <td>{{$item['product_child_name']}}</td>
                        <td>{{$item['category_name']}}</td>
                        <td>{{$item['product_model_name']}}</td>
                        <td>
                            {{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}{{__('đ')}}
                        </td>
                        <td class="ss--text-center">
                            {{$item['is_actived'] == 1 ? __('Hoạt động') : __('Tạm ngưng')}}
                        </td>
                        @if($typeTab == 'sale')
                            <td>
                                {{$item['percent_sale']}}
                            </td>
                        @endif
                        <td>
                            <button onclick="productChild.removeList(this, '{{ $typeTab }}', '{{ $item['product_child_id'] }}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}"><i class="la la-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    @if(isset($LIST))
        {{ $LIST->links('helpers.paging') }}
    @endif
</div>