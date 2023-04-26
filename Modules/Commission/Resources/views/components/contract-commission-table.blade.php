<div class="form-group">
    <a class="btn  btn-sm m-btn--icon color" href="javascript:void(0)" onclick="commission.addContractTemplate()">
        <i class="la la-plus"></i> @lang('Thêm điều kiện')
    </a>
</div>

<div class="table-responsive">
    <table class="table m-table m-table--head-bg-default" id="contract-table">
        <thead class="bg">
            <tr>
                <th class="tr_thead_list">@lang('Giá trị tối thiểu')</th>
                <th class="tr_thead_list">@lang('Giá trị tối đa')</th>
                <th class="tr_thead_list">@lang('Hoa hồng cho nhân viên')</th>
                <th>@lang('Hành động')</th>
            </tr>
        </thead>

        <tbody>
            <tr class="tr_template">
                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="min-contract-1" name="min-contract" value="0">

{{--                        <div class="input-group-append">--}}
{{--                            <span class="input-group-text text_type_default">@lang('VNĐ')</span>--}}
{{--                        </div>--}}
                    </div>

                    <span class="error_valid_min_contract_1 color_red"></span>
                    <input type="hidden" class="number" value="1">
                </td>

                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="max-contract-1" name="max-contract" value="0">

{{--                        <div class="input-group-append">--}}
{{--                            <span class="input-group-text text_type_default">@lang('VNĐ')</span>--}}
{{--                        </div>--}}
                    </div>
                    <span class="error_valid_max_contract_1 color_red"></span>
                </td>

                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="contract-commission-value-1" name="contract-commission-value" value="0">

                        <div class="input-group-append">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active label_config_operation_money_1">
                                    <input type="radio" name="config-operation-1" checked
                                           value="0"> @lang('VNĐ')
                                </label>
                                <label class="btn btn-secondary label_config_operation_percent_1">
                                    <input type="radio" name="config-operation-1" value="1">
                                    %
                                </label>
                            </div>
                        </div>
                    </div>

                    <span class="error_valid_commission_value_1 color_red"></span>
                </td>
                <td>
                    <a href="javascript:void(0)" onclick="commission.removeTr(this, 'contract')"
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
