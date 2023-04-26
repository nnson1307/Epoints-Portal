<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">{{__('STT')}}</th>
            <th class="ss--font-size-th">{{__('NHÓM CHỨC NĂNG')}}</th>
            <th class="ss--font-size-th">{{__('CHỨC NĂNG')}}</th>
            <th class="ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--font-size-th"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr class="ss--font-size-13 ss--nowrap">
{{--                    <td>{{$item['position']}}</td>--}}
                    <td>{{$key + 1}}</td>
                    <td>{{$item['menu_category_name']}}</td>
                    <td>{{$item['admin_menu_name']}}</td>
                    <td>
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onchange="listMenu.changeStatus('{{$item['admin_menu_function_id']}}', 0)"
                                           name="" checked>
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onchange="listMenu.changeStatus('{{$item['admin_menu_function_id']}}', 1)"
                                           name="">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td>
                        <button onclick="listMenu.remove({{$item['admin_menu_function_id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="Delete">
                            <i class="la la-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}