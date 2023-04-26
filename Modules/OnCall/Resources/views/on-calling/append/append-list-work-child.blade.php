@if(count($listWork) != 0)
    <div class="col-12 mt-4">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Loại công việc</th>
                <th class="text-center">Tiêu đề</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Người thực hiện</th>
                <th class="text-center">Ngày cập nhật</th>
                <th class="text-center">Ngày hết hạn</th>
            </tr>
            </thead>
            <tbody>
            @foreach($listWork as $key => $item)
                <tr>
                    <td class="text-center">{{($listWork->currentPage() - 1)*$listWork->perPage() + $key+1 }}</td>
                    <td class="text-center">{{$item['manage_type_work_icon'] == null ? $item['manage_type_work_name'] : $item['manage_type_work_icon']}}</td>
                    <td class="text-center">
                        <a class="m-link" target="_blank" style="color:#464646" href="{{route('manager-work.detail',['id' => $item['manage_work_id']])}}">
                            {{$item['manage_work_title']}}
                        </a>
                    </td>
                    <td class="text-center">
                        @if($item['manage_status_id'] != 6  && in_array(\Auth::id(),[$item['processor_id'],$item['assignor_id']]))
                            <p class="mb-0 cursor_pointer status_work_priority " style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                        @else
                            <p class="mb-0 status_work_priority " style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                        @endif
                    </td>

                    <td class="text-center">{{$item['staff_name']}}</td>
                    <td class="text-center">{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y')}}</td>
                    <td class="text-center">{{\Carbon\Carbon::parse($item['date_end'])->format('d/m/Y')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $listWork->links('on-call::on-calling.helpers.paging-work') }}
    </div>
@else
    <div class="col-12 mt-5">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Hành động</th>
                <th>Loại công việc</th>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Tiến độ</th>
                <th>Người thực hiện</th>
                <th>Ngày cập nhật</th>
                <th>Ngày hết hạn</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="9" class="text-center">Không có dữ liệu</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif