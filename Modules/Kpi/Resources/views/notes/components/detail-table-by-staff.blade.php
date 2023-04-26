<div class="table-responsive" style="padding-bottom: 7px;">
    <!-- Nếu phiếu giao ở trạng thái mới thì không hiện kết quả thực tế KPI -->
    @if ($DETAIL_DATA['generalDetail']['status'] == 'N')
        <table class="table table-striped m-table ss--header-table" id="criteria-table">
            <thead class="bg">
                <tr>
                    <th rowspan="2" class="tr_thead_list text-center align-middle">#</th>
                    <th rowspan="2" class="tr_thead_list text-center align-middle">{{ __('Nhân viên') }}</th>
                    <!-- gen từ list tiêu chí -->
                    @foreach ($DETAIL_DATA['listDetail'][0]['kpi_criteria_name'] as $criteriaName)
                        <th colspan="3" class="tr_thead_list text-center">{{ __($criteriaName) }}</th>
                    @endforeach
                </tr>
                <!-- Đếm số tiêu chí để gen ra cột -->
                <tr>
                    @for ($i = 0; $i < count($DETAIL_DATA['listDetail'][0]['kpi_criteria_name']); $i++)
                        <th class="text-center">{{ __('Độ Quan Trọng') }}</th>
                        <th class="text-center">{{ __('Kết Quả Chỉ Tiêu') }}</th>
                        <th class="text-center">{{ __('Đơn Vị') }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                <!-- Dòng data nhân viên -->
                @foreach ($DETAIL_DATA['listDetail'] as $key => $detailItem)
                    <tr class="tr_template">
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ $detailItem['full_name'] }}</td>
                        @foreach ($detailItem['priority'] as $key => $priorityItem)
                            <td class="text-center">{{ $priorityItem }}%</td>
                            <td class="text-center">{{ number_format(preg_replace('/[^\d.]/', '', $detailItem['kpi_value'][$key])) }}</td>
                            <td class="text-center">{{ __($detailItem['unit_name'][$key]) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    <!-- Nếu phiếu giao ở trạng thái đang áp dụng hoặc đã chốt thì hiện kết quả thực tế KPI -->
    @else
        <table class="table table-striped m-table ss--header-table" id="criteria-table">
            <thead class="bg">
                <tr>
                    <th rowspan="2" class="tr_thead_list text-center align-middle">#</th>
                    <th rowspan="2" class="tr_thead_list text-center align-middle">{{ __('Nhân viên') }}</th>
                    <th rowspan="2" class="tr_thead_list text-center align-middle">{{ __('Tổng KPI') }}</th>
                    <!-- gen từ list tiêu chí -->
                    @foreach ($DETAIL_DATA['listDetail'][0]['kpi_criteria_name'] as $criteriaName)
                        <th colspan="3" class="tr_thead_list text-center">{{ __($criteriaName) }}</th>
                    @endforeach
                </tr>
                <!-- Đếm số tiêu chí để gen ra cột -->
                <tr>
                    @for ($i = 0; $i < count($DETAIL_DATA['listDetail'][0]['kpi_criteria_name']); $i++)
                        <th class="text-center">{{ __('Độ Quan Trọng') }}</th>
                        <th class="text-center">{{ __('Kết Quả Chỉ Tiêu') }}</th>
                        <th class="text-center">{{ __('Đơn Vị') }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                <!-- Dòng data nhân viên -->
                @foreach ($DETAIL_DATA['listDetail'] as $key => $detailItem)
                    <tr class="tr_template">
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ $detailItem['full_name'] }}</td>
                        <td class="text-center">{{ round(array_sum($detailItem['total_kpi_percent']), 2) }}%</td>
                        @foreach ($detailItem['priority'] as $key => $priorityItem)
                            <td class="text-center">{{ $priorityItem }}%</td>
                            @if ($detailItem['unit_name'][$key] == 'VND')
                                <td class="text-center">{{ number_format(round($detailItem['kpi_calculate_value'][$key], 0)) }} / {{ number_format(preg_replace('/[^\d.]/', '', $detailItem['kpi_value'][$key])) }}</td>
                            @else
                                @if ($detailItem['is_customize'][$key] == 1)
                                    <td class="text-center">
                                        <input type="hidden" id="kpi_note_detail_id" value="{{ $detailItem['kpi_note_detail_id'][$key] }}">
                                        <input type="hidden" id="kpi_criteria_id" value="{{ $detailItem['kpi_criteria_id'][$key] }}">
                                        <input type="hidden" id="branch_id" value="{{ $DETAIL_DATA['generalDetail']['branch_id'] }}">
                                        <input type="hidden" id="department_id" value="{{ $DETAIL_DATA['generalDetail']['department_id'] }}">
                                        <input type="hidden" id="team_id" value="{{ $DETAIL_DATA['generalDetail']['team_id'] }}">
                                        <input type="hidden" id="staff_id" value="{{ $detailItem['staff_id'] }}">
                                        <input type="hidden" id="month" value="{{ $DETAIL_DATA['generalDetail']['effect_month'] }}">
                                        <input type="hidden" id="year" value="{{ $DETAIL_DATA['generalDetail']['effect_year'] }}">
                                        <input type="hidden" id="kpi_criteria_trend" value="{{ $detailItem['kpi_criteria_trend'][$key] }}">
                                        <input type="hidden" id="is_blocked" value="{{ $detailItem['is_blocked'][$key] }}">
                                        <input type="hidden" id="priority" value="{{ $detailItem['priority'][$key] }}">
                                        <input type="hidden" id="kpi_target" value="{{ $detailItem['kpi_value'][$key] }}">
                                        <input type="hidden" id="kpi_criteria_unit_id" value="{{ $detailItem['kpi_criteria_unit_id'][$key] }}">
                                        <input type="text" class="kpi_calculate_value" id="kpi_calculate_value" value="{{ number_format(round($detailItem['kpi_calculate_value'][$key], 0)) }}" 
                                                onkeypress="return event.charCode > 47 && event.charCode < 58;" pattern="[0-9]" disabled> 
                                        / 
                                        {{ number_format(preg_replace('/[^\d.]/', '', $detailItem['kpi_value'][$key])) }}
                                        @if ($DETAIL_DATA['generalDetail']['status'] == 'A')
                                        <a href="#" 
                                            id="btn-edit-custom-kpi"
                                            class="btn-edit-criteria m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" 
                                            title="{{ __('Chỉnh sửa') }}">
                                            <i class="la la-edit"></i>
                                        </a>
                                        @endif
                                    </td>
                                @else
                                    <td class="text-center">{{ number_format(round($detailItem['kpi_calculate_value'][$key], 0)) }} / {{ number_format(preg_replace('/[^\d.]/', '', $detailItem['kpi_value'][$key])) }}</td>
                                @endif
                            @endif
                            <td class="text-center">{{ __($detailItem['unit_name'][$key]) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>