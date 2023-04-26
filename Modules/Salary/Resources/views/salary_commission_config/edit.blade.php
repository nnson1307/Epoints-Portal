<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title ss--title m--font-bold">
                <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                {{ __('CẬP NHẬT CẤU HÌNH HOA HỒNG') }}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form class="modal-body">
            <input type="hidden" name="salary_commission_config_id" value="{{ $item->salary_commission_config_id }}">
            <div class="form-group m-form__group">
                <label class="black_title d-block">
                    {{ __('Phòng ban') }}:<b class="text-danger">*</b>
                </label>
                <select name="department_id" class="form-control select2 select2-activ" disabled>
                    <option value="">@lang('Chọn phòng ban')</option>
                    @foreach ($department_list as $key => $value)
                        <option value="{{ $key }}" {{ $key == $item->department_id ? ' selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                </select>
                <span class="error error-department_id"></span>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('Hiển thị theo') }}:<b class="text-danger">*</b>
                </label>
                <div class="d-flex">
                    <label class="m-radio cus mr-3">
                        <input type="radio" name="type_view" value="kd"
                            {{ 'kd' == $item->type_view ? ' checked' : '' }}>{{ __('Kinh doanh') }}
                        <span></span>
                    </label>
                    <label class="m-radio cus">
                        <input type="radio" name="type_view" value="kt"
                            {{ 'kt' == $item->type_view ? ' checked' : '' }}>{{ __('Kỹ thuật') }}
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('Nội bộ') }}
                </label>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="input-group mr-3">
                                <label class="black_title d-block w-100">
                                    {{ __('Bán mới') }}:<br>
                                </label>
                                <input type="text" name="internal_new" class="form-control percent-format"
                                    placeholder="0" value="{{ $item->internal_new }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="input-group">
                                <label class="black_title d-block w-100">
                                    {{ __('Renew') }}:<br>
                                </label>
                                <input type="text" name="internal_renew" class="form-control percent-format"
                                    placeholder="0" value="{{ $item->internal_renew }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('Bên ngoài') }}
                </label>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="input-group mr-3">
                                <label class="black_title d-block w-100">
                                    {{ __('Bán mới') }}:<br>
                                </label>
                                <input type="text" name="external_new" class="form-control percent-format"
                                    placeholder="0" value="{{ $item->external_new }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="input-group">
                                <label class="black_title d-block w-100">
                                    {{ __('Renew') }}:<br>
                                </label>
                                <input type="text" name="external_renew" class="form-control percent-format"
                                    placeholder="0" value="{{ $item->external_renew }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('Đại lý') }}
                </label>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="input-group mr-3">
                                <label class="black_title d-block w-100">
                                    {{ __('Bán mới') }}: <br>
                                </label>
                                <input type="text" name="partner_new" class="form-control format-percent"
                                    placeholder="0" value="{{ $item->partner_new }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="input-group">
                                <label class="black_title d-block w-100">
                                    {{ __('Renew') }}:<br>
                                </label>
                                <input type="text" name="partner_renew" class="form-control format-percent"
                                    placeholder="0" value="{{ $item->partner_renew }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('Hoa hồng lắp đặt') }}:<b class="text-danger">*</b>
                </label>
                <div class="input-group">
                    <input type="text" name="installation_commission" class="form-control format-percent" placeholder="0" value="{{ $item->installation_commission }}">
                    <div class="input-group-append">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('KPIs doanh số NVCT (>=)') }}:<b class="text-danger">*</b>
                </label>
                <div class="input-group">
                    <input type="text" name="kpi_staff" class="form-control money_format" placeholder="0"
                        value="{{ $item->kpi_staff }}">
                    <div class="input-group-append">
                        <span class="input-group-text">VND</span>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    {{ __('KPIs doanh số NVTV (>=)') }}:<b class="text-danger">*</b>
                </label>
                <div class="input-group">
                    <input type="text" name="kpi_probationers" class="form-control money_format" placeholder="0"
                        value="{{ $item->kpi_probationers }}">
                    <div class="input-group-append">
                        <span class="input-group-text">VND</span>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </button>

                    <button type="button" onclick="SalaryCommissionConfig.submitEdit({{ $item->salary_commission_config_id }})"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-check"></i>
                            <span>{{ __('LƯU THÔNG TIN') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
