<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th></th>
            <th class="tr_thead_list text-center">@lang('Tỷ lệ thành công')</th>
            <th class="tr_thead_list text-center">@lang('Tỷ lệ thất bại')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($dataList2) > 0)
            <tr>
                <td>@lang('Tổng cộng')</td>
                <td class="text-center">{{round( (($totalSuccess/$totalHistory) * 100), 2)}}%</td>
                <td class="text-center">{{round((($totalFail/$totalHistory) * 100), 2)}}%</td>
            </tr>
            @foreach($dataList2 as $v)
                <tr>
                    <td>{{$v['date']}}</td>
                    <td class="text-center">{{round((($v['success']/$v['total']) * 100), 2)}}%</td>
                    <td class="text-center">{{round((($v['fail']/$v['total']) * 100), 2)}}%</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
