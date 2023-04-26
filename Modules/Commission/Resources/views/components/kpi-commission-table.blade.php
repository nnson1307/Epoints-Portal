<div class="form-group">
    <a class="btn  btn-sm m-btn--icon color" href="javascript:void(0)" onclick="commission.addKpiTemplate()">
        <i class="la la-plus"></i> @lang('Thêm điều kiện')
    </a>
</div>

<div class="form-group table-responsive">
    <table class="table m-table m-table--head-bg-default" id="kpi-table">
        <thead class="bg">
            <tr>
                <th class="tr_thead_list">@lang('Kpi tối thiểu (%)')</th>
                <th class="tr_thead_list">@lang('Kpi tối đa (%)')</th>
                <th class="tr_thead_list">@lang('Hoa hồng cho nhân viên')</th>
                <th>@lang('Hành động')</th>
            </tr>
        </thead>

        <tbody>
            <tr class="tr_template">
                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="min-kpi-1" name="min-kpi" value="0">

                        <div class="input-group-append">
                            <span class="input-group-text text_type_default">%</span>
                        </div>
                    </div>
                    <span class="error_valid_min_kpi_1 color_red"></span>
                    <input type="hidden" class="number" value="1">
                </td>

                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="max-kpi-1" name="max-kpi" value="0">

                        <div class="input-group-append">
                            <span class="input-group-text text_type_default">%</span>
                        </div>
                    </div>
                    <span class="error_valid_max_kpi_1 color_red"></span>
                </td>

                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="kpi-commission-value-1" name="kpi-commission-value" value="0">

                        <div class="input-group-append">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="config-operation-1" checked
                                           value="0"> @lang('VNĐ')
                                </label>
{{--                                <label class="btn btn-secondary">--}}
{{--                                    <input type="radio" name="config-operation-1" value="1">--}}
{{--                                    %--}}
{{--                                </label>--}}
                            </div>
                        </div>
                    </div>
                    <span class="error_valid_commission_value_1 color_red"></span>
                </td>
                <td>
                    <a href="javascript:void(0)" onclick="commission.removeTr(this, 'kpi')"
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="Delete">
                        <i class="la la-trash"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="form-group m-form__group">
        <span class="error_table_template color_red"></span>
    </div>
</div>

