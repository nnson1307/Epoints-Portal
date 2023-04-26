
<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('No')</th>
            <th class="tr_thead_list">@lang('NGÀY THỰC HIỆN')</th>
            <th class="tr_thead_list">@lang('NHÂN VIÊN CHĂM SÓC')</th>
            <th class="tr_thead_list">@lang('LOẠI CHĂM SÓC')</th>
            <th class="tr_thead_list">@lang('NỘI DUNG')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($dataCare) && count($dataCare) > 0)
            @foreach ($dataCare as $key => $item)
                <tr>
                    @if(isset($page))
                        <td class="ss--font-size-13">{{ (($page-1)*6 + $key + 1) }}</td>
                    @else
                        <td class="ss--font-size-13">{{ ($key + 1) }}</td>
                    @endif
                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>{{$item['full_name']}}</td>
                    <td>
                        @if ($item['care_type'] == 'call')
                            @lang('Gọi')
                        @elseif ($item['care_type'] == 'chat')
                            @lang('Trò chuyện')
                        @elseif ($item['care_type'] == 'email')
                            @lang('Email')
                        @endif
                    </td>
                    <td>{{$item['content']}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $dataCare->links('helpers.paging') }}