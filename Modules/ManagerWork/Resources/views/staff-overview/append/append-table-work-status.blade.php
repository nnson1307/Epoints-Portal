<table class="table table-striped m-table ss--header-table">
    <thead>
    <tr>
        <th>#</th>
        <th>{{ __('managerwork::managerwork.type_work') }}</th>
        <th>{{ __('managerwork::managerwork.title') }}</th>
        <th>{{ __('managerwork::managerwork.status') }}</th>
        <th>{{ __('managerwork::managerwork.process') }}</th>
        <th>{{ __('managerwork::managerwork.staff_processor') }}</th>
        <th>{{ __('managerwork::managerwork.date_updated') }}</th>
        <th>{{ __('managerwork::managerwork.date_expiration') }}</th>
    </tr>
    </thead>
    <tbody>
    @if(count($listWorkStatus) != 0)
        @foreach($listWorkStatus as $key => $item)
            <tr>
                <td>
                    {{($listWorkStatus->currentPage() - 1)*$listWorkStatus->perPage() + $key+1 }}
                </td>
                <td>{{$item['manage_type_work_icon'] == null ? $item['manage_type_work_name'] : $item['manage_type_work_icon']}}</td>
                <td><a href="{{route('manager-work.detail',['id' => $item['manage_work_id']])}}">{{$item['manage_work_title']}}</a> </td>
                <td><p class="mb-0 ml-0 status_work_priority " style="background-color:{{$item['manage_color_code']}}">{{$item['manage_status_name']}}</p></td>
                <td>
                    <div class="w-50 d-inline-block">
                        <div class="progress progress-lg ">
                            <div class="progress-bar kt-bg-warning" role="progressbar" style="width: {{$item['progress']}}%;background: #38daca" aria-valuenow="{{$item['progress']}}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <span class="d-inline-block">{{$item['progress'] == null || $item['progress'] == '' ? 0 : $item['progress']}}%</span>
                </td>
                <td>{{$item['staff_name']}}</td>
                <td>{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y')}}</td>
                <td>{{\Carbon\Carbon::parse($item['date_end'])->format('d/m/Y')}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="9" class="text-center">{{__('Không có dữ liệu')}}</td>
        </tr>
    @endif
    </tbody>
</table>
{{ $listWorkStatus->links('manager-work::staff-overview.helpers.paging-table-work-status') }}