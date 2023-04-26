<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('#')</th>
            <th class="tr_thead_list">@lang('TÊN LOẠI TIẾNG VIỆT')</th>
            <th class="tr_thead_list">@lang('TÊN LOẠI TIẾNG ANH')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$item['customer_info_type_name_vi']}}</td>
                    <td>{{$item['customer_info_type_name_en']}}</td>
                    <td class="text_middle">
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn" checked
                                           onclick="customerInfoType.updateStatus('{{$item['customer_info_type_id']}}', 0)">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn"
                                           onclick="customerInfoType.updateStatus('{{$item['customer_info_type_id']}}', 1)">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if(in_array('customer-info-type.edit', session('routeList')))
                            <button onclick="customerInfoType.edit('{{$item['customer_info_type_id']}}')"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Sửa')}}">
                                <i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('customer-info-type.delete', session('routeList')))
                            <button onclick="customerInfoType.remove( {{$item['customer_info_type_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xoá')}}">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
