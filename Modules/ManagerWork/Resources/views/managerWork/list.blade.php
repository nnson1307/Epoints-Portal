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
                        $star = isset($item->rating->point)?$item->rating->point:0;
                        $star_str = '';
                        for ($i = 1;$i <= 5;$i++){
                            if ($star >= $i){
                                $star_str  .= '<i class="fa fa-star text-warning" aria-hidden="true"></i>';
                            }else{
                                $star_str  .= '<i class="fa fa-star" aria-hidden="true"></i>';
                            }
                        }

                            $createObjectType = "";

                            switch ($item->create_object_type) {
                                case 'ticket';
                                $createObjectType = "Ticket";
                                break;
                                case 'shift';
                                $createObjectType = __('Ca làm việc');
                                break;
                                default:
                                    $createObjectType = __('Trực tiếp');
                            }

                            $parse_column = [
                                'id' => $item->manage_work_id,
                                'count' => isset($page) ? ($page-1)*10 + $key+1 :$key+1,
                                'manage_type_work_icon' => $item['manage_type_work_icon'] != '' ? ($item['manage_type_work_icon']) : asset('static/backend/images/service-card/default/hinhanh-default3.png'),
                                'manage_work_title' => $item->manage_work_title,
                                'manage_status_id' => $item->manage_status_id,
                                'manage_status_name' => $item->manage_status_name,
                                'manage_work_code' => $item->manage_work_code,
                                'manage_color_code' => $item->manage_color_code,
                                'manage_project_name' => $item->manage_project_name,
                                'manage_work_parent_id' => $item->manage_work_parent_id,
                                'manage_work_parent_code' => $item->manage_work_parent_code,
                                'priority' => $item->priority == 1 ? __('Cao') : ($item->priority == 2 ? __('Bình thường') : ($item->priority == 3 ? __('Thấp') : '')),
                                'progress' => $item->progress,
                                'processor_id' => (isset($item->processor->full_name))? $item->processor->full_name:'',
                                'date_estimated' => isset($item->date_estimated) && $item->date_estimated != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->date_estimated)->format('d/m/Y H:i') : '',
                                'date_start' => $item->date_start != null ? \Carbon\Carbon::parse($item->date_start)->format('d/m/Y H:i') : \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i'),
                                'date_end' => \Carbon\Carbon::parse($item->date_end)->format('d/m/Y H:i'),
                                'created_by' => $item->created_by,
                                'approve_name' => $item->approve_name,
                                'updated_name' => $item->updated_name,
                                'created_name' => $item->created_name,
                                'is_edit' => $item->is_edit,
                                'is_deleted' => $item->is_deleted,
                                'type_card_work' => $item->type_card_work == 'bonus' ? __('Thường') : 'KPI',
                                'created_at' => isset($item->created_at) && $item->created_at != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '',
                                'updated_at' => isset($item->updated_at) && $item->updated_at != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') : '',
                                'date_finish' =>  isset($item->date_finish) && $item->date_finish != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($item->date_finish)->format('d/m/Y H:i') : '',
                                'customer_name' => $item->customer_name,
                                'time' => $item->time.' '.($item->time_type == 'd' ? __('ngày') : __('giờ')),
                                'manage_work_support_id' => isset($listSupport[$item['manage_work_id']]) ? implode(', ',$listSupport[$item['manage_work_id']]) : '',
                                'tag' => isset($listTag[$item['manage_work_id']]) ? implode(', ',$listTag[$item['manage_work_id']]) : '',
                                'manage_work_parent_name' => isset($item->manage_work_parent_name) && $item->manage_work_parent_name != 0 ? __('Công việc con') : __('Công việc cha'),
                                'create_object_type' => $createObjectType
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
                                    if ($column['column_name'] == 'manage_work_parent_code' && isset($item['manage_parent_work_id'])){
                                        $params['link'] =  route('manager-work.detail',  $item['manage_parent_work_id']);
                                    } else {
                                        $params['link'] =  route('manager-work.detail', $item['manage_work_id']);
                                    }
                                }

                                $params['data'] =  $data_column;
                                $params['column'] =  $column;
                            @endphp
                            <td class="{{$column['class']}}"
                            @if(isset($column['attribute']) && is_array($column['attribute']))
                                @foreach ($column['attribute'] as $attr_key => $attr_value)
                                    {{' '.$attr_key.'='.$attr_value.''}}
                                        @endforeach
                                    @endif>
                                @if ($column['type'] == 'function')
                                    @if(\Helper::checkIsAdmin())
                                        <button onclick="WorkChild.showPopup('{{ $item['manage_work_id'] }}')" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                                            <i class="la la-edit"></i>
                                        </button>
                                        <button onclick="ManagerWork.remove(this, '{{ $item['manage_work_id'] }}','{{ $item['total_child_job'] }}')"
                                                 class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                 title="{{__('Xóa')}}"><i class="la la-trash"></i>
                                        </button>
                                    @else
                                        @if(in_array(\Auth::id(),[$item['processor_id'],$item['assignor_id']]))
                                            @if($item['ticket_status_id'] != 4)
                                                {{--                                        <button onclick="ManagerWork.edit('{{ $item['manage_work_id'] }}')" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">--}}
                                                @if($item['is_edit'] == 1)
                                                    <button onclick="WorkChild.showPopup('{{ $item['manage_work_id'] }}')" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                                                        <i class="la la-edit"></i>
                                                    </button>
    {{--                                            @else--}}
    {{--                                                <button disabled class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">--}}
    {{--                                                    <i class="la la-edit"></i>--}}
    {{--                                                </button>--}}
                                                @endif
    {{--                                        @else--}}
    {{--                                            <button disabled class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">--}}
    {{--                                                <i class="la la-edit"></i>--}}
    {{--                                            </button>--}}
                                            @endif
    {{--                                        @if($item['ticket_status_id'] != 1)--}}
                                                @if($item['is_deleted'] == 1)
                                                    <button onclick="ManagerWork.remove(this, '{{ $item['manage_work_id'] }}','{{ $item['total_child_job'] }}')"
                                                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                            title="{{__('Xóa')}}"><i class="la la-trash"></i>
                                                    </button>
    {{--                                            @else--}}
    {{--                                                    <button disabled--}}
    {{--                                                            class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"--}}
    {{--                                                            title="{{__('Xóa')}}"><i class="la la-trash"></i>--}}
    {{--                                                    </button>--}}
                                                @endif
    {{--                                        @endif--}}
    {{--                                    @else--}}
    {{--                                        <button disabled class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">--}}
    {{--                                            <i class="la la-edit"></i>--}}
    {{--                                        </button>--}}
    {{--                                        <button disabled--}}
    {{--                                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"--}}
    {{--                                                title="{{__('Xóa')}}"><i class="la la-trash"></i>--}}
    {{--                                        </button>--}}

                                    @endif
                                @endif
                                @elseif($column['type'] == 'status_work')
                                    <p class="mb-0 ml-0 status_work_priority " style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                                @else
                                    @include('manager-work::column_config.'.$column['type'], $params)
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
