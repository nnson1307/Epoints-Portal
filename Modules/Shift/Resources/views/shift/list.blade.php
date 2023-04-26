<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('TÊN CA')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN')</th>
            <th class="tr_thead_list">@lang('GIỜ LÀM')</th>
            <th class="tr_thead_list">@lang('GIỜ NGHỈ TRƯA')</th>
            <th class="tr_thead_list">@lang('GIỜ LÀM TỐI THIỂU')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('GHI CHÚ')</th>
            <th class="tr_thead_list">@lang('HÀNH ĐỘNG')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $k => $item)
                <tr>
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>{{$item['shift_name']}}</td>
                    <td>{{\Carbon\Carbon::createFromFormat("H:i:s", $item['start_work_time'])->format('H:i')}}
                        - {{\Carbon\Carbon::createFromFormat("H:i:s", $item['end_work_time'])->format('H:i')}}</td>
                    <td>
                        {{round($item['time_work'], 2)}} @lang('giờ')
                    </td>

                    <td>
                        @if ($item['start_lunch_break'] != null && $item['end_lunch_break'] != null)
                            {{round($item['hour_lunch'], 2)}} @lang('giờ')
                        @endif
                    </td>
                    <td>{{floatval($item['min_time_work'])}} @lang('giờ')</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               onclick="listShift.changeStatus(this, '{{$item['shift_id']}}')"
                                               {{$item['is_actived'] == 1 ? 'checked': ''}} class="manager-btn" name="">
                                        <span></span>
                                    </label>
                        </span>
                    </td>
                    <td>{!! str_limit($item['note'],100,'...') !!}</td>
                    <td>
                        @if(in_array('shift.edit', session('routeList')))
                            <a href="javascript:void(0)" onclick="edit.popupEdit('{{$item['shift_id']}}', false)"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif

                        @if(in_array('shift.destroy', session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="listShift.remove('{{$item['shift_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                        @endif

                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
