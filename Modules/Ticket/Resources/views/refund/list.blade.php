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
                <tr>
                    @php 
                        $parse_column = [
                            'id' => $item->ticket_refund_id,
                            'count' => isset($page) ? ($page-1)*10 + $key+1 :$key+1,
                            'code' => $item->code,
                            'staff_id' => isset($item->refund_by_full_name) ? $item->refund_by_full_name : '-',
                            'approve_id' => isset($item->approve_by_full_name) ? $item->approve_by_full_name : '-',
                            'created_by' => isset($item->created_by_full_name) ? $item->created_by_full_name : '-',
                            'updated_by' => isset($item->updated_by_full_name) ? $item->updated_by_full_name : '-',
                            'created_at' => \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i'),
                            'updated_at' => $item->updated_by != '' ?\Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') :'',
                            'status' => $item->status,
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
                                $params['link'] =  route('ticket.refund.detail-view',$item->ticket_refund_id);
                                $column['attribute']['data-id'] = $item->ticket_refund_id;
                            }
                            $params['data'] =  $data_column;
                            $params['column'] =  $column;
                            @endphp
                       <td class="{{$column['class']}}">
                            @if ($column['type'] == 'function')
                                @if ($item->status == 'W' && $item->approve_id == \Auth::id())
                                    <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                        href="{{route('ticket.refund.approve-view',$item->ticket_refund_id)}}">
                                        <i class="fa fa-check-square" aria-hidden="true"></i>
                                    </a>
                                @endif
                                @if (in_array($item->status, ['D','WF']) && $item->created_by == \Auth::id())
                                <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                    href="{{route('ticket.refund.add-view',$item->ticket_refund_id)}}">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                                @else
                                <a href="javascript:void(0);"  class="d-none"></a>
                                @endif
                                @if (in_array($item->status, ['D']) && $item->created_by == \Auth::id())
                                <a href="{{ route('ticket.refund.remove',$item->ticket_refund_id) }}"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
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
