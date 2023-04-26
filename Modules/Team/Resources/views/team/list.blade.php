<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('TÊN NHÓM')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('THÔNG TIN NHÁNH CHA')</th>
            <th class="tr_thead_list">@lang('NGƯỜI QUẢN LÝ')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>
                        {{$item['team_name']}}
                    </td>
                    <td>
                        @if ($item['is_actived'] == 1)
                            @lang('Hoạt động')
                        @else
                            @lang('Tạm ngưng')
                        @endif
                    </td>
                    <td>
                       {{$item['department_name']}}
                    </td>
                    <td>
                        {{$item['staff_title_name'] . ' - ' . $item['staff_name']}}
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        {{--@if(in_array('promotion.edit', session('routeList')))--}}
                        <a href="{{route('team.team.edit', $item['team_id'])}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
                        {{--@endif--}}

                        {{--@if(in_array('promotion.destroy', session('routeList')))--}}
                            <a href="javascript:void(0)" onclick="list.remove('{{$item['team_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
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
