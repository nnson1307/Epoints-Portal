<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table kpi-table" id="staff-table">
        <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th">{{ __('#') }}</th>
                <th class="ss--font-size-th"></th>
                <th class="ss--font-size-th">{{ __('Họ và tên') }}</th>
                <th class="ss--font-size-th">{{ __('Loại nhân viên') }}</th>
                <th class="ss--font-size-th">{{ __('Chi nhánh') }}</th>
                <th class="ss--font-size-th">{{ __('Phòng ban') }}</th>
                <th class="ss--font-size-th">{{ __('Hệ số hoa hồng') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($STAFF_DATA))
                @foreach ($STAFF_DATA as $staffItem)
                    <tr>
                        <td>{{ $staffItem['staff_id'] }}</td>
                        <td>
                            <input type="checkbox" class="staff-checkbox staff-box" id="staff-checkbox" value="{{ $staffItem['staff_id'] }}">
                        </td>
                        <td>{{ $staffItem['full_name'] }}</td>
                        <td>{{ $staffItem['type_name'] }}</td>
                        <td>{{ $staffItem['branch_name'] }}</td>
                        <td>{{ $staffItem['department_name'] }}</td>
                        <td>
                            <div class="input-group" style="max-width: 150px;">
                                <input type="number" class="form-control staff-box" name="commission_coefficient" id="commission_coefficient">
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">Không có dữ liệu</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
