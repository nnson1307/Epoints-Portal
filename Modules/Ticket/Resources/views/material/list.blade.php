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
                            'id' => $item->ticket_request_material_id,
                            'count' => isset($page) ? ($page-1)*10 + $key+1 :$key+1,
                            'ticket_request_material_code' => $item->ticket_request_material_code,
                            'ticket_id' => isset($item->ticketCode->ticket_code) ? $item->ticketCode->ticket_code : '-',
                            'proposer_by' => isset($item->proposer->full_name) ? $item->proposer->full_name : '-',
                            'proposer_date' => \Carbon\Carbon::parse($item->proposer_date)->format('d/m/Y H:i'),
                            'approved_by' => isset($item->approved->full_name) ? $item->approved->full_name : '-',
                            'approved_date' => $item->approved_by != '' ?\Carbon\Carbon::parse($item->approved_date)->format('d/m/Y H:i') :'',
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
                                $params['link'] =  'javascript:void(0);';
                                $column['attribute']['data-id'] = $item->ticket_request_material_id;
                            }
                            $params['data'] =  $data_column;
                            $params['column'] =  $column;
                            @endphp
                       <td class="{{$column['class']}}">
                            @if ($column['type'] == 'function')
                                @if ($item->status == 'new')
                                    <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                        href="javascript:void(0);" onclick="Material.edit('{{$item->ticket_request_material_id}}')">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);"  onclick="Material.remove(this, '{{ $item->ticket_request_material_id }}')"
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
