<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN')}}</th>
            <th class="ss--font-size-th">{{__('DANH MỤC')}}</th>
            <th class="ss--font-size-th">{{__('NHÃN')}}</th>
            <th class="ss--font-size-th">{{__('GIÁ')}}</th>
{{--            <th class="ss--font-size-th ss--text-center">{{__('TÌNH TRẠNG')}}</th>--}}
            <th class="ss--font-size-th ss--text-center">{{__('TRẠNG THÁI')}}</th>
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
                           href="{{route('product-detail',$item['proId'])}}">{{ $item['proName'] }}</a>
                    </td>
                    <td>{{ $item['proCategoryName'] }}</td>
                    <td>{{ $item['proModelName'] }}</td>
                    <td>
                        {{number_format($item['proPriceStandard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                    </td>
{{--                    <td class="ss--text-center">--}}
{{--                        @if ($item['proType']=="normal")--}}
{{--                            <span>{{__('Thường')}}</span>--}}
{{--                        @elseif($item['proType']=="hot")--}}
{{--                            <span>{{__('Hot')}}</span>--}}
{{--                        @else--}}
{{--                            <span>{{__('Mới')}}</span>--}}
{{--                        @endif--}}
{{--                    </td>--}}
                    <td class="ss--text-center">
                        @if(in_array('admin.product.change-status',session('routeList')))
                            @if ($item['proIsActived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="product.changeStatus(this, '{!! $item['proId'] !!}', 'publish')"
                                           checked name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="product.changeStatus(this, '{!! $item['proId'] !!}', 'unPublish')"
                                           name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['proIsActived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           checked  name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td class="pull-right">
                        @if(in_array('admin.product.edit',session('routeList')))
                            <a href="{{route('admin.product.edit',$item['proId'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Cập nhật')}}"><i class="la la-edit"></i></a>
                        @endif
                        @if(in_array('admin.product.remove',session('routeList')))
                            <button onclick="product.remove(this, '{{ $item['proId'] }}')"
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
{{--.--}}