
<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
            <tr>
                <th class="ss--font-size-th ss--text-center">{{ __('Mã ticket') }}</th>
                <th class="ss--font-size-th">{{ __('Tiêu đề') }}</th>
                <th class="ss--font-size-th">{{ __('Yêu cầu') }}</th>
                <th class="ss--font-size-th">{{ __('Loại yêu cầu') }}</th>
                <th class="ss--text-center ss--font-size-th">{{ __('Thời gian tạo') }}
                </th>
                <th class="ss--font-size-th">{{ __('Người tạo') }}
                </th>
{{--                <th class="ss--font-size-th">{{ __('Người xử lý') }}--}}
{{--                </th>--}}
                <th class="ss--text-center ss--font-size-th">{{ __('Trạng thái') }}</th>
            </tr>
        </thead>
        <tbody>
        @if(isset($LIST_TICKET))
            @foreach ($LIST_TICKET as $key => $value)
                <tr>
                    <td>
                        <a class="m-link" style="color:#464646" title="{{__('Chi tiết')}}" target="_blank"
                           href="{{route('ticket.detail', $value['ticket_id'])}}">
                            {{$value['ticket_code']}}
                        </a>
                    </td>
                    <td>{{$value['title']}}</td>
                    <td>{{$value['ticket_request_type_name']}}</td>
                    <td>{{$value['ticket_issue_name']}}</td>
                    <td class="text-center">{{date("d/m/Y",strtotime($value['created_at']))}}</td>
                    <td>{{$value['creator']}}</td>
{{--                    <td>{{$value['creator']}}</td>--}}
                    <td class="text-center">
                        @if ($value['ticket_status_id'] == 1)
                            <span class="m-badge m-badge--success m-badge--wide"
                                  style="width: 100%">{{ $value['ticket_status_name'] }}</span>
                        @elseif ($value['ticket_status_id'] == 2)
                            <span class="m-badge m-badge--second m-badge--wide"
                                  style="width: 100%">{{ $value['ticket_status_name'] }}</span>
                        @elseif ($value['ticket_status_id'] == 3)
                            <span class="m-badge m-badge--warning m-badge--wide"
                                  style="width: 100%">{{ $value['ticket_status_name'] }}</span>
                        @elseif ($value['ticket_status_id'] == 4)
                            <span class="m-badge m-badge--primary m-badge--wide"
                                  style="width: 100%">{{ $value['ticket_status_name'] }}</span>
                        @elseif ($value['ticket_status_id'] == 5)
                            <span class="m-badge m-badge--danger m-badge--wide"
                                  style="width: 100%">{{ $value['ticket_status_name'] }}</span>
                        @elseif ($value['ticket_status_id'] == 6)
                            <span class="m-badge m-badge--primary m-badge--wide"
                                  style="width: 100%">{{ $value['ticket_status_name'] }}</span>
                        @elseif ($value['ticket_status_id'] == 7)
                            <span class="m-badge m-badge--metal m-badge--wide"
                                  style="width: 100%">{{ $value['ticket_status_name'] }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>