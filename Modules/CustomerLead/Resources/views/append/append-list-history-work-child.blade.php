@if(count($historyWork) != 0)
    <div class="col-12 mt-3 p-0">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                {{-- <th class="text-center">#</th>
                <th class="text-center">Loại công việc</th>
                <th class="text-center">Tiêu đề</th>
                <th class="text-center">Trạng thái</th>
                <th class="text-center">Người thực hiện</th>
                <th class="text-center">Ngày cập nhật</th>
                <th class="text-center">Ngày hết hạn</th> --}}
                <th class="text-center">@lang('STT')</th>
                <th class="text-center">@lang('Công việc')</th>
                <th class="text-center">@lang('Ngày bắt đầu')/@lang('Ngày hết hạn')</th>
                <th class="text-center">@lang('Người thực hiện')</th>
                <th class="text-center">@lang('Ngày tạo')/@lang('Ngày cập nhật')</th>
                <th class="text-center">@lang('Người tạo')/@lang('Người cập nhật')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($historyWork as $key => $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-left">
                        <div>[{{ $item['manage_work_code'] }}] {{ $item['manage_work_title'] }}</div>
                        @if($item['manage_status_id'] != 6  && in_array(\Auth::id(),[$item['processor_id'],$item['assignor_id']]))
                            <p onclick="Work.popupChangeStatus({{$item['manage_work_id']}})" class="m-0 cursor_pointer status_work_priority " style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                        @else
                            <p class="mb-0 status_work_priority m-0" style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                        @endif
                        <div>{{ $item['manage_type_work_name'] }}</div>
                    </td>
                    <td class="text-center">
                        {{ App\Helpers\Helper::formatDate($item['date_start']) }}<br>
                        {{ App\Helpers\Helper::formatDate($item['date_end']) }}
                    </td>
                    <td class="text-center">{{$item['staff_name']}}</td>
                    <td class="text-center">
                        {{ App\Helpers\Helper::formatDate($item['created_at']) }}<br>
                        {{ App\Helpers\Helper::formatDate($item['updated_at']) }}
                    </td>
                    <td class="text-center">{{$item['createdStaff_name']}}<br>{{$item['updatedStaff_name']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $historyWork->links('customer-lead::helpers.paging-work-history') }}
    </div>
@else
    <div class="col-12 mt-3 p-0">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                {{-- <th>#</th>
                <th>Loại công việc</th>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Tiến độ</th>
                <th>Người thực hiện</th>
                <th>Ngày cập nhật</th>
                <th>Ngày hết hạn</th> --}}
                <th class="text-center">@lang('STT')</th>
                <th class="text-center">@lang('Công việc')</th>
                <th class="text-center">@lang('Ngày bắt đầu')/@lang('Ngày hết hạn')</th>
                <th class="text-center">@lang('Người thực hiện')</th>
                <th class="text-center">@lang('Ngày tạo')/@lang('Ngày cập nhật')</th>
                <th class="text-center">@lang('Người tạo')/@lang('Người cập nhật')</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="9" class="text-center">@lang('Không có dữ liệu')</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif