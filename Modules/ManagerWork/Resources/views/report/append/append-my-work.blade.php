@foreach($list as $key => $item)
    <div class="col-12">
        <h5 class="position-relative"><i class="far fa-calendar-alt"></i><span class="title-line-page"> {{$item['text_block']}} ({{count($item['list'])}})</span></h5>
    </div>
    @if(count($item['list']) != 0)
        <div class="col-12">
            <table class="table table-striped m-table m-table--head-bg-default list_priority_table">
                <thead>
                <tr>
{{--                    <th width="5%"></th>--}}
                    <th width="35%"><span class="cursor_pointer" onclick="MyWork.sortMyWork('sort_{{$key}}','sort_{{$key}}_manage_work_title',`{{isset($data['sort']) ? $data['sort'][$key]['manage_work_title'] : ''}}`)">{{__('Tiêu đề')}} <i class="fas fa-sort"></i></span></th>
                    <th width="15%" class="text-center">{{_('Người thực hiện')}}</th>
                    <th width="15%" class="text-center">{{_('Trạng thái')}}</th>
                    <th width="15%" class="text-center"><span class="cursor_pointer" onclick="MyWork.sortMyWork('sort_{{$key}}','sort_{{$key}}_progress',`{{isset($data['sort']) ? $data['sort'][$key]['progress'] : ''}}`)">{{__('Tiến độ')}} <i class="fas fa-sort"></i></span></th>
                    <th width="15%" class="text-center"><span class="cursor_pointer" onclick="MyWork.sortMyWork('sort_{{$key}}','sort_{{$key}}_date_end',`{{isset($data['sort']) ? $data['sort'][$key]['date_end'] : ''}}`)">{{__('Ngày hết hạn')}} <i class="fas fa-sort"></i></span></th>
                </tr>
                </thead>
                <tbody>
                <input type="hidden" class="sort_{{$key}}" name="sort[{{$key}}][manage_work_title]" id="sort_{{$key}}_manage_work_title" value="{{isset($data['sort']) ? $data['sort'][$key]['manage_work_title'] : ''}}">
                <input type="hidden" class="sort_{{$key}}" name="sort[{{$key}}][progress]" id="sort_{{$key}}_progress" value="{{isset($data['sort']) ? $data['sort'][$key]['progress'] : ''}}">
                <input type="hidden" class="sort_{{$key}}" name="sort[{{$key}}][date_end]" id="sort_{{$key}}_date_end" value="{{isset($data['sort']) ? $data['sort'][$key]['date_end'] : ''}}">
                @foreach($item['list'] as $itemList)
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
                            <p
                                    @if($itemList['manage_status_id'] != 6)
                                    onclick="StaffOverview.popupChangeStatus('{{$itemList['manage_work_id']}}')"
                                    style="cursor: pointer;background-color:{{$itemList['manage_color_code']}}"
                                    @endif

                                    class="mb-0 status_work_priority " style="background-color:{{$itemList['manage_color_code']}}">{{$itemList['manage_status_name']}}</p>
                        </td>
                        <td class="text-center">
                            <div
                                    @if($itemList['manage_status_id'] != 6)
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
                            @if($itemList['manage_status_id'] != 6)
                                onclick="StaffOverview.popupChangeDate('{{$itemList['manage_work_id']}}')"
                                style="cursor: pointer"
                            @endif
                            >
                            {{\Carbon\Carbon::parse($itemList['date_end'])->format('d/m/Y')}}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endforeach