<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th>#</th>
            <th>{{__('NHÂN VIÊN')}} <button class="sort" type="button" onclick="Report.sort('full_name','{{$filter['sort_key'] != 'full_name' ? 'DESC' : ($filter['sort_type'] == 'ASC' ? 'DESC' : 'ASC') }}')"><i class="fas fa-sort"></i></button> </th>
            <th class="text-center">{{__('TỔNG CÔNG VIỆC ĐƯỢC GIAO')}} <button class="sort" type="button" onclick="Report.sort('total_process','{{$filter['sort_key'] != 'total_process' ? 'DESC' : ($filter['sort_type'] == 'ASC' ? 'DESC' : 'ASC') }}')"><i class="fas fa-sort"></i></button></th>
            <th class="text-center">{{__('TỔNG THỜI GIAN LÀM VIỆC (Phút)')}} <button class="sort" type="button"><i class="fas fa-sort" onclick="Report.sort('total_time_work','{{$filter['sort_key'] != 'total_time_work' ? 'DESC' : ($filter['sort_type'] == 'ASC' ? 'DESC' : 'ASC') }}')"></i></button> </th>
            <th class="text-center">{{__('HOÀN THÀNH ĐÚNG TIẾN ĐỘ')}} <button class="sort" type="button" onclick="Report.sort('total_completed_schedule','{{$filter['sort_key'] != 'total_completed_schedule' ? 'DESC' : ($filter['sort_type'] == 'ASC' ? 'DESC' : 'ASC') }}')"><i class="fas fa-sort"></i></button> </th>
            <th class="text-center">{{__('HOÀN THÀNH QUÁ HẠN')}} <button class="sort" type="button" onclick="Report.sort('total_completed_overdue','{{$filter['sort_key'] != 'total_completed_overdue' ? 'DESC' : ($filter['sort_type'] == 'ASC' ? 'DESC' : 'ASC') }}')"><i class="fas fa-sort"></i></button> </th>
{{--            <th class="text-center">{{__('CHƯA HOÀN THÀNH')}} <button class="sort" type="button" onclick="Report.sort('total_not_completed','{{$filter['sort_key'] != 'total_not_completed' ? 'DESC' : ($filter['sort_type'] == 'ASC' ? 'DESC' : 'ASC') }}')"><i class="fas fa-sort"></i></button> </th>--}}
            @foreach($listStatusActive as $item)
                <th class="text-center">{{$item['manage_status_name']}} </th>
            @endforeach
            <th class="text-center">{{__('QUÁ HẠN')}} <button class="sort" value="DESC" type="button" onclick="Report.sort('total_overdue','{{$filter['sort_key'] != 'total_overdue' ? 'DESC' : ($filter['sort_type'] == 'ASC' ? 'DESC' : 'ASC') }}')"><i class="fas fa-sort"></i></button> </th>
        </tr>
        </thead>
        <tbody>
        @if(isset($list) && count($list) != 0)
            <?php $i = 1; ?>
            @foreach($list as $key => $item)
                <tr>
                    <td>{{($list->currentPage() - 1) * $list->perPage() + $i }}</td>
                    <td><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id']])}}">{{$item['full_name']}}</a> </td>
                    <td class="text-center"><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id']])}}">{{$item['total_process']}}</a></td>
                    <td class="text-center"><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id']])}}">{{$item['total_time_work']}}</a></td>
                    <td class="text-center"><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id'],'type_work' => 'finish'])}}">{{$item['total_completed_schedule']}}</a></td>
                    <td class="text-center"><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id'],'type_work' => 'finish_overdue'])}}">{{$item['total_completed_overdue']}}</a></td>
{{--                    <td class="text-center"><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id'],'type_work' => 'unfinish'])}}">{{$item['total_not_completed']}}</a></td>--}}
                    @foreach($listStatusActive as $itemStatus)
                        <td class="text-center"><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id'],'manage_status_id' => $itemStatus['manage_status_id']])}}">{{isset($listStatus[$item['staff_id']]) && isset($listStatus[$item['staff_id']][$itemStatus['manage_status_id']]) ? count($listStatus[$item['staff_id']][$itemStatus['manage_status_id']]) : 0}} </a></td>
                    @endforeach
                    <td class="text-center"><a href="{{route('manager-work.report.get-list-work',['staff_id' => $item['staff_id'],'type_work' => 'overdue'])}}">{{$item['total_overdue']}}</a></td>
                </tr>
                <?php $i++; ?>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}