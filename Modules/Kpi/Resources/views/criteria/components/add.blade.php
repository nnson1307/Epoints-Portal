<div class="modal fade" id="popup-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content clear-form">

            <!-- Header modal -->
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('THÊM TIÊU CHÍ TÍNH KPI') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Body modal -->
            <div class="modal-body">

                <!-- Form chỉnh sửa tiêu chí -->
                <form id="frm-add-criteria" data-route="{{ route('kpi.criteria.submit') }}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-form__group">
                                <div class="criteria-content">
                                    <!-- Tên tiêu chí -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Tên tiêu chí') }}: <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="kpi_criteria_name"
                                                    name="kpi_criteria_name"
                                                    placeholder="{{ __('Nhập tên tiêu chí') }}"
                                                    aria-describedby="basic-addon2">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Đơn vị -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Đơn Vị') }}: <b class="text-danger">*</b></label>
                                            <div class="input-group">
                                                <select name="kpi_criteria_unit_id" id="kpi_criteria_unit_id" class="form-control">
                                                    <option value="">{{ __('Chọn đơn vị tính') }}</option>
                                                    @if (isset($criteriaUnit) && $criteriaUnit->isNotEmpty())
                                                        @foreach ($criteriaUnit as $unitItem)
                                                            <option value="{{ $unitItem['kpi_criteria_unit_id'] }}">{{ __($unitItem['unit_name']) }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mô tả -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Mô tả') }}:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="description"
                                                    name="description"
                                                    placeholder="{{ __('Nhập mô tả') }}"
                                                    aria-describedby="basic-addon2">
                                            </div>
                                            <div class="err-msg"></div>
                                        </div>
                                    </div>

                                    <!-- Chiều hướng -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">
                                                {{ __('Chiều hướng tốt') }}:
                                            </label>

                                            <div class="radio-row">
                                                <input type="radio" id="kpi_criteria_trend_down" name="kpi_criteria_trend" value="0">
                                                <label for="kpi_criteria_trend">{{ __('Giảm') }}</label>
                                            </div>
        
                                            <div class="radio-row">
                                                <input type="radio" id="kpi_criteria_trend_up" name="kpi_criteria_trend" value="1" checked>
                                                <label for="kpi_criteria_trend">{{ __('Tăng') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Là chỉ số chặn -->
                                    <div class="row modal-row">
                                        <div class="col-6">
                                            <label class="form-check-label m-checkbox m-checkbox--air">
                                                <input type="checkbox" name="is_blocked" id="is_blocked" value="1">
                                                <span></span>
                                                <div class="pt-1"><b>{{ __('Là chỉ số chặn') }}</b></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer modal -->
                    <div class="modal-footer">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                            <div class="m-form__actions m--align-right">
                                <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                    <span class="ss--text-btn-mobi">
                                        <i class="la la-arrow-left"></i>
                                        <span>{{ __('HỦY') }}</span>
                                    </span>
                                </button>

                                <button type="submit" onclick="" id="btn-save-criteria"
                                    class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10">
                                    <span class="ss--text-btn-mobi">
                                        <i class="la la-check"></i>
                                        <span>{{ __('LƯU THÔNG TIN') }}</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
                
            </div>
        </div>
    </div>
</div>
