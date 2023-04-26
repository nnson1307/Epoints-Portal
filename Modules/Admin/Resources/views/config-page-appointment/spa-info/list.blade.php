<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tên đơn vị')}}</th>
            <th class="tr_thead_list">{{__('Mã đại diện')}}</th>
            <th class="tr_thead_list">{{__('Số điện thoại')}}</th>
            <th class="tr_thead_list">{{__('Địa chỉ')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['code']}}</td>
                    <td>{{$item['phone']}}</td>
                    <td>{{$item['address'].', '.$item['district_type'].' '.$item['district_name'].', '.
                        $item['province_type'].' '.$item['province_name']}}</td>

                    <td>
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="spa_info.changeStatus(this, '{!! $item['id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="spa_info.changeStatus(this, '{!! $item['id'] !!}', 'unPublish')"
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                        @endif
                    </td>
                    <td>

                        <a href="{{route('admin.config-page-appointment.edit-info',$item['id'])}}"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>

                        </a>
                        <button onclick="spa_info.remove(this, {{$item['id']}})"
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
