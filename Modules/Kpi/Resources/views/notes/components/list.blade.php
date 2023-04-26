<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th">{{ __('#') }}</th>
                <th class="ss--font-size-th">{{ __('Tên phiếu giao KPI') }}</th>
                <th class="ss--font-size-th">{{ __('Thời Gian Tính KPI') }}</th>
                <th class="ss--font-size-th">{{ __('Chi nhánh') }}</th>
                <th class="ss--font-size-th">{{ __('Phòng ban') }}</th>
                <th class="ss--font-size-th">{{ __('Nhóm') }}</th>
                <th class="ss--font-size-th">{{ __('Người tạo') }}</th>
                <th class="ss--font-size-th">{{ __('Thời gian tạo') }}</th>
                <th class="ss--font-size-th">{{ __('Trạng thái') }}</th>
                <th class="ss--font-size-th">{{ __('Hành động') }}</th>  
            </tr>
        </thead>

        <tbody>
            @if (isset($data) && $data->isNotEmpty())
                @foreach ($data as $key => $item)
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td class="text_middle">
                            @if(isset($page))
                                {{ ($page-1)*10 + $key+1}}
                            @else
                                {{$key+1}}
                            @endif
                        </td>
                        <td>{{ $item['kpi_note_name'] }}</td>
                        <td>{{ __("Tháng " .$item['effect_month']) }}/{{ $item['effect_year'] }}</td>
                        <td>{{ $item['branch_name'] }}</td>
                        <td>{{ $item['department_name'] }}</td>
                        <td>{{ $item['team_name'] }}</td>
                        <td>{{ $item['created_by'] }}</td>
                        <td>{{ Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i') }}</td>
                        @if ($item['status'] == 'N')
                            <td>{{ __('Mới') }}</td>
                        @elseif ($item['status'] == 'A')
                            <td>{{ __('Đang áp dụng') }}</td>
                        @else
                            <td>{{ __('Đã chốt') }}</td>
                        @endif
                        <td>
                            {{-- @if (in_array('kpi.note.detail', session('routeList'))) --}}
                                <a href="{{ route('kpi.note.detail', ['id' => $item['kpi_note_id']]) }}" 
                                    class="btn-edit-criteria m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" 
                                    title="{{ __('Xem chi tiết') }}">
                                    <i class="la la-eye"></i>
                                </a>
                            {{-- @endif --}}

                            {{-- @if (in_array('kpi.note.edit', session('routeList'))) --}}
                                @if ($item['status'] == 'N')
                                    <a href="{{ route('kpi.note.edit', ['id' => $item['kpi_note_id']]) }}" 
                                        class="btn-edit-criteria m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" 
                                        title="{{ __('Chỉnh sửa') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                @endif
                            {{-- @endif --}}

                            {{-- @if (in_array('kpi.note.remove', session('routeList'))) --}}
                                @if ($item['status'] == 'N'|| $item['status'] == 'A')
                                    <button onclick="KpiNote.remove(this, {{ $item['kpi_note_id'] }})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{ __('Xóa') }}">
                                        <i class="la la-trash"></i>
                                    </button>
                                @endif
                            {{-- @endif --}}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td align="center" colspan="10">{{ __('Chưa có dữ liệu') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

{{ $data->links('helpers.paging') }}
