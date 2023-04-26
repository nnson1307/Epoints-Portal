<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('HỌ & TÊN')</th>
            <th class="tr_thead_list">@lang('SỐ ĐIỆN THOẠI')</th>
            <th class="tr_thead_list">@lang('GIỚI TÍNH')</th>
            <th class="tr_thead_list">@lang('ĐỊA CHỈ')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
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
                    <td>{{$item['full_name']}}</td>
                    <td>{{$item['phone']}}</td>
                    <td>
                        @if ($item['gender'] == "female")
                            @lang('Nữ')
                        @elseif($item['gender'] == "male")
                            @lang('Nam')
                        @else
                            @lang('Khác')
                        @endif
                    </td>
                    <td>{{$item['address']}}</td>
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox"
                                       onchange="listCarrier.changeStatus('{{$item['user_carrier_id']}}', this)"
                                       class="manager-btn" {{$item['is_actived'] == 1 ? 'checked' : ''}}>
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        @if(in_array('user-carrier.edit', session('routeList')))
                            <a href="{{route('user-carrier.edit', $item['user_carrier_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('user-carrier.destroy', session('routeList')))
                            <a href="javascript:void(0)" onclick="listCarrier.remove('{{$item['user_carrier_id']}}')"
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
