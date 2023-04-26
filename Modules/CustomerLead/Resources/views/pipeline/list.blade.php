<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('TÊN PIPELINE')</th>
            <th class="tr_thead_list">@lang('TÊN DANH MỤC PIPELINE')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN GIỮ LEAD TỐI ĐA')</th>
            <th class="tr_thead_list">@lang('THIẾT LẬP MẶC ĐỊNH')</th>
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
                    <td>
                        <a href="{{route('customer-lead.pipeline.detail', $item['pipeline_id'])}}">
                            {{$item['pipeline_name']}}
                        </a>
                    </td>
                    <td>{{__($item['pipeline_category_name'])}}</td>
                    <td>
                        @if (isset($item['time_revoke_lead']) && $item['time_revoke_lead'] > 0)
                            {{$item['time_revoke_lead']}} {{__('ngày')}}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox" onchange="setDefault({{$item['pipeline_id']}}, '{{$item['pipeline_category_code']}}')"
                                       class="manager-btn" {{$item['is_default'] == 1 ? 'checked' : ''}}>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if(in_array('customer-lead.pipeline.edit', session('routeList')))
                            <a href="{{route('customer-lead.pipeline.edit', $item['pipeline_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('customer-lead.pipeline.destroy', session('routeList')))
                            <a href="javascript:void(0)" onclick="list.remove('{{$item['pipeline_id']}}', {{$item['is_default']}})"
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