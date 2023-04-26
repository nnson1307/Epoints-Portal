<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th">{{ __('#') }}</th>
                <th class="ss--font-size-th">{{ __('Tên tiêu chí') }}</th>
                <th class="ss--font-size-th">{{ __('Chiều hướng tốt') }}</th>
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
                        <td>{{ __($item['kpi_criteria_name']) }}</td>
                        <td>{{ $item['kpi_criteria_trend'] == 0 ? __("Giảm") : __("Tăng") }}</td>
                        <td>{{ $item['created_by'] }}</td>
                        <td>{{ Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}</td>
                        <td>
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" disabled {{ $item['status'] == 1 ? 'checked' : '' }}
                                        class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                        </td>
                        <td>
                            @if (in_array('kpi.criteria.list', session('routeList')))
                                <a href="#" 
                                    class="btn-edit-criteria m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" 
                                    title="Chi tiết"
                                    data-id="{{ $item['kpi_criteria_id'] }}"
                                    data-name="{{ $item['kpi_criteria_name'] }}"
                                    data-unit="{{ $item['kpi_criteria_unit_id'] }}"
                                    data-description="{{ $item['description'] }}"
                                    data-trend="{{ $item['kpi_criteria_trend'] }}"
                                    data-blocked="{{ $item['is_blocked'] }}"
                                    data-status="{{ $item['status'] }}"
                                    data-lead="{{ $item['is_lead'] }}"
                                    data-pipeline="{{ $item['pipeline_id'] }}"
                                    data-journey="{{ $item['journey_id'] }}">
                                    <i class="la la-edit"></i>
                                </a>
                            @endif

                            @if (in_array('kpi.criteria.remove', session('routeList')))
                                @if ($item['is_customize'] == 1)
                                    <button onclick="KpiCriteria.remove(this, {{ $item['kpi_criteria_id'] }})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Xóa">
                                        <i class="la la-trash"></i>
                                    </button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td align="center" colspan="7">{{ __('Chưa có dữ liệu') }}</td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
{{ $data->links('helpers.paging') }}
