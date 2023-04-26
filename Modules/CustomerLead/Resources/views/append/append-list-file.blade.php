@if(isset($listFiles) && count($listFiles) != 0)
    <div class="col-12 mt-3 p-0">
        <table class="table table-striped m-table ss--header-table" data-id="{{ $item['customer_lead_id'] }}">
            <thead>
            <tr>
                <th class="text-center">@lang('Chức năng')</th>
                <th class="text-center">@lang('STT')</th>
                <th class="text-center">@lang('Tập tin')</th>
                <th class="text-center">@lang('Nội dung')</th>
                <th class="text-center">@lang('Người tạo')/@lang('Người cập nhật')</th>
                <th class="text-center">@lang('Ngày tạo')/@lang('Ngày cập nhật')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($listFiles as $key => $item)
                <tr>
                    <td class="text-center"><a href="#" class="edit-file" data-id="{{ $item['customer_lead_file_id'] }}"><i class="la la-edit"></i></a></td>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center"><a href="{{ asset($item['path']) }}">{{ $item['file_name'] }}</a></td>
                    <td class="text-center">{{ $item['content'] }}</td>
                    <td class="text-center">{{ $item['created_by'] }}<br>{{ $item['updated_by'] }}</td>
                    <td class="text-center">
                        {{ App\Helpers\Helper::formatDateTime($item['created_at']) }}<br>
                        {{ App\Helpers\Helper::formatDateTime($item['updated_at']) }}
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
                <th class="text-center">@lang('Chức năng')</th>
                <th class="text-center">@lang('STT')</th>
                <th class="text-center">@lang('Tập tin')</th>
                <th class="text-center">@lang('Nội dung')</th>
                <th class="text-center">@lang('Người tạo')/@lang('Người cập nhật')</th>
                <th class="text-center">@lang('Ngày tạo')/@lang('Ngày cập nhật')</th>
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