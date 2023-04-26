<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN CHỨC VỤ')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('MÃ CHỨC VỤ')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGÀY TẠO')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr>
                    <td class="ss--font-size-13">{{ ($key+1) }}</td>
                    <td class="ss--font-size-13">{{ $item['staff_title_name'] }}</td>
                    <td class="ss--font-size-13 ss--text-center">{{ $item['staff_title_code'] }}</td>
                    <td class="ss--text-center ss--font-size-13">
                        @if(in_array('admin.staff-title.change-status', session()->get('routeList')))
                            @if ($item['is_active'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="staffTitle.changeStatus(this, '{!! $item['staff_title_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="staffTitle.changeStatus(this, '{!! $item['staff_title_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_active'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
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
                        @if($item['is_system'] != 1)
                            @if(in_array('admin.staff-title.submitedit', session()->get('routeList')))
                                <button onclick="staffTitle.edit({{$item['staff_title_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Cập nhật"><i class="la la-edit"></i>
                                </button>
                            @endif
                            @if(in_array('admin.staff-title.remove', session()->get('routeList')))
                                <button onclick="staffTitle.remove(this, '{{ $item['staff_title_id'] }}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Xóa"><i class="la la-trash"></i>
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}