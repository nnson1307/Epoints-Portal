<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */
?>
<div class="modal fade" id="modalHolidayEdit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 40% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #008990!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHỈNH SỬA NGÀY LỄ')
                </h5>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>
                        @lang('Tên ngày lễ'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" id="staff_holiday_title" value="{{ $data['staff_holiday_title'] }}" class="form-control m-input" placeholder="{{ __('Tên ngày lễ') }}">
                    <span class="error-staff-holiday-title"></span>
                    <input type="hidden" id="staff_holiday_id" value="{{ $data['staff_holiday_id'] }}">
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label>
                            @lang('Ngày bắt đầu'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group date">
                            <input type="text" class="form-control m-input" value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['staff_holiday_start_date'])->format('d/m/Y') }}" readonly=""  placeholder="{{ __('Ngày bắt đầu') }}" id="staff_holiday_start_date">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <span class="error-staff-holiday-start-date"></span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>
                            @lang('Ngày kết thúc'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group date">
                            <input type="text" class="form-control m-input daterange-picker" value="{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data['staff_holiday_end_date'])->format('d/m/Y') }}" readonly="" placeholder="{{ __('Ngày kết thúc') }}" id="staff_holiday_end_date">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                </span>
                            </div>
                        </div>
                        <span class="error-staff-holiday-end-date"></span>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </button>
                        <button class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md
                                m--margin-left-10" onclick="holiday.edit();">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('ĐỒNG Ý')</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#staff_holiday_start_date, #staff_holiday_end_date').datepicker({
            rtl: mUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            format: 'dd/mm/yyyy',
        });
</script>