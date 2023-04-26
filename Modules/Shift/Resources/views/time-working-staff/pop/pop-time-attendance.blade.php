<div class="modal fade" id="modal-time-attendance" role="dialog" style="z-index: 100;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    {{__('CHẤM CÔNG HỘ')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Nhân viên'):
                            </label>
                            <input type="text" id="staff" value="{{$item['full_name']}}" class="form-control m-input"
                                   disabled>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Tên ca'):
                            </label>
                            <input type="text" id="staff" value="{{$item['shift_name']}}" class="form-control m-input"
                                   disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Thời gian bắt đầu'):
                            </label>
                            <input type="text" id="check_in_day" name="check_in_day" value="{{\Carbon\Carbon::parse($item['working_day'])->format('d/m/Y')}}" class="form-control m-input"
                                   disabled>
                        </div>
                        <input type="hidden" id="lock_check_in" value="{{$log_check_in != null && $log_check_in['created_type'] == 'staff' ? 1: 0}}">
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                               {{ __('Giờ vào ca') }}
                            </label>
                            <input type="text" class="form-control m-input time-input" value="{{ \Carbon\Carbon::parse($item['working_time'])->format('H:i')}}"
                                   placeholder="@lang('Giờ')" name="check_in_time" id="check_in_time">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Thời gian kết thúc'):
                            </label>
                            <input type="text" id="check_out_day" name="check_out_day" value="{{\Carbon\Carbon::parse($item['working_end_day'])->format('d/m/Y')}}" class="form-control m-input"
                                   disabled>
                        </div>
                        <input type="hidden" id="lock_check_out" value="{{$log_check_out != null && $log_check_out['created_type'] == 'staff' ? 1: 0}}">
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                @lang('Giờ ra ca'):
                            </label>
                            <input type="text" class="form-control m-input time-input" value="{{ \Carbon\Carbon::parse($item['working_end_time'])->format('H:i')}}"
                                   placeholder="@lang('Giờ')" name="check_out_time" id="check_out_time">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>

                    <button type="button" onclick="index.submitTimeAttendance('{{$item['time_working_staff_id']}}', '{{$item['staff_id']}}', '{{$item['working_day']}}', '{{$view}}')"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU')}}</span>
							</span>
                    </button>
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