@if(!empty($arrContact))
    <div class="col-12 mt-3 p-0">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th class="text-center">@lang('STT')</th>
                <th class="text-center">@lang('Tên khách hàng')</th>
                <th class="text-center">@lang('Số điện thoại')</th>
                <th class="text-center">@lang('Email')</th>
                <th class="text-center">@lang('Chức vụ')</th>
                <th class="text-center">@lang('Ngày tạo')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($arrContact as $key => $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $item['full_name'] }}</td>
                    <td class="text-center">{{ $item['phone'] }}</td>
                    <td class="text-center">{{ $item['email'] }}</td>
                    <td class="text-center">{{ $item['staff_title_name'] }}</td>
                    <td class="text-center">
                        {{ App\Helpers\Helper::formatDateTime($item['created_at']) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $listWork->links('customer-lead::helpers.paging-work') }}
    </div>
@else
    <div class="col-12 mt-3 p-0">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th class="text-center">@lang('STT')</th>
                <th class="text-center">@lang('Tên khách hàng')</th>
                <th class="text-center">@lang('Số điện thoại')</th>
                <th class="text-center">@lang('Email')</th>
                <th class="text-center">@lang('Chức vụ')</th>
                <th class="text-center">@lang('Ngày tạo')</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center">@lang('Không có dữ liệu')</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif