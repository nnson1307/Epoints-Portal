<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/6/22
 * Time: 4:20 PM
 */

?>
<div class="modal fade" id="modalChecking" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 40% !important;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #008990!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('RA CA')
                </h5>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Nhân viên'):
                            </label>
                            <input type="text" id="staff" value="{{$data['full_name']}}" class="form-control m-input"
                                   readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Vị trí làm việc'):
                            </label>
                            <input type="text" id="branch" value="{{$data['branch_name']}}" class="form-control m-input"
                                   readonly>
                            <input type="hidden" id="checkin_branch_id" value="{{$data['branch_id']}}">
                            <input type="hidden" id="time_working_staff_id" value="{{$data['time_working_staff_id']}}">
                            <input type="hidden" id="shift_id" value="{{$data['shift_id']}}">
                            <input type="hidden" id="working_day" value="{{$data['working_day']}}">
                            <input type="hidden" id="working_end_time" value="{{$data['working_end_time']}}">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Ca'):
                            </label>
                            <input type="text" id="shift" name="shift" value="{{$data['shift_name']}}" class="form-control m-input"
                                   readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Giờ ra ca'):
                            </label>
                            <input type="text" class="form-control m-input time-input" value="{{ \Carbon\Carbon::parse($data['working_end_time'])->format('H:i')}}"
                                   placeholder="@lang('Giờ')" name="checkout_time" id="checkout_time">
                        </div>
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
                                m--margin-left-10" onclick="attendances.checkout()">
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
    $(".time-input").timepicker({
        todayHighlight: !0,
        autoclose: !0,
        pickerPosition: "bottom-left",
        // format: "dd/mm/yyyy hh:ii",
        format: "HH:ii",
        defaultTime: "",
        showMeridian: false,
        minuteStep: 5,
        snapToStep: !0,
        // startDate : new Date()
        // locale: 'vi'
    });
</script>