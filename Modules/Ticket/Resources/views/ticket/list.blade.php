<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table-config">
        <thead class="bg">
        <tr>
            @foreach ($showColumn as $column)
                @if($column['active'] == 1)
                    <th class="tr_thead_list{{ $column['class'] != '' ? ' '.$column['class'] :'' }}">{{$column['name']}}</th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(isset($list))
            @foreach ($list as $key => $item)
                {{-- class="{{ ($item->is_overtime == 1) ? 'bg-danger text-light':'' }}" --}}
                <tr>
                    @php
                        $star = isset($item->rating->point)?$item->rating->point:0;
                        $star_str = '';
                        for ($i = 1;$i <= 5;$i++){
                            if ($star >= $i){
                                $star_str  .= '<i class="fa fa-star text-warning" aria-hidden="true"></i>';
                            }else{
                                $star_str  .= '<i class="fa fa-star" aria-hidden="true"></i>';
                            }
                        }
                            $parse_column = [
                                'id' => $item->ticket_id,
                                'count' => isset($page) ? ($page-1)*10 + $key+1 :$key+1,
                                'ticket_code' => $item->ticket_code,
                                'title' => $item->title,
                                'priority' => getPriority($item->priority),
                                'ticket_type' => isset($item->issue_group->name)?$item->issue_group->name:'',
                                'ticket_issue_id' => (isset($item->issue->name)) ? $item->issue->name : '',
                                'issule_level' => levelIssue($item['issule_level']),
                                'queue_process_id' => (isset($item->queue->queue_name))? $item->queue->queue_name : '',
                                'operate_by' => isset($item->operate->full_name)? $item->operate->full_name:'',
                                'ticket_status_id' => $item->ticket_status_id,
                                'date_issue' => \Carbon\Carbon::parse($item->date_issue)->format('d/m/Y H:i'),
                                'date_expected' => \Carbon\Carbon::parse($item->date_expected)->format('d/m/Y H:i'),
                                'star' => $star_str,
                                'created_by' => $item->created_by,
                            ];
                    @endphp
                    @foreach ($showColumn as $column)
                        @if($column['active'] == 1)
                            @php
                                if(!isset($column['type'])){
                                    $column['type'] = 'null';
                                }
                                $data_column = '-';
                                if(isset($column['column_name']) && isset($parse_column[$column['column_name']])){
                                    $data_column = $parse_column[$column['column_name']];
                                }
                                if(isset($column['type']) && $column['type'] == 'link'){
                                    $params['link'] =  route('ticket.detail',  $item['ticket_id']);
                                }
                                $params['data'] =  $data_column;
                                $params['column'] =  $column;
                            @endphp
                            <td class="{{$column['class']}}">
                                @if ($column['type'] == 'function')
                                    @if($item['is_edit'] == 1)
                                        <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                           href="{{route('ticket.edit', $item['ticket_id'])}}">
                                            <i class="la la-edit"></i>
                                        </a>
                                    @endif

                                    @if ($isAcceptTicketSameQueue == 1 && $item['process_by'] == null && in_array($item['queue_process_id'], $arrQueueStaff) && $infoStaffQueue['ticket_role_queue_id'] == 1 && $item['is_edit'] == 0)
                                        <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                           href="javascript:void(0)"
                                           onclick="ticket.acceptTicket('{{$item['ticket_id']}}', 'list')">
                                            <i class="la la-check-circle"></i>
                                        </a>
                                    @endif
                                @else
                                    @include('ticket::column_config.'.$column['type'], $params)
                                @endif
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
