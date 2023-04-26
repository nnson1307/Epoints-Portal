<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th" id="th_title">
                @lang('admin::notification.index.table.header.TITLE')
            </th>
            <th class="ss--font-size-th" id="th_is_sent">
                @lang('admin::notification.index.table.header.NOTIFICATIONS_IS_SENT')
            </th>
            <th class="ss--font-size-th" id="th_read_notification">
                @lang('admin::notification.index.table.header.RATE_READ_NOTIFICATION')
            </th>
            <th class="ss--font-size-th" id="td_send_time">
                @lang('admin::notification.index.table.header.SEND_TIME')
            </th>
            <th class="ss--font-size-th" id="th_active">
                @lang('admin::notification.index.table.header.ACTIVE')
            </th>
            <th class="ss--font-size-th" id="th_is_send">
                @lang('admin::notification.index.table.header.IS_SEND')
            </th>
            <th class="ss--font-size-th">
                @lang('admin::notification.index.table.header.ACTION')
            </th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $noti)
                @if($noti['title'] == null)
                    @continue
                @endif
                <tr>
                    <td>
                        <p title="{{ $noti['title'] }}">
                            {{ subString($noti['title']) }}
                        </p>
                    </td>
                    <td>
                        @if(isset($notiCount[$noti['notification_detail_id']]))
                            {{ $notiCount[$noti['notification_detail_id']]['total'] }}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        @if(isset($notiCount[$noti['notification_detail_id']]))
                            {{ ceil( ($notiCount[$noti['notification_detail_id']]['is_read'] * 100) / $notiCount[$noti['notification_detail_id']]['total'] ) }}
                            %
                        @else
                            0%
                        @endif
                    </td>
                    <td id="time-{{ $noti['notification_detail_id'] }}">
                        @if($noti['send_at'] != null)
                            {{ $noti['send_at'] }}
                        @else
                            @php
                                $time_type = null;
                                if($noti['schedule_value_type'] == 'hours') {
                                    $time_type = 'Giờ';
                                } elseif($noti['schedule_value_type'] == 'minute') {
                                    $time_type = 'Phút';
                                } elseif($noti['schedule_value_type'] == 'day') {
                                    $time_type = 'Ngày';
                                }
                                echo $noti['schedule_value'].' '.$time_type;
                            @endphp
                        @endif
                    </td>
                    <td>
                        {{--                        <span class="kt-switch kt-switch--success">--}}
                        {{--                                    <label>--}}
                        {{--                                        <input type="checkbox" class="is_actived"--}}
                        {{--                                               data-id="{{ $noti['notification_detail_id'] }}"--}}
                        {{--                                               data-non-specific-value="{{ $noti['schedule_value'] }}"--}}
                        {{--                                               data-non-specific-type="{{ $noti['schedule_value_type'] }}"--}}
                        {{--                                               @if($noti['queue_id'] == null && $noti['is_actived'] == 1) disabled--}}
                        {{--                                               @endif--}}
                        {{--                                               @if($noti['is_actived'] == 1) checked="checked" @endif--}}
                        {{--                                        >--}}
                        {{--                                        <span></span>--}}
                        {{--                                    </label>--}}
                        {{--                                </span>--}}
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn is_actived"
                                           data-id="{{ $noti['notification_detail_id'] }}"
                                           data-non-specific-value="{{ $noti['schedule_value'] }}"
                                           data-non-specific-type="{{ $noti['schedule_value_type'] }}"
                                           @if($noti['queue_id'] == null && $noti['is_actived'] == 1) disabled
                                           @endif
                                           @if($noti['is_actived'] == 1) checked="checked" @endif
                                    >
                                    <span></span>
                                </label>
                         </span>
                    </td>
                    <td id="status-{{ $noti['notification_detail_id'] }}">
                        @if($noti['send_status'] == 'sent')
                            @lang('admin::notification.index.search.IS_SEND.SENT')
                        @elseif($noti['send_status'] == 'pending')
                            @lang('admin::notification.index.search.IS_SEND.WAIT')
                        @elseif($noti['send_status'] == 'not')
                            @lang('admin::notification.index.search.IS_SEND.DONT_SEND')
                        @endif
{{--                        @if($noti['queue_id'] == null && $noti['is_actived'] == 1)--}}
{{--                            @lang('admin::notification.index.search.IS_SEND.SENT')--}}
{{--                        @elseif($noti['queue_send_at'] != null && $noti['is_actived'] == 1)--}}
{{--                            @lang('admin::notification.index.search.IS_SEND.WAIT')--}}
{{--                        @elseif($noti['is_actived'] == 0)--}}
{{--                            @lang('admin::notification.index.search.IS_SEND.DONT_SEND')--}}
{{--                        @endif--}}
                    </td>
                    <td>
                        <div class="kt-portlet__head-toolbar">
                            <div class="btn-group" role="group">
                                <button id="btnGroupVerticalDrop1" type="button"
                                        class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    {{__('Hành động')}}
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                    <a class="dropdown-item" href="{{route('admin.notification.detail', $noti['notification_detail_id'])}}">
                                        <i class="la la-eye"></i>
                                        <span class="kt-nav__link-text kt-margin-l-5">
                                                {{__('admin::notification.index.table.BTN_DETAIL')}}
                                            </span>
                                    </a>
{{--                                    @include('helpers.button', ['button' => [--}}
{{--                                                'route' => '',--}}
{{--                                                 'html' => '<a href="'.route('admin.notification.detail', $noti['notification_detail_id']).'" class="dropdown-item">'--}}
{{--                                                     .'<i class="la la-eye"></i>'--}}
{{--                                                     .'<span class="kt-nav__link-text kt-margin-l-5">'.__('admin::notification.index.table.BTN_DETAIL').'</span>'.--}}
{{--                                                '</a>'--}}
{{--                                        ]])--}}
                                    @if($noti['send_status'] == 'pending')
                                        <a class="dropdown-item" href="{{route('admin.notification.edit', $noti['notification_detail_id'])}}">
                                            <i class="la la-edit"></i>
                                            <span class="kt-nav__link-text kt-margin-l-5">
                                                {{__('admin::notification.index.table.BTN_EDIT')}}
                                            </span>
                                        </a>
{{--                                        @include('helpers.button', ['button' => [--}}
{{--                                                    'route' => 'admin.notification.edit',--}}
{{--                                                         'html' => '<a href="'.route('admin.notification.edit', $noti['notification_detail_id']).'" class="dropdown-item">'--}}
{{--                                                         .'<i class="la la-edit"></i>'--}}
{{--                                                         .'<span class="kt-nav__link-text kt-margin-l-5">'.__('admin::notification.index.table.BTN_EDIT').'</span>'.--}}
{{--                                                    '</a>'--}}
{{--                                            ]])--}}
                                    @endif
                                    <a class="dropdown-item" onclick="removeItem('{{$noti['notification_detail_id']}}')">
                                        <i class="la la-trash"></i>
                                        <span class="kt-nav__link-text kt-margin-l-5">
                                                {{__('admin::notification.index.table.BTN_DELETE')}}
                                            </span>
                                    </a>
{{--                                    @include('helpers.button', ['button' => [--}}
{{--                                               'route' => 'admin.notification.destroy',--}}
{{--                                                'html' => '<a href="javascript:void(0)" onclick="removeItem('.$noti['notification_detail_id'].')" class="dropdown-item">'--}}
{{--                                                    .'<i class="la la-trash"></i>'--}}
{{--                                                    .'<span class="kt-nav__link-text kt-margin-l-5">'.__('admin::notification.index.table.BTN_DELETE').'</span>'.--}}
{{--                                               '</a>'--}}
{{--                                       ]])--}}
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
