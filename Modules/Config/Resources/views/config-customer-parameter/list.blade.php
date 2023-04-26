<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('TÊN THAM SỐ')</th>
            <th class="tr_thead_list">@lang('NỘI DUNG')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
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
                        {{$item['parameter_name']}}
                    </td>
                    <td>
                        {{$item['content']}}
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}}
                    </td>
                    <td>
                        @if(in_array('config.customer-parameter.edit', session('routeList')))
                            <a href="{{route('config.customer-parameter.edit', $item['config_customer_parameter_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('config.customer-parameter.destroy', session('routeList')))
                            <a href="javascript:void(0)" onclick="list.remove('{{$item['config_customer_parameter_id']}}')"
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
{{$LIST->links('helpers.paging') }}
