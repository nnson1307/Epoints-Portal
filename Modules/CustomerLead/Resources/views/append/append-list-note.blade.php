@if(isset($listNotes) && count($listNotes) != 0)
    <div class="col-12 mt-3 p-0">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th class="text-center">@lang('STT')</th>
                <th class="text-center">@lang('Nội dung')</th>
                <th class="text-center">@lang('Ngày tạo')</th>
                <th class="text-center">@lang('Người tạo')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($listNotes as $key => $item)
                <tr>
                    <td class="text-center">{{($listWork->currentPage() - 1)*$listWork->perPage() + $key+1 }}</td>
                    <td class="text-center">{{ $item['content'] }}</td>
                    <td class="text-center">
                        {{ App\Helpers\Helper::formatDateTime($item['created_at']) }}
                    </td>
                    <td class="text-center">{{ $item['created_by'] }}</td>
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
                <th class="text-center">@lang('Nội dung')</th>
                <th class="text-center">@lang('Ngày tạo')</th>
                <th class="text-center">@lang('Người tạo')</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="9" class="text-center">@lang('Không có dữ liệu')</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif