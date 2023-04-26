<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th>@lang('chathub::setting.index.AVATAR')</th>
            <th>@lang('chathub::setting.index.CHANNEL_NAME')</th>
            <th>@lang('chathub::setting.index.LINK_CHANNEL')</th>
            <th>@lang('chathub::setting.index.MANIPULATION')</th>
            {{-- <th>@lang('chathub::setting.index.DIALOGFLOW')</th> --}}
            <th>@lang('chathub::setting.index.MENU')<br>@lang('chathub::setting.index.ACTIVE')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @foreach($channelList as $channel)
            <tr>
                <th>
                    <span class="kt-userpic kt-userpic--circle">
                        <img src="{{$channel['avatar']}}" alt="image" height="50px">
                    </span>
                </th>
                <td>{{$channel['name']}}</td>
                <td><a href="{{$channel['link']}}" target="_blank">{{$channel['link']}}</a></td>
                <td>
                    @if($channel['is_subscribed']==0)
                        <a href="javascript:void(0);" onclick="channel.subscribeChannel({{$channel['channel_id']}})" class="btn btn-success">@lang('chathub::setting.index.SUBCRIBE')</a>
                    @else
                        <a href="javascript:void(0);" onclick="channel.unsubscribeChannel({{$channel['channel_id']}})" class="btn btn-warning">@lang('chathub::setting.index.UNSUBCRIBE')</a>
                    @endif
                </td>
                <td>
                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                        <label>
                            <input id="show_option" name="show_option" onclick="channel.showOption({{$channel['channel_id']}})" type="checkbox" @if($channel['show_option'])checked @endif>
                            <span></span>
                        </label>
                    </span>
                </td>
                <td>
                    <button value="{{$channel['channel_id']}}"
                            onclick="channel.showPopupEdit({{$channel['channel_id']}})"
                            class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                            title="{{__('Sửa')}}"
                            id="edit1">
                        <i class="la la-edit"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
