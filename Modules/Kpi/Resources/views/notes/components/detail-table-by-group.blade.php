<div class="table-responsive" style="padding-bottom: 7px;">
    @if ($DETAIL_DATA['generalDetail']['status'] == 'N')
        <table class="table table-striped m-table ss--header-table" id="criteria-table">
            <thead class="bg">
                <tr>
                    <th class="tr_thead_list text-center align-middle">#</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Tiêu chí') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Độ Quan Trọng') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Chỉ tiêu') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Đơn Vị') }}</th>
                </tr>
            </thead>
            
            <tbody>
                <!-- Dòng data nhân viên -->
                @foreach ($DETAIL_DATA['listDetail'] as $key => $detailItem)
                    <tr class="tr_template">
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ __($detailItem['kpi_criteria_name']) }}</td>
                        <td class="text-center">{{ $detailItem['priority'] }}%</td>
                        <td class="text-center">{{ number_format(floatval($detailItem['kpi_value']), strlen(substr(strrchr(floatval($detailItem['kpi_value']), "."), 1))) }}</td>
                        <td class="text-center">{{ __($detailItem['unit_name']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table class="table table-striped m-table ss--header-table" id="criteria-table">
            <thead class="bg">
                <tr>
                    <th class="tr_thead_list text-center align-middle">#</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Tiêu chí') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Độ Quan Trọng') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Chỉ tiêu') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Thực tế') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('Đơn Vị') }}</th>
                    <th class="tr_thead_list text-center align-middle">{{ __('% KPI hoàn thành') }}</th>
                </tr>
            </thead>
            
            <tbody>
                <!-- Dòng data nhân viên -->
                @foreach ($DETAIL_DATA['listDetail'] as $key => $detailItem)
                    <tr class="tr_template">
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ __($detailItem['kpi_criteria_name']) }}</td>
                        <td class="text-center">{{ $detailItem['priority'] }}%</td>
                        <td class="text-center">{{ number_format(floatval($detailItem['kpi_value']), strlen(substr(strrchr(floatval($detailItem['kpi_value']), "."), 1))) }}</td>

                        <!-- Nếu tiêu chí là do người dùng tự thêm thì cho phép chỉnh sửa -->
                        @if ($detailItem['is_customize'] == 1)
                            <td class="text-center">
                                <input type="hidden" id="kpi_note_detail_id" value="{{ $detailItem['kpi_note_detail_id'] }}">
                                <input type="hidden" id="kpi_criteria_id" value="{{ $detailItem['kpi_criteria_id'] }}">
                                <input type="hidden" id="branch_id" value="{{ $DETAIL_DATA['generalDetail']['branch_id'] }}">
                                <input type="hidden" id="department_id" value="{{ $DETAIL_DATA['generalDetail']['department_id'] }}">
                                <input type="hidden" id="team_id" value="{{ $DETAIL_DATA['generalDetail']['team_id'] }}">
                                <input type="hidden" id="staff_id" value="">
                                <input type="hidden" id="month" value="{{ $DETAIL_DATA['generalDetail']['effect_month'] }}">
                                <input type="hidden" id="year" value="{{ $DETAIL_DATA['generalDetail']['effect_year'] }}">
                                <input type="hidden" id="kpi_criteria_trend" value="{{ $detailItem['kpi_criteria_trend'] }}">
                                <input type="hidden" id="is_blocked" value="{{ $detailItem['is_blocked'] }}">
                                <input type="hidden" id="priority" value="{{ $detailItem['priority'] }}">
                                <input type="hidden" id="kpi_target" value="{{ $detailItem['kpi_value'] }}">
                                <input type="hidden" id="kpi_criteria_unit_id" value="{{ $detailItem['kpi_criteria_unit_id'] }}">
                                <input type="text" class="kpi_calculate_value" id="kpi_calculate_value" value="{{ number_format(floatval($detailItem['kpi_calculate_value']), strlen(substr(strrchr(floatval($detailItem['kpi_calculate_value']), "."), 1))) }}" 
                                                onkeypress="return event.charCode > 47 && event.charCode < 58;" pattern="[0-9]" disabled>
                                @if ($detailItem['kpi_report'] < 0)
                                    <span style="color: red;">
                                        <b>{{ '('. number_format($detailItem['kpi_report'], 2) .')' }}</b>
                                    </span>
                                @elseif ($detailItem['kpi_report'] > 0)
                                    <span style="color: green;">
                                        <b>{{ '(+'. number_format($detailItem['kpi_report'], 2) .')' }}</b>
                                    </span>
                                @else
                                    <span style="color: green;">
                                        <b>{{ '('. number_format($detailItem['kpi_report'], 2) .')' }}</b>
                                    </span>
                                @endif

                                @if ($DETAIL_DATA['generalDetail']['status'] == 'A')
                                <a href="#" 
                                id="btn-edit-custom-kpi"
                                class="btn-edit-criteria m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" 
                                title="{{ __('Chỉnh sửa') }}">
                                    <i class="la la-edit"></i>
                                </a>
                                @endif
                            </td>
                        <!-- Ngược lại cái trên thì không có icon sửa -->
                        @else
                            <td class="text-center">
                                {{ number_format(floatval($detailItem['kpi_calculate_value']), strlen(substr(strrchr(floatval($detailItem['kpi_calculate_value']), "."), 1))) }}
                                @if ($detailItem['kpi_report'] < 0)
                                    <span style="color: red;">
                                        <b>{{ '('. number_format(floatval($detailItem['kpi_report']), strlen(substr(strrchr(floatval($detailItem['kpi_report']), "."), 1))) .')' }}</b>
                                    </span>
                                @elseif ($detailItem['kpi_report'] > 0)
                                    <span style="color: green;">
                                        <b>{{ '('. number_format(floatval($detailItem['kpi_report']), strlen(substr(strrchr(floatval($detailItem['kpi_report']), "."), 1))) .')' }}</b>
                                    </span>
                                @else
                                    <span style="color: green;">
                                        <b>{{ '('. number_format(floatval($detailItem['kpi_report']), strlen(substr(strrchr(floatval($detailItem['kpi_report']), "."), 1))) .')' }}</b>
                                    </span>
                                @endif
                            </td>
                        @endif
                        <td class="text-center">{{ __($detailItem['unit_name']) }}</td>
                        <td class="text-center">{{ round($detailItem['kpi_percent'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>