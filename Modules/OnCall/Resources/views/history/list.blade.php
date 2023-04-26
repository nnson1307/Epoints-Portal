<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('SỐ NGƯỜI GỌI')</th>
            <th class="tr_thead_list">@lang('SĐT NGƯỜI NHẬN')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN BẮT ĐẦU')</th>
            <th class="tr_thead_list">@lang('THỜI GIAN KẾT THÚC')</th>
            <th class="tr_thead_list">@lang('THỜI LƯỢNG')</th>
            <th class="tr_thead_list">@lang('LOẠI CUỘC GỌI')</th>
            <th class="tr_thead_list">@lang('TÊN NGƯỜI GỌI')</th>
            <th class="tr_thead_list">@lang('TÊN NGƯỜI NHẬN')</th>
            <th class="tr_thead_list">@lang('NGUỒN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('NỘI DUNG CHĂM SÓC')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if ($item['history_type'] == "out")
                            {{$item['extension_number']}}
                        @else
                            @if(in_array('popup-care-oncall', session()->get('routeList')))
                                <a class="m-link" style="color:#464646" title="{{__('Chi tiết')}}"
                                   onclick="historyGetModal('{{$item['history_id']}}','{{$item['source_code']}}', '{{$item['object_id']}}', '{{$item['object_id_call']}}', '{{$item['object_phone']}}')"
                                   href="javascript:void(0)">
                                    {{$item['object_phone']}}
                                </a>
                            @else
                                {{$item['object_phone']}}
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($item['history_type'] == "out")
                            @if(in_array('popup-care-oncall', session()->get('routeList')))
                                <a class="m-link" style="color:#464646" title="{{__('Chi tiết')}}"
                                   onclick="historyGetModal('{{$item['history_id']}}','{{$item['source_code']}}', '{{$item['object_id']}}', '{{$item['object_id_call']}}', '{{$item['object_phone']}}')"
                                   href="javascript:void(0)">
                                    {{$item['object_phone']}}
                                </a>
                            @else
                                {{$item['object_phone']}}
                            @endif
                        @else
                            {{$item['extension_number']}}
                        @endif
                    </td>
                    <td>
                        @if ($item['start_time'] != null)
                            {{\Carbon\Carbon::parse($item['start_time'])->format('d/m/Y H:i:s')}}
                        @endif
                    </td>
                    <td>
                        @if ($item['end_time'] != null)
                            {{\Carbon\Carbon::parse($item['end_time'])->format('d/m/Y H:i:s')}}
                        @endif
                    </td>
                    <td>{{$item['total_reply_time']}}</td>
                    <td>
                        @if ($item['history_type'] == "out")
                            @lang('Cuộc gọi đi')
                        @else
                            @lang('Cuộc gọi đến')
                        @endif
                    </td>
                    <td>
                        @if ($item['history_type'] == "out")
                            {{$item['staff_name']}}
                        @else
                            {{$item['object_name']}}
                        @endif
                    </td>
                    <td>
                        @if ($item['history_type'] == "out")
                            {{$item['object_name']}}
                        @else
                            {{$item['staff_name']}}
                        @endif
                    </td>
                    <td>{{$item['source_name']}}</td>
                    <td>
                        @if ($item['source_code'] == "lead")
                            {{$item['content_care_lead']}}
                        @elseif ($item['source_code'] == "deal")
                            {{$item['content_care_deal']}}
                        @endif
                    </td>
                    <td>
                        @if ($item['status'] == 0)
                            @lang('Thất bại')
                        @else
                            @lang('Thành công')
                        @endif
                    </td>
                    <td>
                        @if(in_array('oncall.history.show', session('routeList')))
                            <a href="{{route('oncall.history.show', $item['history_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chi tiết')">
                                <i class="la la-eye"></i>
                            </a>
                        @endif
                        @if($item['link_record'] != null)
                            <a href="{{$item['link_record']}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-arrow-circle-down"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
