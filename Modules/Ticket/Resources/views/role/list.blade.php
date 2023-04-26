<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th></th>
            <th class="ss--font-size-th ss--text-center">{{__('Tên role')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Mô tả')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Ngày tạo')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Người tạo')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Ngày cập nhật')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Người cập nhật')}}</th>
            {{-- <th class="ss--text-center ss--font-size-th">{{__('Trạng thái')}}</th> --}}
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key=>$item)
                <tr>
                    <td class="ss--font-size-13">{{ isset($page) ? ($page-1)*10 + $key+1 :$key+1 }}</td>
                    <td class="pull-right">
                        {{-- @if(in_array('ticket.queue.submit-edit',session('routeList'))) --}}
                            <button onclick="Shift.edit({{$item['ticket_role_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                            </button>
                        {{-- @endif --}}
                        {{-- @if(in_array('ticket.queue.remove',session('routeList'))) --}}
                            {{-- <button onclick="Shift.remove(this, '{{ $item['ticket_queue_id'] }}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
                            </button> --}}
                        {{-- @endif --}}
                    </td>
                    <td class="ss--font-size-13 ss--text-center"><a href="javascript:void(0);" onclick="Shift.view('{{ $item['ticket_role_id'] }}')">{{ $item['name'] }}</a></td>
                    <td class="ss--font-size-13">{{ $item['description'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($item['created_at']), 'd/m/Y H:i') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{isset($item->staff_created->full_name)?$item->staff_created->full_name:''}}</td>
                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($item['updated_at']), 'd/m/Y H:i')}}</td>
                    <td class="ss--text-center ss--font-size-13">{{isset($item->staff_updated->full_name)?$item->staff_updated->full_name:''}}</td>
                    
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}