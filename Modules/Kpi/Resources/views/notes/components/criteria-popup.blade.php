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
                <form id="frm-add-criteria">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="m-form__group">
                                <div class="criteria-content">

                                    <!-- Tiêu chí -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Tiêu chí') }}: </label>
                                            <div class="input-group">
                                                <select name="kpi_criteria_id" id="kpi_criteria_id" class="form-control m-input ss--select-2" style="width: 100%">
                                                    @foreach ($CRITERIA_LIST as $criteriaItem)
                                                        <option value="{{ $criteriaItem['kpi_criteria_id'] }}" 
                                                            data-unit="{{ __($criteriaItem['unit_name']) }}"
                                                            data-name="{{ __($criteriaItem['kpi_criteria_name']) }}"
                                                            data-customize="{{ $criteriaItem['is_customize'] }}">
                                                                {{ __($criteriaItem['kpi_criteria_name']) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Độ quan trọng -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Độ Quan Trọng') }}: </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="priority"
                                                    name="priority"
                                                    placeholder="{{ __('Nhập độ quan trọng') }}"
                                                    aria-describedby="basic-addon2">
                                            </div>
                                            <span class="float-right text-danger priority-msg"></span>
                                        </div>
                                    </div>

                                    <!-- Chỉ tiêu -->
                                    <div class="row modal-row">
                                        <div class="col-12">
                                            <label class="font-weight-bold">{{ __('Chỉ tiêu') }}: </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="kpi_value"
                                                    name="kpi_value"
                                                    placeholder="{{ __('Nhập chỉ tiêu') }}"
                                                    aria-describedby="basic-addon2">
                                            </div>
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

                                <button type="button" onclick="KpiNote.closeModal()" id="btn-save-criteria"
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
