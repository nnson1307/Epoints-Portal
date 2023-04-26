<div class="table-responsive">
    <table class="table m-table">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tên lịch làm việc')}}</th>
            <th class="tr_thead_list">{{__('Hình thức lặp lại')}}</th>
            <th class="tr_thead_list">{{__('Ngày bắt đầu phân ca')}}</th>
            <th class="tr_thead_list">{{__('Ngày kết thúc phân ca')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th class="tr_thead_list">{{__('Ghi chú')}}</th>
            <th class="tr_thead_list">{{__('Hành động')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $k => $v)
                <tr>
                   <td>
                       {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                   </td>
                    <td>{{$v['work_schedule_name']}}</td>
                    <td>{{$v['repeat'] == 'hard' ? __('Cố định'): __('Hàng tháng')}}</td>
                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $v['start_day_shift'])->format('d/m/Y')}}</td>
                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $v['end_day_shift'])->format('d/m/Y')}}</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           {{$v['is_actived'] == 1 ? 'checked': ''}} class="manager-btn" onchange="">
                                    <span></span>
                                </label>
                            </span>
                    </td>
                    <td>{{$v['note']}}</td>
                    <td>
                        {{--@if(in_array('promotion.edit', session('routeList')))--}}
                            <a href="{{route('shift.work-schedule.edit', $v['work_schedule_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        {{--@endif--}}
                        {{--@if(in_array('promotion.destroy', session('routeList')))--}}
                        <a href="javascript:void(0)" onclick="index.remove('{{$v['work_schedule_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Xóa')">
                            <i class="la la-trash"></i>
                        </a>
                        {{--@endif--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
