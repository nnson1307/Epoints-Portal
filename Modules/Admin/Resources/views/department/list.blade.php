<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--font-size-th ss--nowrap">
            <th>#</th>
            <th>{{__('TÊN PHÒNG BAN')}}</th>
{{--            <th>{{__('THÔNG TIN NHÁNH CHA')}}</th>--}}
{{--            <th class="ss--text-center">@lang('NGƯỜI QUẢN LÝ')</th>--}}
            <th class="ss--text-center">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center">{{__('NGÀY TẠO')}}</th>
            <th class="ss--text-center">{{__('Hành động')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $key=> $item)
                <tr class="ss--font-size-13">
                    @if(isset($page))
                        <td class="ss--font-size-13 ss--nowrap">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="ss--font-size-13 ss--nowrap">{{$key+1}}</td>
                    @endif
                    <td class="ss--nowrap">{{$item['department_name']}}</td>
{{--                    <td>--}}
{{--                        {{$item['branch_name']}}--}}
{{--                    </td>--}}
{{--                    <td class="ss--text-center">--}}
{{--                        {{$item['staff_title_name'] . ' - ' . $item['staff_name']}}--}}
{{--                    </td>--}}
                    <td class="ss--text-center">
                        @if(in_array('admin.department.change-status',session('routeList')))
                            @if($item['is_inactive'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="Department.changeStatus(this, '{!! $item['department_id'] !!}', 'publish')"
                                           disabled
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="Department.changeStatus(this, '{!! $item['department_id'] !!}', 'unPublish')"
                                           disabled
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if($item['is_inactive'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="" disabled>
                                    <span></span>
                                </label>
                            </span>
                            @else
                                {{--<button class="m-badge  m-badge--danger m-badge--wide">Tạm ngưng</button>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           class="manager-btn" name="" disabled>
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td class="ss--text-center ss--nowrap">{{ date_format($item['created_at'], 'd/m/Y')}}</td>
                    <td class="ss--text-center ss--nowrap">
                        @if(in_array('admin.department.submit-edit',session('routeList')))
                            <button onclick="Department.edit({{$item['department_id']}})"
                                    class="classss m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Cập nhật">
                                <i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('admin.department.remove',session('routeList')))
                            <button onclick="Department.remove(this, '{{ $item['department_id'] }}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Xóa">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}