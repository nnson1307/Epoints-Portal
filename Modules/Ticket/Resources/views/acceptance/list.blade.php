<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table-config">
        <thead class="bg">
        <tr>
            @foreach ($showColumn as $column)
                @if($column['active'] == 1)
                    <th class="tr_thead_list{{ $column['class'] != '' ? ' '.$column['class'] :'' }}">{{$column['name']}}</th>
                @endif
            @endforeach
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    @php 
                        $parse_column = [
                            'id' => $item->ticket_acceptance_id,
                            'count' => isset($page) ? ($page-1)*10 + $key+1 :$key+1,
                            'ticket_acceptance_id' => $item->ticket_acceptance_id,
                            'ticket_acceptance_code' => $item->ticket_acceptance_code,
                            'ticket_code' => $item->ticket_code,
                            'title' => $item->title,
                            'sign_by' => $item->sign_by,
                            'customer_name' => $item->customer_name,
                            'created_name' => $item->created_name,
                            'sign_date' => $item->sign_date != '' && $item->sign_date != null && $item->sign_date != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->sign_date)->format('d/m/Y H:i') : '',
                            'updated_at' => \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i'),
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
                           @if($column['view_detail'] == 1)
                               <a class="ss--text-black" href="{{route('ticket.acceptance.detail',['id' => $item['ticket_acceptance_id']])}}">
                                   @include('ticket::column_config.'.$column['type'], $params)
                               </a>
                           @else
                               @include('ticket::column_config.'.$column['type'], $params)
                           @endif
                       </td>
                        @endif
                    @endforeach
                    @if($item['status'] == 'new')
                        <td>
                            <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               href="{{route('ticket.acceptance.edit',['ticketid' => $item['ticket_acceptance_id']])}}" >
                                <i class="la la-edit"></i>
                            </a>
                        </td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}
