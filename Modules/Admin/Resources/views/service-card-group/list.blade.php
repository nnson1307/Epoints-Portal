<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN NHÓM THẺ')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr>
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="ss--font-size-13">{{$key+1}}</td>
                    @endif
                    <td class="ss--font-size-13">{{ $item['name'] }}</td>

                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($item['created_at']), 'd/m/Y')}}
                    <td class="pull-right ss--font-size-13">
                        @if($item['order_source_id']!=1)
                            @if(in_array('admin.order-source.submit-edit',session('routeList')))
                                <button onclick="serviceCardGroup.edit({{$item['service_card_group_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                                </button>
                            @endif
                            @if(in_array('admin.order-source.remove',session('routeList')))
                                <button onclick="serviceCardGroup.remove(this, '{{ $item['service_card_group_id'] }}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Xóa')}}"><i class="la la-trash"></i>
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