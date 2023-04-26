@foreach($list as $key => $item)

    <div class="row">
        <div class="col-12" id="heading{{$key}}" style="display: flex;align-items: center;">
            <div style="min-width:160px; float: left">
                <h6 style="font-size: 1.25rem;" class="position-relative" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                    <i class="far fa-calendar-alt"></i> {{$item['text_block']}} ({{count($item['list'])}})
                </h6>
            </div>
            <div style="width:calc(100% - 160px); float: left; height: 100%;">
                <hr>
            </div>
        </div>

    </div>
    @if(count($item['list']) != 0)
        <div class="col-12 collapse {{$key == 0 ? 'show' : ''}}" id="collapse{{$key}}" aria-labelledby="heading{{$key}}" data-parent="#accordion">
            <table class="table table-striped m-table m-table--head-bg-default list_priority_table">
                <thead>
                <tr>
                    <th width="35%">{{__('Tiêu đề')}}</th>
                    <th width="15%" class="text-center">{{__('Người thực hiện')}}</th>
                    <th width="15%" class="text-center">{{__('Trạng thái')}}</th>
                    <th width="15%" class="text-center">{{__('Tiến độ')}}</th>
                    <th width="15%" class="text-center">{{__('Ngày hết hạn')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($item['list'] as $keyList => $itemList)
                    @if($keyList < 10)
                        <tr>
                            <td>
                                <a href="{{route('manager-work.detail',['id'=> $itemList['manage_work_id']])}}">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div>
                                            @if($itemList['manage_project_name'] != null)
                                                <a href="{{route('manager-work',['manage_project_id' => $itemList['manage_project_id']])}}" ><p class="title_project mb-2">{{$itemList['manage_project_name']}}</p></a>
                                            @endif
                                            <p class="title_work mb-0"><strong>{{$itemList['manage_work_title']}}</strong></p>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td class="">
                                <img tabindex="-1" style="width: 25px;height: 25px;border-radius: 50%; margin-right: 5px" src="{{$itemList['processor_avatar']}}"
                                     onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($itemList['processor_name']),0,1))}}';">
                                {{$itemList['processor_name']}}
                            </td>
                            <td class="text-center">
                                <p
                                        @if($itemList['manage_status_id'] != 6  && in_array(\Auth::id(),[$itemList['processor_id'],$itemList['assignor_id']]))
                                        onclick="StaffOverview.popupChangeStatus('{{$itemList['manage_work_id']}}')" style="background-color:{{$itemList['manage_color_code']}}"
                                        style="cursor: pointer"
                                        @endif
                                        class="mb-0 status_work_priority " style="background-color:{{$itemList['manage_color_code']}}">{{$itemList['manage_status_name']}}</p>
                            </td>
                            <td class="text-center">
                                <div
                                        @if($itemList['is_parent'] == 0 && $itemList['manage_status_id'] != 6  && $itemList['is_edit'] == 1 && in_array(\Auth::id(),[$itemList['processor_id'],$itemList['assignor_id']]))
                                        onclick="StaffOverview.popupChangeProcess('{{$itemList['manage_work_id']}}')"
                                        style="cursor: pointer"
                                        @endif

                                        class="w-75 d-inline-block">
                                    <div class="progress progress-lg ">
                                        <div class="progress-bar kt-bg-warning" role="progressbar" style="width: {{$itemList['progress']}}%;background: #FFC000" aria-valuenow="{{$itemList['progress']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <span class="d-inline-block">{{$itemList['progress'] == null || $itemList['progress'] == '' ? 0 : $itemList['progress']}}%</span>
                            </td>
                            <td class="text-center">
                                @if($itemList['date_end'] != '0000-00-00 00:00:00')
                                    <span
                                            @if($itemList['manage_status_id'] != 6 && $itemList['is_edit'] == 1 && in_array(\Auth::id(),[$itemList['processor_id'],$itemList['assignor_id']]))
                                            onclick="StaffOverview.popupChangeDate('{{$itemList['manage_work_id']}}')"
                                            style="cursor: pointer"
                                        @endif
                                >
                                    {{\Carbon\Carbon::parse($itemList['date_end'])->format('d/m/Y')}}
                                </span>
                                @else
                                    N/A
                                @endif

                            </td>
                            <td>
                                @if($itemList['processor_id'] != \Illuminate\Support\Facades\Auth::id())
                                    @if((isset($item['type']) && $item['type'] == 'staff-overview1') || (isset($item['type-search']) && $item['type-search'] == 'overdue'))
                                        <button type="button" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"  onclick="StaffOverview.remindWorkOverdue('{{$item['type-search']}}','{{$itemList['manage_work_id']}}','{{$item['text_block']}}')"><i class="fas fa-plus-circle"></i> {{__('Nhắc nhở')}}</button>
                                    @else
                                        <button type="button" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"  onclick="StaffOverview.remindWorkOverdue('not_overdue','{{$itemList['manage_work_id']}}','{{$item['text_block']}}')"><i class="fas fa-plus-circle"></i> {{__('Nhắc nhở')}}</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            @if(count($item['list']) > 10)
                <a href="{{route('manager-work',[
                    'date_start' => isset($item['date_start']) ? $item['date_start'] : '',
                    'date_end' => isset($item['date_end']) ? $item['date_end'] : '',
                    'assign_by' => isset($item['assign_by']) ? $item['assign_by'] : '',
                    'manage_status_id' => isset($item['manage_status_id']) && count($item['manage_status_id']) != 0 ? $item['manage_status_id'] : '',
                    'department_id' => isset($item['chart_department_id']) ? $item['chart_department_id'] : '',
                    'manage_project_id' => isset($item['chart_manage_project_id']) ? $item['chart_manage_project_id'] : '',
                    'type-search' => isset($item['type-search']) ? $item['type-search'] : '',
                    'type-page' => isset($item['type-page']) ? $item['type-page'] : ''
                ])}}">{{__('Xem thêm')}}</a>
            @endif
        </div>

    @endif
@endforeach