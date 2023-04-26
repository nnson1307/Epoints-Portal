<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap table-border-heading">
        <thead>
        <tr>
            <th colspan="3" class="ss--font-size-th"></th>
            <th colspan="2" class="ss--text-center ss--font-size-th">{{__('Nội bộ')}}</th>
            <th colspan="2" class="ss--text-center ss--font-size-th">{{__('Bên ngoài')}}</th>
            <th colspan="2" class="ss--text-center ss--font-size-th">{{__('Đại lý')}}</th>
            <th colspan="5"></th>
        </tr>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th></th>
            <th class="ss--font-size-th">{{__('Phòng ban')}}</th>
            <th class="ss--font-size-th">{{__('Bán mới')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Renew')}}</th>
            <th class="ss--font-size-th">{{__('Bán mới')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Renew')}}</th>
            <th class="ss--font-size-th">{{__('Bán mới')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Renew')}}</th>

            <th class="ss--text-center ss--font-size-th">{{__('KPI doanh số NVCT')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('KPI doanh số NVTV')}}</th>
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
                    <td class="">
                        <button onclick="SalaryCommissionConfig.edit({{$item['salary_commission_config_id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                        </button>
                    </td>
                    <td class="ss--font-size-13">{{ $item['department_name'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['internal_new'] }}%</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['internal_renew'] }}%</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['external_new'] }}%</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['external_renew'] }}%</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['partner_new'] }}%</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['partner_renew'] }}%</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['kpi_staff'], 0, '', '.') }} VND</td>
                    <td class="ss--text-center ss--font-size-13">{{ number_format($item['kpi_probationers'], 0, '', '.') }} VND</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item['updated_by_full_name'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ date_format(new DateTime($item['updated_at']), 'd/m/Y H:i') }}</td>
                    <td class="ss--text-center ss--font-size-13">
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label class="ss--switch">
                                <input type="checkbox"
                                        onclick="SalaryCommissionConfig.changeStatus(this, '{!! $item['salary_commission_config_id'] !!}', 'publish')"
                                        checked class="manager-btn" name="">
                                <span></span>
                            </label>
                        </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label class="ss--switch">
                                <input type="checkbox"
                                        onclick="SalaryCommissionConfig.changeStatus(this, '{!! $item['salary_commission_config_id'] !!}', 'unPublish')"
                                        class="manager-btn" name="">
                                <span></span>
                            </label>
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