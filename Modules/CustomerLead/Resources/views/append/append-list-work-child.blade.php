
@if(isset($listWork) && count($listWork) != 0)
    <div class="col-12 mt-3 p-0">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th class="text-center">STT</th>
                <th class="text-center">Công việc</th>
                <th class="text-center">Ngày bắt đầu/Ngày hết hạn</th>
                <th class="text-center">Người thực hiện</th>
                <th class="text-center">Ngày tạo/Ngày cập nhật</th>
                <th class="text-center">Người tạo/Người cập nhật</th>
            </tr>
            </thead>
            <tbody>
            @foreach($listWork as $key => $item)
                <tr>
                    <td class="text-center">{{($listWork->currentPage() - 1)*$listWork->perPage() + $key+1 }}</td>
                    <td class="text-left">
                        <div>[{{ $item['manage_work_code'] }}] {{ $item['manage_work_title'] }}</div>
                        @if($item['manage_status_id'] != 6  && in_array(\Auth::id(),[$item['processor_id'],$item['assignor_id']]))
                            <p onclick="Work.popupChangeStatus({{$item['manage_work_id']}})" class="m-0 cursor_pointer status_work_priority " style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                        @else
                            <p class="mb-0 status_work_priority m-0" style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p>
                        @endif
                        <div>{{ $item['manage_type_work_name'] }}</div>
                        {{-- @if(isset($deal_id) || (isset($item['manage_work_customer_type']) && $item['manage_work_customer_type'] == 'deal'))
                            <a href="javascript:void(0)" onclick="listDeal.popupDealCareEdit({{$item['customer_id']}},{{$item['manage_work_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Cập nhật"><i class="la la-edit"></i></a>
                        @else
                            <a href="javascript:void(0)" onclick="listLead.popupCustomerCareEdit({{$item['customer_id']}},{{$item['manage_work_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Cập nhật"><i class="la la-edit"></i></a>
                        @endif
                        @if($item['is_deleted'] == 1)
                            <button onclick="Work.removeWork({{$item['manage_work_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa"><i class="la la-trash"></i></button>
                        @endif --}}
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
                    {{-- <td class="text-center">{{$item['manage_type_work_icon'] == null ? $item['manage_type_work_name'] : $item['manage_type_work_icon']}}</td> --}}
                    {{-- <td class="text-center"><a href="{{route('manager-work.detail',['id' => $item['manage_work_id']])}}">{{$item['manage_work_title']}}</a> </td> --}}
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $listWork->links('customer-lead::helpers.paging-work') }}
    </div>
@else
    <div class="col-12 mt-3 p-0">
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