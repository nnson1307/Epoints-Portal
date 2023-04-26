<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('TÊN CÔNG TY')</th>
            <th class="tr_thead_list">@lang('MÃ CÔNG TY')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>
                        {{$item['company_name']}}
                    </td>
                    <td>
                        {{$item['company_code']}}
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox"
                                       onchange="listCompany.changeStatus('{{$item['company_id']}}', this)"
                                       class="manager-btn" {{$item['is_actived'] == 1 ? 'checked' : ""}}>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        {{--@if(in_array('promotion.edit', session('routeList')))--}}
                        <a href="{{route('team.company.edit', $item['company_id'])}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
                        {{--@endif--}}

                        {{--@if(in_array('promotion.destroy', session('routeList')))--}}
                        <a href="javascript:void(0)" onclick="listCompany.remove('{{$item['company_id']}}')"
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
