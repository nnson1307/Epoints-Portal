<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default" id="table-info">
        <thead class="bg">
        <tr>
            <th>#</th>
            <th class="tr_thead_list">@lang('THỜI GIAN THỰC HIỆN')</th>
            <th class="tr_thead_list">@lang('NHÂN VIÊN CHĂM SÓC')</th>
            <th class="tr_thead_list">@lang('LOẠI CÔNG VIỆC')</th>
            <th class="tr_thead_list">@lang('NỘI DUNG')</th>
        </tr>
        </thead>
        <tbody>
        @if(count($listCustomerCare) > 0)
            @foreach($listCustomerCare as $k => $v)
                <tr>
                    @if(isset($page))
                        <td class="text_middle">{{ ($page-1)*6 + $k+1}}</td>
                    @else
                        <td class="text_middle">{{$k+1}}</td>
                    @endif
                    {{--                                                <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>--}}
                    <td>{{$v['created_group']}}</td>
                    <td>{{$v['full_name']}}</td>
                    <td>
                        @if ($v['care_type'] == 'call')
                            @lang('Gọi')
                        @elseif ($v['care_type'] == 'chat')
                            @lang('Trò chuyện')
                        @elseif ($v['care_type'] == 'email')
                            @lang('Email')
                        @endif
                    </td>
                    <td>{!! $v['content'] !!}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $listCustomerCare->links('helpers.paging') }}