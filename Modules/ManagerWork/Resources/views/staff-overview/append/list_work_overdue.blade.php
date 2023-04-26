<div class="row">
    <div class="col-12 block_hot_1 text-center">
        <p class="mb-0"><strong>{{__('CÔNG VIỆC QUÁ HẠN (:totalWork)',['totalWork' => count($list_overdue)])}}</strong></p>
    </div>
    @if(count($list_overdue) != 0)
        <div class="col-12 block_hot_2">
            <form id="form_list_work_overdue">
                <div class="text-right mb-3">
                    <button type="button" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm"  onclick="StaffOverview.remindWorkOverdue()"><i class="fas fa-plus-circle"></i> {{__('Nhắc nhở')}}</button>
                </div>
                <table class="w-100">
                    <thead>
                    <tr>
                        <th width="80%"></th>
                        <th width="20%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list_overdue as $item)
                        <tr>
                            <td style="width:90%">
                                <div class="block_overdue_main mb-3">
                                    <div class="block_overdue_table_1">
                                        <p class="mb-0 title_overdue"><i class="far fa-clock"></i> {{__('Quá hạn')}} {{\Carbon\Carbon::parse($item['date_end'])->diffForHumans(\Carbon\Carbon::now())}}</p>
                                        <div class="block_overdue_table_content_header">
                                            <div class="w-74 d-inline-block div-title">
                                                <h5 class="font-weight-bold">{{$item['manage_work_title']}}</h5>
                                                @foreach($item['tags'] as $itemTag)
                                                    <span>{{$itemTag['manage_tag_name']}}</span>
                                                @endforeach
                                            </div>
                                            <div class="w-25 d-inline-block text-right">
                                                <svg class="circle-chart" viewbox="0 0 33.83098862 33.83098862" width="60" height="60" xmlns="http://www.w3.org/2000/svg">
                                                    <circle class="circle-chart__background" stroke="#FFD699" stroke-width="3" fill="none" cx="16.91549431" cy="16.91549431" r="15.61549431" />
                                                    <circle class="circle-chart__circle" stroke="#FF9A05" stroke-width="3" stroke-dasharray="{{isset($item['progress']) ? $item['progress'] : 0}},100" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.61549431" />
                                                    <g class="circle-chart__info">
                                                        <text class="circle-chart__percent" x="16.91549431" y="15.5" alignment-baseline="central" text-anchor="middle" font-size="8">{{isset($item['progress']) ? $item['progress'] : 0}}%</text>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="block_overdue_table_content_footer">
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="avatars_overview">
                                                        <a href="javascript:void(0)" class="avatars_overview__item">
                                                            <img class="avatar" src="{{$item['processor_avatar']}}" alt="" onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($item['processor_name']),0,1))}}';">
                                                        </a>
                                                        <a href="javascript:void(0)" class="avatars_overview__item">
                                                            <img class="avatar" src="{{$item['assignor_avatar']}}" alt="" onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($item['assignor_name']),0,1))}}';">
                                                        </a>
                                                        @if(count($item['list_staff']) != 0)
                                                            <?php $n = 0 ; ?>
                                                            @foreach($item['list_staff'] as $itemStaff)
                                                                <?php $n++ ; ?>
                                                                @if($n <= 4)
                                                                    <a href="javascript:void(0)" class="avatars_overview__item">
                                                                        <img class="avatar" src="{{$itemStaff['staff_avatar']}}" alt="" onerror="this.src='https://ui-avatars.com/api/?background=5867dd&color=FFFFFF&rounded=true&name={{strtoupper(substr(str_slug($itemStaff['staff_name']),0,1))}}';">
                                                                    </a>
                                                                @endif
                                                            @endforeach
                                                            @if(count($item['list_staff']) > 4)
                                                                <a href="javascript:void(0)" class="pt-1 pl-1">+{{count($item['list_staff']) - 4}}</a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-3 d-flex align-items-center">
                                                    <p class="mb-0"><i class="far fa-clock"></i> {{\Carbon\Carbon::parse($item['date_end'])->format('d/m/Y')}}</p>
                                                </div>
                                                <div class="col-2 d-flex align-items-center">
                                                    <p class="mb-0"><i class="far fa-comment"></i> {{$item['total_message']}}</p>
                                                </div>
                                                <div class="col-4 text-center">
                                                    <p class="status_work_priority mb-0" style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">
                                <label class="m-checkbox m-checkbox--state-success mt-0">
                                    <input type="checkbox" class="list_work_overdue" name="list_work_overdue[]" value="{{$item['manage_work_id']}}">
                                    <span></span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    @endif
</div>