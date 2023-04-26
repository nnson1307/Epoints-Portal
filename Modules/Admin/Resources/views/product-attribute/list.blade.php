<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th>#</th>
            <th class="ss--font-size-th">{{__('NHÓM THUỘC TÍNH')}}</th>
            <th class="ss--font-size-th">{{__('NHÃN THUỘC TINH')}}</th>
            <th class="ss--font-size-th">{{__('MÃ THUỘC TÍNH')}}</th>
            <th class="ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--font-size-th">{{__('NGÀY TẠO')}}</th>
            <th class="ss--font-size-th"></th>
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
                    <td class="ss--font-size-13">{{ $item['proAttrGrName'] }}</td>
                    <td class="ss--font-size-13">{{ $item['proAttrLabel'] }}</td>
                    <td class="ss--font-size-13">{{ $item['proAttrCode'] }}</td>
                    <td class="ss--font-size-13">
                        @if(in_array('admin.product-attribute.change-status',session('routeList')))
                            @if ($item['isActive'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="productAttribute.changeStatus(this, '{!! $item['proAttId'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="productAttribute.changeStatus(this, '{!! $item['proAttId'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['isActive'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td class="ss--font-size-13">{{ date_format(new DateTime($item['createdAt']), 'd/m/Y')}}</td>
                    <td class="pull-right ss--font-size-13">
                        @if(in_array('admin.product-attribute.submit-edit',session('routeList')))
                            <button onclick="productAttribute.edit({{$item['proAttId']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i></button>
                        @endif
{{--                        @if(in_array('fnb.product-attribute.edit',session('routeList')))--}}
                            <button type="button" onclick="productAttributeFNB.edit({{$item['proAttId']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}} (EN)"><i class="la la-edit"></i></button>
{{--                        @endif--}}
                        @if(in_array('admin.product-attribute.remove',session('routeList')))
                            <button onclick="productAttribute.remove(this, '{{ $item['proAttId'] }}')"
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