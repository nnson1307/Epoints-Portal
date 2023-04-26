<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('TÊN HOA HỒNG')}}</th>
            <th class="tr_thead_list">{{__('LOẠI HOA HỒNG')}}</th>
            <th class="tr_thead_list text-center">{{__('GIÁ TRỊ HOA HỒNG')}}</th>
            <th class="tr_thead_list text-center">{{__('HỆ SỐ HOA HỒNG')}}</th>
            <th class="tr_thead_list text-center">{{__('GIÁ TRỊ THỰC NHẬN')}}</th>
        </tr>
        </thead>
        <tbody>
        @if (count($LIST) > 0)
            @foreach($LIST as $k => $v)
                <tr class="tr_commission">
                    <td>
                        {{isset($page) ? ($page-1)*10 + $k+1 : $k+1}}
                    </td>
                    <td>
                        {{$v['commission_name']}}
                    </td>
                    <td>
                        @switch($v['commission_type'])
                            @case('order')
                                @lang('Hoa hồng theo đơn hàng')
                                @break
                            @case('kpi')
                                @lang('Hoa hồng theo kpi')
                                @break
                            @case('contract')
                                @lang('Hoa hồng theo hợp đồng')
                                @break
                        @endswitch
                    </td>
                    <td class="text-center">
                        {{number_format($v['number_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">
                        {{number_format($v['coefficient'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">
                        {{number_format($v['commission_money'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    {{ $LIST->links('helpers.paging') }}
</div>