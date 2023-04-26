<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">@lang('Chi nhánh')</th>
            <th class="tr_thead_list text-center">@lang('Số giờ làm dự kiến/thực tế (giờ)')</th>
            <th class="tr_thead_list text-center">@lang('Tỉ lệ số giờ vượt dự kiến')</th>
            <th class="tr_thead_list text-center">@lang('Ngân sách lương dự kiến/ thực tế (VND)')</th>
            <th class="tr_thead_list text-center">@lang('Tỉ lệ ngân sách vượt dự kiến')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>{{$item['branch_name']}}</td>
                    <td class="text-center">
                        {{number_format($item['estimate_time'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        /
                        {{number_format($item['total_working_time'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">
                        {{number_format($item['ratio_expected_hour'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} %
                    </td>
                    <td class="text-center">
                        {{number_format($item['estimate_money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        /
                        {{number_format($item['total_salary'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">
                        {{number_format($item['ratio_expected_salary'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} %
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}