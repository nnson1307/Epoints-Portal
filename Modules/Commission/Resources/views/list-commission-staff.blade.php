<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">{{ __('#') }}</th>
            <th class="ss--font-size-th">{{ __('Hành động') }}</th>
            <th class="ss--font-size-th">{{ __('Họ và tên') }}</th>
            <th class="ss--font-size-th">{{ __('Loại nhân viên') }}</th>
            <th class="ss--font-size-th">{{ __('Chi nhánh') }}</th>
            <th class="ss--font-size-th">{{ __('Phòng ban') }}</th>
            <th class="ss--font-size-th text-center">{{ __('Hoa hồng thực nhận') }}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($STAFF_DATA))
            @foreach ($STAFF_DATA as $k => $staffItem)
                <tr>
                    <td style="vertical-align: middle;">{{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}</td>
                    <td style="vertical-align: middle;">
                        <a href="javascript:void(0)" onclick="listStaff.showPopEdit('{{$staffItem['staff_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
                    </td>
                    <td style="vertical-align: middle;">
                        <a href="{{route('admin.commission.detail-received', $staffItem['staff_id'])}}">
                            {{$staffItem['full_name']}}
                        </a>
                    </td>
                    <td style="vertical-align: middle;">
                        @if ($staffItem['staff_type'] == 'probationers')
                            @lang('Thử việc')
                        @elseif($staffItem['staff_type'] == 'staff')
                            @lang('Chính thức')
                        @endif
                    </td>
                    <td style="vertical-align: middle;">{{ $staffItem['branch_name'] }}</td>
                    <td style="vertical-align: middle;">{{ $staffItem['department_name'] }}</td>
                    <td class="text-center" style="vertical-align: middle;">
                        {{number_format($staffItem['total_commission_money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">@lang('Không có dữ liệu')</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
{{ $STAFF_DATA->links('helpers.paging') }}
