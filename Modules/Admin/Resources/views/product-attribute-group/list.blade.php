<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN THUỘC TÍNH')}}</th>
            <th class="ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--font-size-th">{{__('NGÀY TẠO')}}</th>
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
                    <td class="ss--font-size-13">{{ $item['product_attribute_group_name'] }}</td>
                    <td class="ss--font-size-13">
                        @if(in_array('admin.product-attribute-group.change-status',session('routeList')))
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="productAttributeGroup.changeStatus(this, '{!! $item['product_attribute_group_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="productAttributeGroup.changeStatus(this, '{!! $item['product_attribute_group_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived'])
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
                    <td class="ss--font-size-13">{{ date_format($item['created_at'], 'd/m/Y')}}</td>
                    <td class="pull-right ss--font-size-13">
                        @if(in_array('admin.product-attribute-group.submit-edit',session('routeList')))
                            <button onclick="productAttributeGroup.edit({{$item['product_attribute_group_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}} (VI)"><i class="la la-edit"></i></button>
                        @endif
                        @if(in_array('fnb.product-attribute-group.edit',session('routeList')))
                            <button type="button" onclick="productAttributeGroupFNB.edit({{$item['product_attribute_group_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}} (EN)"><i class="la la-edit"></i></button>
                        @endif
                        @if(in_array('admin.product-attribute-group.remove',session('routeList')))
                            <button onclick="productAttributeGroup.remove(this, '{{ $item['product_attribute_group_id'] }}')"
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