<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN NGUỒN ĐƠN HÀNG')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr>
                    <td class="ss--font-size-13">{{ ($key+1) }}</td>
                    <td class="ss--font-size-13">{{ $item['order_source_name'] }}</td>
                    <td class="ss--text-center ss--font-size-13">
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="OrderSource.changeStatus(this, '{!! $item['order_source_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="OrderSource.changeStatus(this, '{!! $item['order_source_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($item['created_at']), 'd/m/Y')}}
                    <td class="pull-right ss--font-size-13">
                        @if($item['order_source_id']!=1 && $item['order_source_id']!=2)
                            @if(in_array('admin.order-source.submit-edit',session('routeList')))
                                <button onclick="OrderSource.edit({{$item['order_source_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Cập nhật"><i class="la la-edit"></i>
                                </button>
                            @endif
                            @if(in_array('admin.order-source.remove',session('routeList')))
                                <button onclick="OrderSource.remove(this, '{{ $item['order_source_id'] }}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Xóa"><i class="la la-trash"></i>
                                </button>
                            @endif
                        @endif
                    </td>

                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}