@if ($list)
    <div class="table-responsive">
        <table class="table table-striped m-table ss--header-table ss--nowrap">
            <thead>
            <tr>
                <th class="ss--text-center  ss--font-size-th">{{__('Hành động')}}</th>
                <th class="ss--font-size-th">{{__('Tên chiến dịch')}}</th>
                <th class="ss--font-size-th">{{__('Tên OA')}}</th>
                <th class="ss--font-size-th">{{__('Loại chiến dịch')}}</th>
                <th class="ss--text-center ss--font-size-th">{{__('Người tạo')}}</th>
                <th class="ss--text-center ss--font-size-th">{{__('Số thông báo gửi')}}</th>
                <th class="ss--text-center ss--font-size-th">{{__('Thông báo gửi thành công')}}</th>
                <th class="ss--text-center ss--font-size-th">{{__('Thời gian tạo')}}</th>
                <th class="ss--text-center ss--font-size-th">{{__('Thời gian gửi')}}</th>
                <th class="ss--text-center ss--font-size-th">{{__('Hoạt động')}}</th>
                <th class="ss--text-center ss--font-size-th">{{__('Trạng thái')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--text-center">
                        @if(($item->status == "new"))
                            @if(!($item->is_now == 1 && $item->is_actived == 1))
                                <a href="{{ route('zns.campaign.edit',$item->zns_campaign_id) }}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Cập nhật')}}"><i class="la la-edit"></i>
                                </a>
                            @endif
                        @endif
                        @if($item->status == "new")
                            @if(!($item->is_now == 1 && $item->is_actived == 1))
                                <a href="javascript:void(0);"
                                   onclick="Campaign.removeAction({{$item->zns_campaign_id}})"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Xóa')}}"><i class="la la-trash"></i>
                                </a>
                            @endif
                        @endif
                        <a href="javascript:void(0);" onclick="Campaign.cloneAction({{$item->zns_campaign_id}})"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                           title="{{__('sao chép')}}"><i class="fa fa-clone"></i>
                        </a>
                    </td>
                    <td class="ss--font-size-13">
                        <a href="{{ route('zns.campaign.view',$item->zns_campaign_id) }}"
                           class="text-primary"
                           title="{{ route('zns.campaign.view',$item->zns_campaign_id) }}">
                            {{ $item->name }}
                        </a>
                    </td>
                    <td class="ss--font-size-13">{{ "admin" }}</td>
                    <td class="ss--font-size-13">
                        @if($item->campaign_type == "zns")
                            {{ __('Zalo template API') }}
                        @elseif($item->campaign_type == "follower")
                            {{ __('Zalo Follower API') }}
                        @elseif($item->campaign_type == "broadcast")
                            {{ __('Zalo BroadCast') }}
                        @endif
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{ $item->created_by_full_name }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item->countSend() }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ $item->countSendSuccess() }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ date_format(new DateTime($item->created_at), 'd/m/Y H:i') }}</td>
                    <td class="ss--text-center ss--font-size-13">{{ date_format(new DateTime($item->time_sent), 'd/m/Y H:i') }}</td>
                    <td class="ss--text-center ss--font-size-13">
                        @if ($item->status == "sent" || ($item->status == "new" && $item->is_actived == 1 && $item->is_now == 1))
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox" class="manager-btn" name="is_actived" disabled{{$item->is_actived == 1 ? ' checked':''}}>
                                    <span></span>
                                </label>
                            </span>
                        @elseif($item->status == "new")
                            @if ($item->is_actived)
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label class="ss--switch">
                                    <input type="checkbox"
                                           onclick="Campaign.changeStatus(this, '{!! $item->zns_campaign_id !!}', 'publish')"
                                           checked class="manager-btn" name="is_actived">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label class="ss--switch">
                                        <input type="checkbox"
                                               onclick="Campaign.changeStatus(this, '{!! $item->zns_campaign_id !!}', 'unPublish')"
                                               class="manager-btn" name="is_actived">
                                        <span></span>
                                    </label>
                                </span>
                            @endif
                        @endif
                    </td>
                    <td class="ss--text-center ss--font-size-13">{{ isset($campaign_status[$item->status])?$campaign_status[$item->status]:''  }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $list->links('helpers.paging') }}
@endif
