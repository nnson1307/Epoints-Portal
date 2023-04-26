<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('TÊN NHÂN VIÊN')</th>
            <th class="tr_thead_list">@lang('TÊN CHI NHÁNH')</th>
            <th class="tr_thead_list">@lang('HOA HỒNG SẢN PHẨM')</th>
            <th class="tr_thead_list">@lang('HỆ SỐ HOA HỒNG')</th>
            <th class="tr_thead_list">@lang('HOA HỒNG THỰC LÃNH')</th>
            <th class="tr_thead_list">@lang('NGÀY NHẬN')</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $item)
                <tr>
                    <td>{{$item['staff_name']}}</td>
                    <td>{{$item['branch_name']}}</td>
                    <td>{{number_format($item['staff_commission_rate'] != 0 ? floatval(floatval($item['staff_money']) / $item['staff_commission_rate']) : 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{number_format($item['staff_commission_rate'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td>{{number_format($item['staff_money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                   <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
