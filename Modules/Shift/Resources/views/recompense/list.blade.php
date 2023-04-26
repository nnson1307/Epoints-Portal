<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">@lang('LOẠI')</th>
            <th class="tr_thead_list">@lang('NỘI DUNG')</th>
            <th class="tr_thead_list">@lang('NGƯỜI TẠO')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $k => $item)
                <tr>
                    <td>
                        @if(in_array('shift.recompense.show-pop-edit', session('routeList')) && $item['is_system'] == 0)
                            <a href="javascript:void(0)"
                               onclick="listRecompense.showPopEdit('{{$item['recompense_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif

                        @if(in_array('shift.recompense.destroy', session('routeList')) && $item['is_system'] == 0)
                            <a href="javascript:void(0)"
                               onclick="listRecompense.remove('{{$item['recompense_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                        @endif
                    </td>
                    <td>
                        @switch($item['type'])
                            @case('R')
                            @lang('Thưởng')
                            @break
                            @case('P')
                            @lang('Phạt')
                            @break
                        @endswitch
                    </td>
                    <td>{{$item['recompense_name']}}</td>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               onclick="listRecompense.changeStatus(this, '{{$item['recompense_id']}}')"
                                               {{$item['is_actived'] == 1 ? 'checked': ''}} class="manager-btn" name="">
                                        <span></span>
                                    </label>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
