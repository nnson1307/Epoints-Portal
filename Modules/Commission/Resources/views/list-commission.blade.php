<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th">{{ __('#') }}</th>
                <th class="ss--font-size-th">{{ __('Hành Động ') }}</th>
                <th class="ss--font-size-th">{{ __('Tên hoa hồng') }}</th>
                <th class="ss--font-size-th">{{ __('Trạng thái ') }}</th>
                <th class="ss--font-size-th">{{ __('Tags') }}</th>
{{--                <th class="ss--font-size-th">{{ __('Thời gian hiệu lực ') }}</th>--}}
{{--                <th class="ss--font-size-th">{{ __('Thời gian áp dụng mỗi') }}</th>--}}
                <th class="ss--font-size-th">{{ __('Người tạo') }}</th>
                <th class="ss--font-size-th">{{ __('Ngày tạo ') }}</th>
            </tr>
        </thead>
        <tbody>

            @if ($COMMISSION_LIST->isNotEmpty())
                @foreach ($COMMISSION_LIST as $key => $item)
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td class="text_middle">
                            @if(isset($page))
                                {{ ($page-1)*10 + $key+1}}
                            @else
                                {{$key+1}}
                            @endif
                        </td>
                        <td>
                            <button onclick="commission.remove(this, {{$item['commission_id']}}, '{{$item['commission_name']}}', {{$item['count_staff']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Xóa">
                                <i class="la la-trash"></i>
                            </button>
                        </td>
                        <td>
                            <a href="{{route('admin.commission.detail', ['id' => $item['commission_id'] ]) }}">
                                {{ $item['commission_name'] }}
                            </a>
                        </td>
                        <td>
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" {{ $item['status'] == 1 ? 'checked' : '' }}
                                            onchange="commission.changeStatus('{{$item['commission_id']}}', this)"
                                        class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                        </td>
                        <td>{{ implode(', ', $item['tags']) }}</td>
{{--                        <td>{{ Carbon\Carbon::parse($item['start_effect_time'])->format('d/m/Y') }}</td>--}}
{{--                        <td>{{ $item['apply_time'] }} @lang('tháng')</td>--}}
                        <td>{{ $item['created_by'] }}</td>
                        <td>{{ Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td align="center" colspan="9">Chưa có dữ liệu</td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
{{ $COMMISSION_LIST->links('helpers.paging') }}
