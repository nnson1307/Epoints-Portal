<div class="form-group">
    <a class="btn  btn-sm m-btn--icon color" href="javascript:void(0)" onclick="commission.addOrderTemplate()">
        <i class="la la-plus"></i> @lang('Thêm điều kiện')
    </a>
</div>

<div class="form-group table-responsive">
    <table class="table m-table m-table--head-bg-default" id="order-table">
        <thead class="bg">
            <tr>
                <th class="tr_thead_list">@lang('Giá trị tối thiểu')</th>
                <th class="tr_thead_list">@lang('Giá trị tối đa')</th>
                <th class="tr_thead_list">@lang('Hoa hồng cho nhân viên')</th>
                <th>{{ __('Hành động') }}</th>
            </tr>
        </thead>

        <tbody>
            <tr class="tr_template">
                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="min-order-1" name="min-order" value="0">

                        <div class="input-group-append">
                            <span class="input-group-text text_type_default">@lang('VNĐ')</span>
                        </div>
                    </div>
                    <span class="error_valid_min_value_1 color_red"></span>
                    <input type="hidden" class="number" value="1">
                </td>

                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="max-order-1" name="max-order" value="0">

                        <div class="input-group-append">
                            <span class="input-group-text text_type_default">@lang('VNĐ')</span>
                        </div>
                    </div>
                    <span class="error_valid_max_value_1 color_red"></span>
                </td>

                <td>
                    <div class="input-group" style="padding-left: 0px;">
                        <input type="text" class="form-control m-input numeric_child" id="order-commission-value-1" name="order-commission-value" value="0">

                        <div class="input-group-append">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="radio" name="config-operation-1" checked
                                           value="0"> @lang('VNĐ')
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="radio" name="config-operation-1" value="1">
                                    %
                                </label>
                            </div>
                        </div>
                    </div>
                    <span class="error_valid_commission_value_1 color_red"></span>
                </td>
                <td>
                    <a href="javascript:void(0)" onclick="commission.removeTr(this, 'order')"
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
