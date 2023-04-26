<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th></th>
            <th class="ss--font-size-th">{{__('Tên nhân viên')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Tên queue')}}</th>
            @foreach ($roleQueue as $item)
            <th class="ss--text-center ss--font-size-th">{{ $item['name'] }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--font-size-13">{{ isset($page) ? ($page-1)*10 + $key+1 :$key+1 }}</td>
                    <td class="">
                        {{-- @if(in_array('ticket.queue.submit-edit',session('routeList'))) --}}
                            <button onclick="Shift.edit({{$item['ticket_staff_queue_id']}})"
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
                    <td class="ss--font-size-13"><a href="javascript:void(0);" onclick="Shift.view('{{ $item['ticket_staff_queue_id'] }}')">{{ isset($item->staff->full_name)?$item->staff->full_name:'' }}</a></td>
                    <td class="ss--font-size-13 text-center">
                        {{ $item['queue_name'] }}
                    </td>
                    @foreach ($roleQueue as $items)
                        @if ($item->ticket_role_queue_id != $items['ticket_role_queue_id'])
                            <td class="ss--font-size-13 text-center"></td>
                        @else
                            <td class="ss--font-size-13 text-center">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}