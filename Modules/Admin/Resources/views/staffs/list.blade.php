<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="img_sub"></th>
            {{--<th class="tr_thead_list">{{__('Nhân viên')}}</th>--}}
            <th class="tr_thead_list">{{__('Tài khoản')}}</th>
            <th class="tr_thead_list">{{__('Chức vụ')}}</th>
            <th class="tr_thead_list">{{__('Chi nhánh')}}</th>
            {{--<th class="tr_thead_list">Phòng ban</th>--}}
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $color = ["success", "brand", "danger", "accent", "warning", "metal", "primary", "info"];
        ?>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                @php($num = rand(0,7))
                <tr>
                    @if(isset($page))
                        <td class="text_middle">{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td class="text_middle">{{$key+1}}</td>
                    @endif
                    <td>
                        <div class="m-list-pics m-list-pics--sm">
                            @if($item['staff_avatar']!=null)
                                <div class="m-card-user m-card-user--sm">
                                    <div class="m-card-user__pic">
                                        <img src="{{$item['staff_avatar']}}"
                                             onerror="this.onerror=null;this.src='https://placehold.it/40x40/00a65a/ffffff/&text=' + '{{substr(str_slug($item['name']),0,1)}}';"
                                             class="m--img-rounded m--marginless" alt="photo" width="40px"
                                             height="40px">
                                    </div>
                                    <div class="m-card-user__details">
                                        <a href="{{route('admin.staff.show', $item['staff_id'])}}" class="m-card-user__name line-name font-name">
                                            {{$item['name']}}</a>
                                        <span class="m-card-user__email font-sub">
                                                {{$item['department_name']}}
                                            </span>
                                    </div>
                                </div>
                            @else
                                <span style="width: 150px;">
                                        <div class="m-card-user m-card-user--sm">
                                            <div class="m-card-user__pic">

                                                <div class="m-card-user__no-photo m--bg-fill-{{$color[$num]}}">
                                                    <span>
                                                        {{substr(str_slug($item['name']),0,1)}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="m-card-user__details">
                                                <a href="{{route('admin.staff.show', $item['staff_id'])}}"
                                                   class="m-card-user__name line-name font-name">{{$item['name']}}</a>
                                                <span class="m-card-user__email font-sub">
                                                    {{$item['department_name']}}
                                                </span>
                                            </div>
                                        </div>
                                    </span>
                            @endif
                        </div>
                    </td>
                    <td class="text_middle">{{$item['account']}}</td>
                    <td class="text_middle">{{$item['staff_title_name']}}</td>
                    <td class="text_middle">{{$item['branch_name']}}</td>
                    <td class="text_middle">
                        @if(in_array('admin.staff.change-status',session('routeList')))
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="staff.changeStatus(this, '{!! $item['staff_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="staff.changeStatus(this, '{!! $item['staff_id'] !!}', 'unPublish')"
                                           class="manager-btn">
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
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td style="width: 100px;" class="text_middle">
                        @if(in_array('admin.staff.edit',session('routeList')))
                            <a href="{{route('admin.staff.edit',array ('id'=>$item['staff_id']))}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="View">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('admin.staff.remove',session('routeList')))
                            <button onclick="staff.remove(this, {{$item['staff_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Delete">
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
