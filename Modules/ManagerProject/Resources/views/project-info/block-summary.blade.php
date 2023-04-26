
<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th ss--text-center">{{__('Phòng ban')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Tổng thành viên')}}</th>
            <th class="ss--font-size-th  ss--text-center">{{__('Tổng công việc')}}</th>
            @foreach($info['summary']['listStatus'] as $key => $val)
                <th class="ss--font-size-th ss--text-center">{{$val['manage_status_name']}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($info['summary']['listWorkGroupByDepartment'] as $key => $val)
            <tr class="ss--font-size-13 ss--nowrap">
                <td class="ss--font-size-th ss--text-center">{{$key}}</td>
                <td class="ss--font-size-th ss--text-center">
                    @if(collect($info['summary']['listStaffProject'])->where('department_name','=', $key)->count() != 0)
                        <a href="{{route('manager-project.member',['id' => $info['project_id'],'department_id' => $info['summary']['listProjectKeyDepartmentName'][$key]['department_id']])}}">
                            {{collect($info['summary']['listStaffProject'])->where('department_name','=', $key)->count()}}
                        </a>
                    @else
                        {{collect($info['summary']['listStaffProject'])->where('department_name','=', $key)->count()}}
                    @endif
                </td>
                <td class="ss--font-size-th ss--text-center">
                    @if(count($val) != 0)
                        <a href="{{ route('manager-project.work', ['manage_project_id' => $val[0]['project_id'],'none_time' => true,'department_id' => $info['summary']['listProjectKeyDepartmentName'][$key]['department_id']]) }}">
                            {{count($val)}}
                        </a>
                    @else
                        {{count($val)}}
                    @endif

                </td>
                @foreach($info['summary']['listStatus'] as $keyStatus => $valStatus)
                    <th class="ss--font-size-th ss--text-center">{{collect($info['summary']['listWorkGroupByDepartment'][$key])->where('status_id','=', $valStatus['manage_status_id'])->count()}}</th>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>