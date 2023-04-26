<div class="form-group table-responsive">
    <table class="table m-table m-table--head-bg-default" id="kpi-table">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('Kpi tối thiểu (%)')</th>
            <th class="tr_thead_list">@lang('Kpi tối đa (%)')</th>
            <th class="tr_thead_list">@lang('Hoa hồng cho nhân viên')</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($item['commissionConfig']) && count($item['commissionConfig']) > 0)
            @foreach($item['commissionConfig'] as $v)
                <tr class="tr_template">
                    <td>
                        <div class="input-group" style="padding-left: 0px;">
                            <input type="text" class="form-control m-input numeric_child" disabled
                                   value="{{number_format($v['min_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">

                            <div class="input-group-append">
                                <span class="input-group-text text_type_default">%</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="input-group" style="padding-left: 0px;">
                            <input type="text" class="form-control m-input numeric_child" disabled
                                   value="{{$v['max_value'] != null ? number_format($v['max_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0) : null}}">

                            <div class="input-group-append">
                                <span class="input-group-text text_type_default">%</span>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="input-group" style="padding-left: 0px;">
                            <input type="text" class="form-control m-input numeric_child" disabled
                                   value="{{number_format($v['commission_value'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">

                            <div class="input-group-append">
                                <span class="input-group-text text_type_default">
                                    @if ($v['config_operation'] == 0)
                                        @lang('VNĐ')
                                    @elseif($v['config_operation'] == 1)
                                        %
                                    @endif
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
    <div class="form-group m-form__group">
        <span class="error_table_template color_red"></span>
    </div>
</div>

