<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN NHÓM QUYỀN')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr>
{{--                    <td class="ss--font-size-13">{{ ($key+1) }}</td>--}}
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="ss--font-size-13">{{$key+1}}</td>
                    @endif
                    <td class="ss--font-size-13">{{ $item['name'] }}</td>
                    <td class="ss--text-center ss--font-size-13">
                        @if(in_array('admin.staff-title.change-status', session()->get('routeList')))
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="roleGroup.changeStatus(this, '{!! $item['id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="roleGroup.changeStatus(this, '{!! $item['id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input  type="checkbox"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input  type="checkbox"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td class="ss--text-center ss--font-size-13">
                        {{(new DateTime($item['created_at']))->format('d/m/Y')}}
                    </td>
                    <td class="pull-right">
                        @if(in_array('admin.staff-title.submitedit', session()->get('routeList')))
                            <a href="{{route('admin.authorization.edit', array('id'=>$item['id']))}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="{{__('Chỉnh quyền')}}">
                                <i class="la la-user-secret"></i></a>
                            <button onclick="roleGroup.edit({{$item['id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
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