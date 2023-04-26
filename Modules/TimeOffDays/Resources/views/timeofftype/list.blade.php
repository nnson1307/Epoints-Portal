<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('LOẠI ĐƠN PHÉP')</th>
            {{-- <th class="tr_thead_list">@lang('NGƯỜI DUYỆT CẤP 1')</th>
            <th class="tr_thead_list">@lang('NGƯỜI DUYỆT CẤP 2')</th>
            <th class="tr_thead_list">@lang('NGƯỜI DUYỆT CẤP 3')</th> --}}
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
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
                    <td>
                        {{ $item['time_off_type_name'] }}
                    </td>
                    {{-- <td>
                        {{ isset($item['direct_management_approve']) == 1 ? 'Quản lý trực tiếp' : '' }}
                    </td>
                    <td>
                        {{ isset($item['approve_level_2_name']) ? $item['approve_level_2_name'] : '' }}
                    </td>
                    <td>
                        {{ isset($item['approve_level_3_name']) ? $item['approve_level_3_name'] : '' }}
                    </td> --}}
                    <td>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox"
                                    {{$item['is_status'] == 1 ? 'checked': ''}} class="manager-btn" name="">
                                <span></span>
                            </label>
                        </span>
                    </td>
                    <td>
                        <a   href="javascript:void(0)" onclick="timeofftype.edit('{{$item['time_off_type_code']}}')"
                                class="test m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Sửa')}}">
                            <i class="la la-edit"></i>
                        </a>
             
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}