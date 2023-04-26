<div id="popup-create" class="modal fade popup-create" method="POST" action="{{ route('staff-salary.template.ajax-create') }}" role="dialog">
    <div class="modal-dialog modal-dialog-centered hu-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Thêm mẫu áp dụng')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                
                
            
                <form id="form-register">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên mẫu lương'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="modal_staff_salary_template_name" name="modal_staff_salary_template_name"
                                       placeholder="@lang('Tên mẫu lương')">
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Kỳ hạn trả lương'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" id="modal_staff_salary_pay_period_code"
                                            name="modal_staff_salary_pay_period_code">
                                        <option></option>
                                        @foreach($optionPayPeriod as $v)
                                            <option value="{{$v['staff_salary_pay_period_code']}}">{{__($v['staff_salary_pay_period_name'])}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                          
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Loại lương'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" id="modal_staff_salary_type_code" name="modal_staff_salary_type_code" onchange="view.chooseUnitAndTypeModal()">
                                        @foreach($optionType as $v)
                                            <option value="{{$v['staff_salary_type_code']}}">
                                                {{__($v['staff_salary_type_name'])}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group" style="display: none">
                                <label class="black_title">
                                    @lang('Đơn vị tiền tệ'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" id="modal_staff_salary_unit_code" name="modal_staff_salary_unit_code" onchange="view.chooseUnitAndTypeModal()">
                                        @foreach($optionUnit as $v)
                                            <option value="{{$v['staff_salary_unit_code']}}" selected>{{$v['staff_salary_unit_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Hình thức trả lương'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" id="modal_payment_type" name="modal_payment_type">
                                        <option></option>
                                        <option value="cash">@lang('Tiền mặt')</option>
                                        <option value="transfer">@lang('Chuyển khoản')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <table class="table m-table m-table--head-bg-default" id="table_default">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list"></th>
                                <th class="tr_thead_list text-center">@lang('Mức lương')</th>
                                <th class="tr_thead_list text-center salary_not_month">@lang('Thứ bảy')</th>
                                <th class="tr_thead_list text-center salary_not_month">@lang('Chủ nhật')</th>
                                <th class="tr_thead_list text-center salary_not_month">@lang('Ngày lễ')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="vertical-align: middle;">
                                    @lang('Mặc định')
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric" id="salary_default" name="salary_default" value="0">
    
                                        <div class="input-group-append">
                                            <span class="input-group-text text_type_default">
                                            </span>
                                        </div>
                                    </div>
                                    <div id="salary_default-error"></div>
                                </td>
                                <td class="salary_not_month">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric_child" id="salary_saturday_default"
                                               name="salary_saturday_default" value="0">
    
                                        <div class="input-group-append">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="salary_saturday_default_type" checked
                                                           value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="salary_saturday_default_type" value="percent">
                                                    %
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="salary_saturday_default-error"></div>
                                </td>
                                <td class="salary_not_month">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric_child" id="salary_sunday_default"
                                               name="salary_sunday_default" value="0">
    
                                        <div class="input-group-append">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="salary_sunday_default_type" checked
                                                           value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="salary_sunday_default_type" value="percent"> %
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="salary_sunday_default-error"></div>
                                </td>
                                <td class="salary_not_month">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric_child" id="salary_holiday_default"
                                               name="salary_holiday_default" value="0">
    
                                        <div class="input-group-append">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="salary_holiday_default_type" checked
                                                           value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="salary_holiday_default_type" value="percent">
                                                    %
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="salary_holiday_default-error"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row" style="margin-bottom: 0px;">
                        <div class="col-lg-6">
                            <strong>@lang('Lương làm thêm giờ')</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: right;">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" class="manager-btn" name="is_overtime" id="is_overtime"
                                               onchange="view.checkIsOvertime(this);">
                                        <span></span>
    
                                    </label>
                                </span>
                        </div>
                    </div>
                    <div class="form-group div_overtime" style="display: none;">
                        <table class="table m-table m-table--head-bg-default" id="table_overtime">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list"></th>
                                <th class="tr_thead_list text-center">@lang('Mức lương')</th>
                                <th class="tr_thead_list text-center salary_not_month">@lang('Thứ bảy')</th>
                                <th class="tr_thead_list text-center salary_not_month">@lang('Chủ nhật')</th>
                                <th class="tr_thead_list text-center salary_not_month">@lang('Ngày lễ')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="vertical-align: middle;">
                                    @lang('Mặc định')
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric" id="salary_overtime" name="salary_overtime" value="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text text_type_overtime">
                                            </span>
                                        </div>
                                    </div>
                                    <div id="salary_overtime-error"></div>
                                </td>
                                <td class="salary_not_month">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric_child" id="salary_saturday_overtime"
                                               name="salary_saturday_overtime" value="0">
    
                                        <div class="input-group-append">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="salary_saturday_overtime_type" checked
                                                           value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="salary_saturday_overtime_type" value="percent">
                                                  
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="salary_saturday_overtime-error"></div>
                                </td>
                                <td class="salary_not_month">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric_child" id="salary_sunday_overtime"
                                               name="salary_sunday_overtime" value="0">
    
                                        <div class="input-group-append">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="salary_sunday_overtime_type" checked
                                                           value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="salary_sunday_overtime_type" value="percent"> %
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="salary_sunday_overtime-error"></div>
                                </td>
                                <td class="salary_not_month">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input numeric_child" id="salary_holiday_overtime"
                                               name="salary_holiday_overtime" value="0">
    
                                        <div class="input-group-append">
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-secondary active">
                                                    <input type="radio" name="salary_holiday_overtime_type" checked
                                                           value="money"> <span class="salary-unit-name">@lang("VNĐ")</span>
                                                </label>
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="salary_holiday_overtime_type" value="percent">
                                                    %
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="salary_holiday_overtime-error"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row" style="margin-bottom: 0px;">
                        <div class="col-lg-6">
                            <strong>@lang('Phụ cấp')</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: right;">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        <input type="checkbox" class="manager-btn" id="is_allowance" name="is_allowance"
                                               onchange="view.checkIsAllowance(this);">
                                        <span></span>
    
                                    </label>
                                </span>
                        </div>
                    </div>
                    <div class="form-group div_allowance" style="display: none;">
                        <table class="table m-table m-table--head-bg-default" id="table_allowance">
                            <thead class="bg">
                            <tr>
                                {{--<th class="tr_thead_list text-center">@lang('Loại phụ cấp')</th>--}}
                                <th class="tr_thead_list text-center">@lang('Tên phụ cấp')</th>
                                <th class="tr_thead_list text-center">@lang('Phụ cấp thưởng')</th>
                                {{--<th class="tr_thead_list text-center">@lang('Phụ cấp chịu thuế')</th>--}}
                                <th class="tr_thead_list"></th>
                            </tr>
                            </thead>
                            <tbody>
    
                            </tbody>
                        </table>
    
                        <a href="javascript:void(0)" onclick="view.showPopCreateAllowance()" class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span>@lang('Thêm điều kiện')</span>
                            </span>
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                        </button>
                        <button type="button" onclick="view.ajaxCreate()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-allowance"></div>