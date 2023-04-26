<div id="autotable" class="mt-3">
    <div class="table-content m--padding-top-30">
        <div class="table-responsive">
            <table class="table table-striped m-table m-table--head-bg-default" id="table-config">
                <thead class="bg">
                    <tr>
                        <th class="tr_thead_list text-center">#</th>
                        <th class="tr_thead_list text-center">{{__('Mã Ticket')}}</th>
                        <th class="tr_thead_list text-center">{{__('Loại yêu cầu')}}</th>
                        <th class="tr_thead_list text-left">{{__('Tiêu đề')}}</th>
                        <th class="tr_thead_list text-center">{{__('Queue')}}</th>
                        <th class="tr_thead_list text-center">{{__('Yêu cầu')}}</th>
                        <th class="tr_thead_list text-center">{{__('Thời gian phát sinh')}}</th>
                        <th class="tr_thead_list text-center">{{__('Thời gian bắt buộc hoàn thành')}}</th>
                        <th class="tr_thead_list text-center">{{__('Nhân viên chủ trì')}}</th>
                        <th class="tr_thead_list text-center">{{__('Trạng thái')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($list))
                        @foreach ($list as $key => $item)
                            <tr class="text-center">
                                <td>
                                    @if (isset($page))
                                        {{ ($page - 1) * 10 + $key + 1 }}
                                    @else
                                        {{ $key + 1 }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('ticket.detail', $item['ticket_id']) }}">
                                        {{ $item['ticket_code'] }}
                                    </a>
                                </td>
                                <td>
                                    {{ isset($item->issue_group->name)?$item->issue_group->name:'' }}
                                </td>
                                <td class="text-left">
                                    {{ isset($item->title) ? $item->title : '' }}
                                </td>
                                <td>
                                    {{ (isset($item->queue->queue_name))? $item->queue->queue_name : '' }}
                                </td>
                                <td>
                                    {{ (isset($item->issue->name)) ? $item->issue->name : '' }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item['date_issue'])->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item['date_expected'])->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    {{ isset($item->operate->full_name) ? $item->operate->full_name : '' }}
                                </td>
                                <td class="text-center">
                                    @if ($item['ticket_status_id'] == 1)
                                        <span class="m-badge m-badge--success m-badge--wide"
                                            style="width: 80%">{{ $item->status->status_name }}</span>
                                    @elseif ($item['ticket_status_id'] == 2)
                                        <span class="m-badge m-badge--second m-badge--wide"
                                            style="width: 80%">{{ $item->status->status_name }}</span>
                                    @elseif ($item['ticket_status_id'] == 3)
                                        <span class="m-badge m-badge--warning m-badge--wide"
                                            style="width: 80%">{{ $item->status->status_name }}</span>
                                    @elseif ($item['ticket_status_id'] == 4)
                                        <span class="m-badge m-badge--primary m-badge--wide"
                                            style="width: 80%">{{ $item->status->status_name }}</span>
                                    @elseif ($item['ticket_status_id'] == 5)
                                        <span class="m-badge m-badge--danger m-badge--wide"
                                            style="width: 80%">{{ $item->status->status_name }}</span>
                                    @elseif ($item['ticket_status_id'] == 6)
                                        <span class="m-badge m-badge--primary m-badge--wide"
                                            style="width: 80%">{{ $item->status->status_name }}</span>
                                    @elseif ($item['ticket_status_id'] == 7)
                                        <span class="m-badge m-badge--metal m-badge--wide"
                                            style="width: 80%">{{ $item->status->status_name }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {{ $list->links('helpers.paging') }}
    </div>
</div>
