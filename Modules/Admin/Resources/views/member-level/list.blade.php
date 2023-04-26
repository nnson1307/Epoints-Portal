<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Cấp độ')}}</th>
            <th class="tr_thead_list text-center">{{__('Số điểm quy đổi')}}</th>
            <th class="tr_thead_list text-center">{{__('Giảm giá')}} ( % )</th>
            <th class="tr_thead_list text-center">{{__('Trạng thái')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if (isset($LIST))
            @foreach ($LIST as $key=>$item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>{{ $item['name'] }}</td>
                    <td class="text-center">{{ $item['point'] }}</td>
                    <td class="text-center">{{ $item['discount'] }}</td>
                    <td class="text-center">
                        @if(in_array('admin.member-level.change-status',session('routeList')))
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="member_level.changeStatus(this, '{!! $item['member_level_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="member_level.changeStatus(this, '{!! $item['member_level_id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(in_array('admin.member-level.submitedit',session('routeList')))
                            <button value="{{$item['member_level_id']}}"
                                    onclick="member_level.edit({{$item['member_level_id']}})"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-edit"></i>
                            </button>
                        @endif
                        @if(in_array('admin.member-level.remove',session('routeList')))
                            {{-- <button onclick="member_level.remove(this, {{$item['member_level_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Delete">
                                <i class="la la-trash"></i>
                            </button> --}}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

{{ $LIST->links('helpers.paging') }}



