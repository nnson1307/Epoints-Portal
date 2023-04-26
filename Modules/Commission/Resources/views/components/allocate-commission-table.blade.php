<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table kpi-table" id="commission-table">
        <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th">{{ __('#') }}</th>
                <th class="ss--font-size-th"></th>
                <th class="ss--font-size-th">{{ __('Loại hoa hồng') }}</th>
                <th class="ss--font-size-th">{{ __('Tên hoa hồng') }}</th>
                <th class="ss--font-size-th">{{ __('Thời gian hiệu lực') }}</th>
                <th class="ss--font-size-th">{{ __('Thời gian áp dụng mỗi') }}</th>
                <th class="ss--font-size-th">{{ __('Lấy giá trị tính dựa trên') }}</th>
                <th class="ss--font-size-th">{{ __('Tags') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($COMMISSION_DATA))
                @foreach ($COMMISSION_DATA as $commissionItem)
                    <tr>
                        <td>{{ $commissionItem['commission_id'] }}</td>
                        <td>
                            <input type="checkbox" data-id="{{ $commissionItem['commission_id'] }}" class="commission-checkbox" value="{{ $commissionItem['commission_id'] }}">
                        </td>
                        @if ($commissionItem['commission_type'] == 'order')
                            <td>Hoa hồng theo doanh thu đơn hàng</td>
                        @elseif ($commissionItem['commission_type'] == 'kpi')
                            <td>Hoa hồng theo KPI</td>
                        @else
                            <td>Hoa hồng theo hợp đồng</td>
                        @endif
                        <td>{{ $commissionItem['commission_name'] }}</td>
                        <td>{{ $commissionItem['start_effect_time'] }}</td>
                        <td>{{ $commissionItem['apply_time'] }} tháng</td>
                        <td>{{ $commissionItem['calc_apply_time'] }} tháng</td>
                        <td>{{ implode(', ', $commissionItem['tags']) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
