<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('TÊN DANH MỤC PIPELINE')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>{{$item['pipeline_category_name']}}</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox"
                                       onchange="listCategory.changeStatus('{{$item['pipeline_category_id']}}', this)"
                                       class="manager-btn" {{$item['is_actived'] == 1 ? 'checked' : ''}}>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
{{--                        @if(in_array('customer-lead.pipeline-category.edit', session('routeList'))--}}
{{--                            && !in_array($item['pipeline_category_code'], ['CUSTOMER', 'ORDER', 'CUSTOMER_APPOINTMENT']))--}}
{{--                            <a href="{{route('customer-lead.pipeline-category.edit', $item['pipeline_category_id'])}}"--}}
{{--                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                               title="@lang('Chỉnh sửa')">--}}
{{--                                <i class="la la-edit"></i>--}}
{{--                            </a>--}}
{{--                        @endif--}}
{{--                        @if(in_array('customer-lead.pipeline-category.destroy', session('routeList'))--}}
{{--                            && !in_array($item['pipeline_category_code'], ['CUSTOMER', 'ORDER', 'CUSTOMER_APPOINTMENT']))--}}
{{--                            <a href="javascript:void(0)" onclick="listCategory.remove('{{$item['pipeline_category_id']}}')"--}}
{{--                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                               title="@lang('Xóa')">--}}
{{--                                <i class="la la-trash"></i>--}}
{{--                            </a>--}}
{{--                        @endif--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
