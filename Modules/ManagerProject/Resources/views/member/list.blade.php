<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
            <tr>
                <th class="ss--font-size-th">#</th>
                <th class="ss--font-size-th">{{ __('Hành động') }}</th>
                <th class="ss--font-size-th">{{ __('Tên nhân viên') }}</th>
                <th class="ss--font-size-th">{{ __('Vai trò') }}</th>
                <th class="ss--font-size-th">{{ __('Phòng ban') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($list))
                @foreach ($list as $key => $item)
                    <tr>
                        <td class="ss--font-size-13">
                            {{($list->currentPage() - 1)*$list->perPage() + $key+1 }}
                        </td>
                        <td class="">
                            @if(!in_array($item->staff_id,$listStaffManage))
                                @if(in_array(\Auth::id(),$listStaffProject))
{{--                                    <button onclick="member.showModalEdit('{{ $item->manage_project_staff_id }}')"--}}
{{--                                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"--}}
{{--                                        title="{{ __('Cập nhật') }}"><i class="la la-edit"></i>--}}
{{--                                    </button>--}}
                                    <button onclick="member.remove(this,'{{$item->manage_project_id}}','{{ $item->manage_project_staff_id }}','{{ $item->full_name }}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{ __('Xóa') }}"><i class="la la-trash"></i>
                                    </button>
                                @endif
                            @endif
                        </td>
                        <td class="ss--font-size-13">

{{--                            <a href="javascript:void(0)" onclick="member.showModalDetail('{{ $item->manage_project_staff_id }}')">--}}
                            <a href="{{ route('manager-project.work', ['manage_project_id' => $item->manage_project_id , 'processor_id' => $item->staff_id]) }}">
                                <img src="{{ $item->staff_avatar }}"
                                    class="{{ $item->staff_avatar ? 'info-user' : '' }}" alt="">
                                {{ $item->full_name }}
                            </a>
                        </td>
                        <td class="ss--font-size-13">
                            {{ $item->manage_project_role_name }}
                        </td>
                        <td class="ss--font-size-13">
                            {{ $item->department_name }}
                        </td>
                    <tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
{{ $list->links('manager-work::project.member.helpers.paging') }}
