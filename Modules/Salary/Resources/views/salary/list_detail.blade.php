<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">{{__('STT')}}</th>
            <th></th>
            <th class="ss--font-size-th">{{__('Mã nhân viên')}}</th>
            <th class="ss--font-size-th">{{__('Tên nhân viên')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Phòng ban')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Lương cơ bản')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Tổng doanh thu')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Thưởng hoa hồng')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Thưởng KPIs')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Phụ cấp')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Tăng')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Giảm')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Tổng tiền thực lĩnh')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--font-size-13">{{ isset($page) ? ($page-1)*10 + $key+1 :$key+1 }}</td>
                    <td class="">
                        <a href="{{route('salary.salary-edit',['id' => $item['salary_staff_id']])}}"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                        </a>
                    </td>
                    <td class="ss--font-size-13">{{ $item['staff_code'] }}</td>
                    <td class="ss--font-size-13">
                        <a href="{{route('admin.staff.show',['id' => $item['staff_id']])}}?salary_id={{$item['salary_id']}}">
                            {{ $item['staff_name'] }}
                        </a>
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['department_name'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['salary'], 0, '', '.') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['total_revenue'], 0, '', '.') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['total_commission'], 0, '', '.') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['total_kpi'], 0, '', '.') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['total_allowance'], 0, '', '.') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['plus'], 0, '', '.') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['minus'], 0, '', '.') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['total'], 0, '', '.') }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}