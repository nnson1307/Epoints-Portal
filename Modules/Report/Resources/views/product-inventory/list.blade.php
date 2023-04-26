<div class="form-group table-responsive">
    <table class="table table-bordered m-table m-table--border-success">
        <thead>
        <tr>
            <th></th>
            <th></th>

            @if(count($listWarehouse) > 0)
                <th colspan="8" class="text-center">@lang('Tổng')</th>
                @foreach($listWarehouse as $v)
                    <th colspan="8" class="text-center">{{$v['name']}}</th>
                @endforeach
            @endif
        </tr>
        <tr>
            <th></th>
            <th></th>

            @if(count($listWarehouse) > 0)
                <th colspan="2" class="text-center">@lang('Tồn đầu kỳ')</th>
                <th colspan="2" class="text-center">@lang('Nhập')</th>
                <th colspan="2" class="text-center">@lang('Xuất')</th>
                <th colspan="2" class="text-center">@lang('Tổng tồn')</th>

                @foreach($listWarehouse as $v)
                    <th colspan="2" class="text-center">@lang('Tồn đầu kỳ')</th>
                    <th colspan="2" class="text-center">@lang('Nhập')</th>
                    <th colspan="2" class="text-center">@lang('Xuất')</th>
                    <th colspan="2" class="text-center">@lang('Tổng tồn')</th>
                @endforeach
            @endif
        </tr>
        <tr>
            <th>#</th>
            <th>@lang('Tên sản phẩm')</th>

            @if(count($listWarehouse) > 0)
                <th class="text-center">@lang('SL')</th>
                <th class="text-center">@lang('Giá trị')</th>
                <th class="text-center">@lang('SL')</th>
                <th class="text-center">@lang('Giá trị')</th>
                <th class="text-center">@lang('SL')</th>
                <th class="text-center">@lang('Giá trị')</th>
                <th class="text-center">@lang('SL')</th>
                <th class="text-center">@lang('Giá trị')</th>

                @foreach($listWarehouse as $v)
                    <th class="text-center">@lang('SL')</th>
                    <th class="text-center">@lang('Giá trị')</th>
                    <th class="text-center">@lang('SL')</th>
                    <th class="text-center">@lang('Giá trị')</th>
                    <th class="text-center">@lang('SL')</th>
                    <th class="text-center">@lang('Giá trị')</th>
                    <th class="text-center">@lang('SL')</th>
                    <th class="text-center">@lang('Giá trị')</th>
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <th>
                        {{isset($page) ? ($page-1)*10 + $key+1 : $key+1}}
                    </th>
                    <td>{{$item['product_name']}}</td>
                    {{--Data của tổng--}}
                    <td class="text-center">{{$item['allBeginInventory']}}</td>
                    <td class="text-center">
                        {{number_format($item['allBeginInventoryValue'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">{{$item['allInput']}}</td>
                    <td class="text-center">
                        {{number_format($item['allInputValue'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">{{$item['allOutput']}}</td>
                    <td class="text-center">
                        {{number_format($item['allOutputValue'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td class="text-center">{{$item['allInventory']}}</td>
                    <td class="text-center">
                        {{number_format($item['allInventoryValue'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>

                    @foreach($item['data_warehouse'] as $wh)
                        <td class="text-center">{{$wh['begin_inventory']}}</td>
                        <td class="text-center">
                            {{number_format($wh['begin_inventory_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                        <td class="text-center">{{$wh['total_input']}}</td>
                        <td class="text-center">
                            {{number_format($wh['total_input_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                        <td class="text-center">{{$wh['total_output']}}</td>
                        <td class="text-center">
                            {{number_format($wh['total_output_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                        <td class="text-center">{{$wh['inventory']}}</td>
                        <td class="text-center">
                            {{number_format($wh['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
