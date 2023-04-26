<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">{{__('Loại công việc')}} </th>
            <th class="text-center">{{__('Tiêu đề')}} </th>
            <th class="text-center">{{__('Loại thẻ')}} </th>
            <th class="text-center">{{__('Trạng thái')}} </th>
            <th class="text-center">{{__('Tiến độ')}} </th>
            <th class="text-center">{{__('Người thực hiện')}} </th>
            <th class="text-center">{{__('Ngày bắt đầu')}} </th>
            <th class="text-center">{{__('Ngày hết hạn')}} </th>
            <th class="text-center">{{__('Ngày hoàn thành')}} </th>
            <th class="text-center">{{__('Hoàn thành đúng tiến độ')}} </th>
            <th class="text-center">{{__('Hoàn thành quá hạn')}} </th>
            <th class="text-center">{{__('Chưa hoàn thành')}} </th>
            <th class="text-center">{{__('Quá hạn')}} </th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            <?php $i = 1; ?>
            @foreach($list as $key => $item)
                <tr>
                    <td>{{($list->currentPage() - 1) * $list->perPage() + $i }}</td>
                    <td class="text-center">
                        @if($item['manage_type_work_icon'] != '')
                            <img src="{{$item['manage_type_work_icon']}}" style="width:20px;height:20px">
                        @else
                            <img src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}" style="width:20px;height:20px">
                        @endif
                    </td>
                    <td class="text-center"><a href="{{route('manager-work.detail',  $item['manage_work_id'])}}" >{{$item['manage_work_title']}}</a> </td>
                    <td class="text-center">{{$item['type_card_work'] == 'bonus' ? __('Thường') : 'KPI'}}</td>
                    <td class="text-center"><p class="mb-0 ml-0 status_work_priority " style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p></td>
                    <td class="text-center">
                        <div class="progress">
                            <div class="progress-bar bg-warning progress-bar-striped" style="width:{{$item['progress']}}%">{{$item['progress']}}%</div>
                        </div>
                    </td>
                    <td class="text-center">{{$item['processor_full_name']}}</td>
                    <td class="text-center">{{$item['date_start'] != '' ? \Carbon\Carbon::parse($item['date_start'])->format('d/m/Y H:i') : ''}}</td>
                    <td class="text-center">{{$item['date_end'] != '' ? \Carbon\Carbon::parse($item['date_end'])->format('d/m/Y H:i') : ''}}</td>
                    <td class="text-center">{{$item['date_finish'] != '' ? \Carbon\Carbon::parse($item['date_start'])->format('d/m/Y H:i') : ''}}</td>
                    <td class="text-center">
                        <label class="m-checkbox m-checkbox--air">
                            <input {{$item['work_completed_schedule'] == 1 ? 'checked' : ''}} disabled
                                   class="check-inventory-warning"
                                   type="checkbox">
                            <span></span>
                        </label>
                    </td>
                    <td class="text-center">
                        <label class="m-checkbox m-checkbox--air">
                            <input {{$item['work_completed_overdue'] == 1 ? 'checked' : ''}} disabled
                                   class="check-inventory-warning"
                                   type="checkbox">
                            <span></span>
                        </label>
                    </td>
                    <td class="text-center">
                        <label class="m-checkbox m-checkbox--air">
                            <input {{$item['work_not_completed'] == 1 ? 'checked' : ''}} disabled
                                   class="check-inventory-warning"
                                   type="checkbox">
                            <span></span>
                        </label>
                    </td>
                    <td class="text-center">
                        <label class="m-checkbox m-checkbox--air">
                            <input {{$item['work_overdue'] == 1 ? 'checked' : ''}} disabled
                                   class="check-inventory-warning"
                                   type="checkbox">
                            <span></span>
                        </label>
                    </td>
                </tr>
                <?php $i++; ?>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}