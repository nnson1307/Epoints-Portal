<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('MÃ CA')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('THỜI GIAN BẮT ĐẦU')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('THỜI GIAN KẾT THÚC')}}</th>
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
                    <td class="ss--font-size-13">{{ $item['shift_code'] }}</td>
                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($item['start_time']), 'H:i') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($item['end_time']), 'H:i')}}</td>
                    <td class="ss--text-center ss--font-size-13">
                        @if(in_array('admin.shift.change-status',session('routeList')))
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="Shift.changeStatus(this, '{!! $item['shift_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="Shift.changeStatus(this, '{!! $item['shift_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived'])
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
                    <td class="ss--text-center ss--font-size-13">{{ date_format($item['created_at'], 'd/m/Y')}}</td>
                    <td class="pull-right">
                        @if(in_array('admin.shift.submit-edit',session('routeList')))
                            <button onclick="Shift.edit({{$item['shift_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('admin.shift.remove',session('routeList')))
                            <button onclick="Shift.remove(this, '{{ $item['shift_id'] }}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xóa')}}"><i class="la la-trash"></i>
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