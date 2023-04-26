<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('Hành động')}}</th>
            <th class="ss--font-size-th">{{__('Tên loại công việc')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('Icon')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Trạng thái')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Người tạo')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('Ngày tạo')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($list))
            @foreach ($list as $key=>$item)
                <tr>
                    <td class="ss--font-size-13">{{ ($key+1) }}</td>
                    <td class="">
                        <button onclick="TypeWork.edit('{{$item['manage_type_work_id']}}')"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                        </button>
                        <button onclick="TypeWork.remove(this, '{{ $item['manage_type_work_id'] }}')"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="{{__('Xóa')}}"><i class="la la-trash"></i>
                        </button>
                    </td>
                    <td class="ss--font-size-13"><a href="javascript:void(0);" onclick="TypeWork.view('{{ $item['manage_type_work_id'] }}')">{{ $item['manage_type_work_name'] }}</a></td>
                    <td class="ss--font-size-13 ss--text-center">
                        <img src="{{ $item['manage_type_work_icon'] != '' ?($item['manage_type_work_icon']): asset('static/backend/images/service-card/default/hinhanh-default3.png') }}" widht="40px" height="40px" alt="">
                    </td>
                    <td class="ss--text-center ss--font-size-13">
                        {{-- @if(in_array('ticket.queue.change-status',session('routeList'))) --}}
                            @if ($item['is_active'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="TypeWork.changeStatus(this, '{!! $item['manage_type_work_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="TypeWork.changeStatus(this, '{!! $item['manage_type_work_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        {{-- @else
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
                        @endif --}}
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{$item->staff_created->full_name}}</td>
                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($item['created_at']), 'd/m/Y H:i') }}</td>
                   
                    
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $list->links('helpers.paging') }}