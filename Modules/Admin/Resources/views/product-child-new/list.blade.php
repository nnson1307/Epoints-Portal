<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN')}}</th>
            <th class="ss--font-size-th">{{__('ĐƠN VỊ')}}</th>
            <th class="ss--font-size-th">{{__('GIÁ GỐC')}}</th>
            <th class="ss--font-size-th">{{__('GIÁ BÁN')}}</th>
            <th class="ss--font-size-th">{{__('SITE')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('TÌNH TRẠNG')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('HIỂN THỊ TRÊN APP')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('PHỤ THU')}}</th>
            <th class="ss--font-size-th"></th>
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
                    <td>
                        <a class="ss--text-black" title="{{__('Chi tiết')}}"
                           href="{{route('admin.product-child-new.detail',$item['product_child_id'])}}">{{ $item['product_child_name'] }}</a>
                    </td>
                    <td>{{$item['unit_name']}}</td>
                    <td>
                        {{number_format($item['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                    </td>
                    <td>
                        {{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                    </td>
                    <td>{{$item['site_id']}}</td>
                    <td class="ss--text-center">
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="list_prod_child.changeActive({!! $item['product_child_id'] !!}, 0)"
                                           name="" checked>
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="list_prod_child.changeActive({!! $item['product_child_id'] !!}, 1)"
                                           name="">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td class="ss--text-center">
                        @if(in_array('admin.product-child-new.update-status',session('routeList')))
                            @if ($item['is_display'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="list_prod_child.changeDisplay({!! $item['product_child_id'] !!}, 0)"
                                           name="" checked>
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="list_prod_child.changeDisplay({!! $item['product_child_id'] !!}, 1)"
                                           name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" {{ $item['is_display'] == 1 ? 'checked' : '' }}
                                    name="">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                        <td class="ss--text-center">
                            @if ($item['is_surcharge'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="list_prod_child.changeSurcharge({!! $item['product_child_id'] !!}, 0)"
                                           name="" checked>
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="list_prod_child.changeSurcharge({!! $item['product_child_id'] !!}, 1)"
                                           name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        </td>
                    <td class="pull-right">
                        @if(in_array('admin.product-child-new.edit',session('routeList')))
                            <a href="{{route('admin.product-child-new.edit',$item['product_child_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Cập nhật')}}"><i class="la la-edit"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}