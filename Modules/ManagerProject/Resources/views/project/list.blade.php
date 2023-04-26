<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
            <tr>
                <th class="ss--font-size-th">#</th>
                <th class="ss--font-size-th">{{ __('Hành động') }}</th>
                @if (!empty($listColumnConfig['listColumShowConfig']) > 0)
                    @foreach ($listColumnConfig['listColumShowConfig'] as $item)
                        @if($item['column_name'] == 'date_start_and_end')
                            <th class="ss--text-center ss--font-size-th">{{ __('Ngày bắt đầu') }}</th>
                            <th class="ss--text-center ss--font-size-th">{{ __('Ngày kết thúc') }}</th>
                        @else
                            <th class="ss--text-center ss--font-size-th">{{ $item['nameConfig'] }}</th>
                        @endif
                    @endforeach
                @endif

            </tr>
        </thead>
        <tbody>
            @if (isset($list))
                @foreach ($list as $key => $item)
                    <tr>
                        <td class=" ss--text-center ss--font-size-13">
                            {{($list->currentPage() - 1)*$list->perPage() + $key+1 }}</td>
                        <td class="">
                            @if(\Helper::checkIsAdmin())
                                <a href="{{ route('manager-project.project.edit', $item->manage_project_id) }}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{ __('Cập nhật') }}"><i class="la la-edit"></i>
                                </a>
                                <button onclick="Project.remove(this, '{{ $item['manage_project_id'] }}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{ __('Xóa') }}"><i class="la la-trash"></i>
                                </button>
                            @else
                                @if(isset($item['is_staff']) && $item['is_staff'] == 1)
                                    @if($item['permission'] == 'public' || ($item['permission'] == 'private') &&  in_array(\Illuminate\Support\Facades\Auth::id(),collect($item['listStaffManage'])->pluck('staff_id')->toArray()))
                                        @if($item['is_edit'] == 1)
                                            <a href="{{ route('manager-project.project.edit', $item->manage_project_id) }}"
                                                class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                                title="{{ __('Cập nhật') }}"><i class="la la-edit"></i>
                                            </a>
                                        @endif
                                        @if($item['is_deleted'] == 1)
                                            <button onclick="Project.remove(this, '{{ $item['manage_project_id'] }}')"
                                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                title="{{ __('Xóa') }}"><i class="la la-trash"></i>
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </td>
                        @if (!empty($listColumnConfig['listColumShowConfig']) > 0)
                            @foreach ($listColumnConfig['listColumShowConfig'] as $columConfig)
                                @if (!empty($columConfig['relationship']))
                                    @if ($columConfig['relationship'] == 'tags')
                                        <td class="ss--text-center ss--font-size-13">
                                            @if (count($item[$columConfig['relationship']]) > 0)
                                                @foreach ($item[$columConfig['relationship']] as $key => $tag)
                                                    @if ($key == 0)
                                                        {{ $tag['manage_tag_name'] . ', ' }}
                                                    @elseif($key == 1)
                                                        {{ $tag['manage_tag_name'] . ' ' }}
                                                    @else
                                                        ...
                                                        @break
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                    @elseif ($columConfig['relationship'] == 'manager')
                                        <td class="ss--text-center ss--font-size-13">
                                            <p>@foreach($item['listStaffManage'] as $key => $itemStaff)
                                                    @if($key == 0)
                                                        {{$itemStaff['full_name']}}
                                                    @else
                                                        , {{$itemStaff['full_name']}}
                                                    @endif
                                                @endforeach</p>
                                        </td>
                                    @elseif ($columConfig['relationship'] == 'status')
                                        <td class="ss--text-center ss--font-size-13">
                                            <p class="status_work_priority"
                                                style="background-color:{{ $item[$columConfig['relationship']]['manage_project_status_color'] }}">
                                                {{ $item[$columConfig['relationship']][$columConfig['column_name']] }}
                                            </p>
                                        </td>
                                    @else
                                        <td class="ss--text-center ss--font-size-13">
                                            {{ $item[$columConfig['relationship']] ? $item[$columConfig['relationship']][$columConfig['column_name']] : '' }}
                                        </td>
                                    @endif
                            @elseif ($columConfig['column_name'] == 'manage_project_name')
                                <td class="ss--text-center ss--font-size-13">
                                    <a
                                        href="{{ route('manager-project.project.project-info-overview', ['id' => $item->manage_project_id]) }}">{{ $item[$columConfig['column_name']] }}</a>
                                </td>
                            @elseif ($columConfig['column_name'] == 'progress')
                                <td class="ss--text-center ss--font-size-13">
                                    <div class="progress">
                                        <div class="progress-bar bg-warning progress-bar-striped"
                                            style="width:{{ $item[$columConfig['column_name']] . '%' }}">
                                            {{ $item[$columConfig['column_name']] }}</div>
                                    </div>
                                </td>
                            @elseif ($columConfig['column_name'] == 'date_complete')
                                <td class="ss--text-center ss--font-size-13">
                                    {{ $item->status->manage_project_status_id == 6 ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') : '' }}
                                </td>
                            @else
                                @if($columConfig['column_name'] == 'date_start_and_end')
{{--                                    {{dd(explode(' - ',$item[$columConfig['column_name']]))}}--}}
                                    <th class="ss--text-center ss--font-size-th">{{ !isset(explode(' - ',$item[$columConfig['column_name']])[0]) ? '' : \Carbon\Carbon::parse(explode(' - ',$item[$columConfig['column_name']])[0])->format('d/m/Y') }}</th>
                                    <th class="ss--text-center ss--font-size-th">{{ !isset(explode(' - ',$item[$columConfig['column_name']])[1]) ? '' : \Carbon\Carbon::parse(explode(' - ',$item[$columConfig['column_name']])[1])->format('d/m/Y') }}</th>
                                @else
                                    <td class="ss--text-center ss--font-size-13">
                                        @if($columConfig['column_name'] == 'permission')
                                            @if($item[$columConfig['column_name']] == 'public')
                                                {{__('Công khai')}}
                                            @else
                                                {{__('Nội bộ')}}
                                            @endif
                                        @else
                                            {{ $item[$columConfig['column_name']] }}
                                        @endif
                                    </td>
                                @endif
                            @endif
                        @endforeach
                    @endif
                <tr>
            @endforeach
        @endif
    </tbody>
</table>
</div>
{{ $list->links('manager-project::project.helpers.paging') }}
