<table class="table table-striped m-table m-table--head-bg-default">
    <thead class="bg">
    <tr>
        <th class="tr_thead_list">#</th>
        <th class="tr_thead_list">@lang('Loại thưởng')</th>
        <th class="tr_thead_list">@lang('Mức áp dụng')</th>
        <th class="tr_thead_list">@lang('Hành động')</th>
    </tr>
    </thead>
    <tbody>
    @if (count($list) > 0)
        @foreach($list as $k => $v)
            <tr class="tr_shift">
                <td>
                    {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                </td>
                <td>{{$v['recompense_name']}}</td>
                <td>{{number_format($v['money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                <td>
                    <a href="javascript:void(0)"
                       onclick="index.removeRecompense('{{$v['time_working_staff_recompense_id']}}', 'R')"
                       class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                       title="@lang('Xóa')">
                        <i class="la la-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
{{ $list->links('helpers.paging') }}


