<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('Loại yêu cầu')}}</th>
            <th class="ss--font-size-th">{{__('Ngày tạo')}}</th>
            <th class="ss--font-size-th">{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--font-size-13">{{ isset($page) ? ($page-1)*10 + $key+1 :$key+1 }}</td>
                    <td class="ss--font-size-13">{{ $item['name'] }}</td>
                    <td class="ss--font-size-13">{{date_format(new DateTime($item['created_at']), 'd/m/Y H:i') }}</td>
                    <td class="ss--font-size-13">
                        {{-- @if(in_array('ticket.queue.change-status',session('routeList'))) --}}
                            @if ($item['is_active'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="Shift.changeStatus(this, '{!! $item['ticket_issue_group_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="Shift.changeStatus(this, '{!! $item['ticket_issue_group_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        {{-- @else
                            @if ($item['is_active'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif --}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}