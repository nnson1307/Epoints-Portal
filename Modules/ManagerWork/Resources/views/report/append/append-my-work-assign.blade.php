@foreach($list as $key => $item)
{{--    <div class="col-12" id="headingStatus{{$key}}">--}}
{{--        <h5 class="position-relative" data-toggle="collapse" data-target="#collapseStatus{{$key}}" aria-expanded="true" aria-controls="collapseStatus{{$key}}"><i class="far fa-calendar-alt"></i> {{$item['text_block']}} ({{count($item['list'])}})</h5>--}}
{{--    </div>--}}
   <div class="row">
       <div class="col-12" style="display: flex;align-items: center;" id="headingStatus{{$key}}">
           <div style="min-width:300px; float: left">

               <h6 style="font-size: 1.25rem;"  class="position-relative" data-toggle="collapse" data-target="#collapseStatus{{$key}}" aria-expanded="true" aria-controls="collapseStatus{{$key}}">
                   <i class="far fa-calendar-alt"></i> {{$item['text_block']}} ({{count($item['list'])}})
               </h6>
           </div>
           <div style="width:calc(100% - 150px); float: left; height: 100%;">
               <hr>
           </div>
       </div>
   </div>
    @if(count($item['list']) != 0)
        <div class="col-12 collapse {{$key == 0 ? 'show' : ''}}" id="collapseStatus{{$key}}" aria-labelledby="headingStatus{{$key}}" data-parent="#accordion1">
            <table class="table table-striped m-table m-table--head-bg-default list_priority_table">
                <thead>
                <tr>
{{--                    <th width="5%"></th>--}}
                    <th width="35%"><span class="cursor_pointer" onclick="MyWork.sortMyWorkAssign('sort_assign_{{$key}}','sort_assign_{{$key}}_manage_work_title',`{{isset($data['sort_assign']) ? $data['sort_assign'][$key]['manage_work_title'] : ''}}`)">{{__('Tiêu đề')}} <i class="fas fa-sort"></i></span></th>
                    <th width="15%" class="text-center">{{__('Người thực hiện')}}</th>
                    <th width="15%" class="text-center">{{$key == 0 ? __('Phê duyệt') : __('Trạng thái')}}</th>
                    <th width="15%" class="text-center"><span class="cursor_pointer" onclick="MyWork.sortMyWorkAssign('sort_assign_{{$key}}','sort_assign_{{$key}}_progress',`{{isset($data['sort_assign']) ? $data['sort_assign'][$key]['progress'] : ''}}`)">{{__('Tiến độ')}} <i class="fas fa-sort" ></i></span></th>
                    <th width="15%" class="text-center"><span class="cursor_pointer" onclick="MyWork.sortMyWorkAssign('sort_assign_{{$key}}','sort_assign_{{$key}}_date_end',`{{isset($data['sort_assign']) ? $data['sort_assign'][$key]['date_end'] : ''}}`)">{{__('Ngày hết hạn')}}  <i class="fas fa-sort"></i></span></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <input type="hidden" class="sort_assign_{{$key}}" name="sort_assign[{{$key}}][manage_work_title]" id="sort_assign_{{$key}}_manage_work_title" value="{{isset($data['sort_assign']) ? $data['sort_assign'][$key]['manage_work_title'] : ''}}">
                <input type="hidden" class="sort_assign_{{$key}}" name="sort_assign[{{$key}}][progress]" id="sort_assign_{{$key}}_progress" value="{{isset($data['sort_assign']) ? $data['sort_assign'][$key]['progress'] : ''}}">
                <input type="hidden" class="sort_assign_{{$key}}" name="sort_assign[{{$key}}][date_end]" id="sort_assign_{{$key}}_date_end" value="{{isset($data['sort_assign']) ? $data['sort_assign'][$key]['date_end'] : ''}}">
                @foreach($item['list'] as $keyList => $itemList)
                    @if($keyList < 10)
                        <tr>
                            {{--                        <td class="text-center"><i class="la la-edit"></i></td>--}}
                            <td>
                                <a href="{{route('manager-work.detail',['id'=> $itemList['manage_work_id']])}}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($itemList['manage_project_name'] != null)
                                                <p class="title_project mb-2">{{$itemList['manage_project_name']}}</p>
                                            @endif
                                            <p class="title_work mb-0"><strong>{{$itemList['manage_work_title']}}</strong></p>
                                        </div>
                                        {{--                                <div>--}}
                                        {{--                                    <i class="far fa-comment"></i>--}}
                                        {{--                                </div>--}}
                                    </div>
                                </a>
                            </td>
                            <td class="">
                                <img tabindex="-1" style="width: 25px;height: 25px;border-radius: 50%;margin-right: 5px" src="{{$itemList['processor_avatar']}}"
                                     onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($itemList['processor_name']),0,1))}}';">
                                {{$itemList['processor_name']}}
                            </td>
                            <td class="text-center">
                                @if($key == 0)
                                    <a href="javascript:void(0)" class="w-auto d-inline-block block_refuse_my_work" onclick="MyWork.workApprove({{$itemList['manage_work_id']}},'reject')">
                                        <i class="fas fa-ban"></i> {{__('Từ chối')}}
                                    </a>
                                    <a href="javascript:void(0)" class="w-auto d-inline-block block_approve_my_work" onclick="MyWork.workApprove({{$itemList['manage_work_id']}},'approve')">
                                        <i class="fas fa-check"></i> {{__('Duyệt')}}
                                    </a>
                                @else
                                    <p
                                            @if($itemList['manage_status_id'] != 6  && in_array(\Auth::id(),[$itemList['processor_id'],$itemList['assignor_id']]))
                                            onclick="StaffOverview.popupChangeStatus('{{$itemList['manage_work_id']}}')"
                                            style="cursor: pointer; background-color:{{$itemList['manage_color_code']}}"
                                            @endif
                                            class="mb-0 status_work_priority ">{{$itemList['manage_status_name']}}
                                    </p>
                                @endif
                            </td>
                            <td class="text-center">
                                <div
                                        @if($itemList['manage_status_id'] != 6  && in_array(\Auth::id(),[$itemList['processor_id'],$itemList['assignor_id']]))
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
                                <span
                                        @if($itemList['manage_status_id'] != 6  && in_array(\Auth::id(),[$itemList['processor_id'],$itemList['assignor_id']]))
                                         onclick="StaffOverview.popupChangeDate('{{$itemList['manage_work_id']}}')"
                                         style="cursor: pointer"
                                        @endif
                                >
                                    {{\Carbon\Carbon::parse($itemList['date_end'])->format('d/m/Y')}}
                                </span>

                            </td>
                            <td>
                                @if($itemList['processor_id'] != \Illuminate\Support\Facades\Auth::id())
                                    <button type="button" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"  onclick="StaffOverview.remindWorkOverdue('status','{{$itemList['manage_work_id']}}')"><i class="fas fa-plus-circle"></i> {{__('Nhắc nhở')}}</button>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            @if(count($item['list']) > 10)
                <a href="{{route('manager-work',[
                    'date_end' => isset($item['date_end']) ? $item['date_end'] : '',
                    'assign_by' => isset($item['assign_by']) ? $item['assign_by'] : '',
                    'manage_status_id' => isset($item['manage_status_id']) && count($item['manage_status_id']) != 0 ? $item['manage_status_id'] : '',
                    'type-page' => isset($item['type-page']) ? $item['type-page'] : ''
                ])}}">{{__('Xem thêm')}}</a>
            @endif
        </div>
    @endif
@endforeach