<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            {{-- <th></th> --}}
            <th class="ss--font-size-th">{{__('Tên bảng lương')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Kỳ lương')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Người cập nhật')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Thời gian cập nhật')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Trạng thái')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--font-size-13">{{ isset($page) ? ($page-1)*10 + $key+1 :$key+1 }}</td>
                    <td class="ss--font-size-13">
                        <a href="{{ route('salary.detail',$item['salary_id']) }}">{{ $item['name'] }}</a>
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{ __('Tháng').' '.$item['season_month'] .'/'.$item['season_year'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['updated_by_full_name'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ \Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y H:i') }}</td>
                    <td class="ss--text-center ss--font-size-13">
                        @if ($item['is_active'] == 0)
                            <span class="m-badge m-badge--success m-badge--wide w-50">
                            {{ __('Chưa khóa') }}
                            </span>
                        @else
                            <span class="m-badge m-badge--danger m-badge--wide w-50">
                            {{ __('Đã khóa') }}
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}