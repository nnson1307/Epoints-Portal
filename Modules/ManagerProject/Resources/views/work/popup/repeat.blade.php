<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{ __('TẦN SUẤT LẶP') }}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body" id="form-repeat-modal">
        <div class="form-group m-form__group mb-3">
            <div class="d-flex">
                <div class="col-lg-2">
                    <label class="black_title">
                        @lang('Lặp lại')
                    </label>
                </div>
                <div class="col-lg-6">
                    <div class="m-radio-inline d-flex flex-column">
                        <input type="hidden" name="repeat_type">
                        <label class="m-radio cus">
                            <input type="radio" checked="" name="repeat_type_check" value="none"> {{ __('Không bao giờ') }}
                            <span></span>
                        </label>
                        <label class="m-radio cus">
                            <input type="radio" name="repeat_type_check" value="daily">{{ __('Hằng ngày') }}
                            <span></span>
                        </label>
                        <label class="m-radio cus">
                            <input type="radio" name="repeat_type_check" value="weekly">{{ __('Hằng tuần') }}
                            <span></span>
                        </label>
                        <div class="weekDays-selector day-of-week d-none">
                            <input type="checkbox" id="weekday-mon" name="day_of_weeks[]" value="2" class="weekday" />
                            <label for="weekday-mon">T2</label>
                            <input type="checkbox" id="weekday-tue" name="day_of_weeks[]" value="3" class="weekday" />
                            <label for="weekday-tue">T3</label>
                            <input type="checkbox" id="weekday-wed" name="day_of_weeks[]" value="4" class="weekday" />
                            <label for="weekday-wed">T4</label>
                            <input type="checkbox" id="weekday-thu" name="day_of_weeks[]" value="5" class="weekday" />
                            <label for="weekday-thu">T5</label>
                            <input type="checkbox" id="weekday-fri" name="day_of_weeks[]" value="6" class="weekday" />
                            <label for="weekday-fri">T6</label>
                            <input type="checkbox" id="weekday-sat" name="day_of_weeks[]" value="7" class="weekday" />
                            <label for="weekday-sat">T7</label>
                            <input type="checkbox" id="weekday-sun" name="day_of_weeks[]" value="8" class="weekday" />
                            <label for="weekday-sun">CN</label>
                        </div>
                        <label class="m-radio cus">
                            <input type="radio" name="repeat_type_check" value="monthly">{{ __('Hằng tháng') }}
                            <span></span>
                        </label>
                        <div class="input-group">
                            <div class="weekDays-selector day-of-month d-none">
                                @for ($i = 1; $i < 32; $i++)
                                    <input type="checkbox" name="day_of_months[]" id="date-{{ $i }}" value="{{ $i }}"
                                        class="weekday" />
                                    <label for="date-{{ $i }}">{{ $i }}</label>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group m-form__group mb-3">
            <div class="d-flex">
                <div class="col-lg-2">
                    <label class="black_title">
                        @lang('Kết thúc')
                    </label>
                </div>
                <div class="col-lg-6">
                    <div class="m-radio-inline d-flex flex-column">
                        <label class="m-radio cus">
                            <input type="hidden" name="repeat_end">
                            <input type="radio" name="repeat_end_check" value="none">
                            {{ __('Không bao giờ') }}
                            <span></span>
                        </label>
                        <div class="d-flex align-items-center">
                            <div class="col-lg-3 p-0">
                                <label class="m-radio cus">
                                    <input type="radio" name="repeat_end_check" value="after">{{ __('Sau') }}
                                    <span></span>
                                </label>
                            </div>
                            <div class="after-repeat col-lg-9 p-0">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="repeat_end_time"
                                        placeholder="00" width="50px" disabled>
                                    <div class="input-group-append">
                                        <select class="input-group-text" name="repeat_end_type" disabled>
                                            <option value="d" selected>{{ __('Ngày') }}</option>
                                            <option value="w">{{ __('Tuần') }}</option>
                                            <option value="m">{{ __('Tháng') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="col-lg-3 p-0">
                                <label class="m-radio cus">
                                    <input type="radio" name="repeat_end_check" value="date">{{ __('Vào ngày') }}
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-lg-9 p-0">
                                <div class="input-group date">
                                    <input type="text" class="form-control m-input date-time" readonly
                                        placeholder="@lang('Thời gian nhắc')" name="repeat_end_full_time" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i
                                                class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group m-form__group mb-3">
            <div class="d-flex">
                <div class="col-lg-2">
                    <label class="black_title">
                        @lang('Giờ lặp')
                    </label>
                </div>
                <div class="col-lg-6">
                    <div class="input-group">
                        <input type="time" class="form-control m-input" name="repeat_time" value="" />
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                <button type="button" onclick="ManagerWork.appendRepeat()"
                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-check"></i>
                        <span>{{ __('ÁP DỤNG') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
