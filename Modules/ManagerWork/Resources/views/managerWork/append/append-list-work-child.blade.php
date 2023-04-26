@if(count($listWorkChild) != 0)
    <div class="col-12 mt-3">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('managerwork::managerwork.action') }}</th>
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
            @foreach($listWorkChild as $key => $item)
                <tr>
                    <td>{{($listWorkChild->currentPage() - 1)*$listWorkChild->perPage() + $key+1 }}</td>
                    <td>
                        @if(\Illuminate\Support\Facades\Session::has('is_staff_work_project') == false || \Illuminate\Support\Facades\Session::get('is_staff_work_project') == 1)
                            <a href="javascript:void(0)" onclick="WorkChild.showPopup({{$item['manage_work_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="{{ __('managerwork::managerwork.update') }}"><i class="la la-edit"></i></a>
                            @if($item['is_deleted'] == 1)
                                <button onclick="WorkChild.removeRemind({{$item['manage_work_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="{{ __('managerwork::managerwork.delete_th') }}"><i class="la la-trash"></i></button>
                            @endif
                        @endif
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
            </tbody>
        </table>
        {{ $listWorkChild->links('manager-work::managerWork.helpers.paging-work') }}
    </div>
@else
    <div class="col-12 mt-5">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('managerwork::managerwork.action') }}</th>
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
                <tr>
                    <td colspan="9" class="text-center">{{ __('managerwork::managerwork.no_data') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif