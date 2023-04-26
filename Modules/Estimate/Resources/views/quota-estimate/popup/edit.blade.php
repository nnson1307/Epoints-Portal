
<div class="modal fade" id="popup-quota-edit" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content clear-form">
            <div class="modal-header">
                <h5 class="modal-title color_title"
                    style="color: #008990!important; font-weight: bold!important;font-size: 1.1rem!important;"
                    id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{ __('THÊM CẤU HÌNH') }}
                </h5>
            </div>

            <div class="modal-body">
                <form id="frm-edit-estimate">
                    <input type="hidden" id="estimate_branch_time_id" name="estimate_branch_time_id" value="{{ $id }}">
                    @if($type == 'W')
                        <div class="row">
                            <label class="form-check-label m-checkbox m-checkbox--air">
                                            <input type="checkbox" name="is_approve_week" value="1" id="is_approve_week" checked disabled>
                                            <span></span>
                                            <div class="pt-1"><b>{{ __('Theo tuần') }}</b></div>
                                        </label>
                        </div>
                        <div class="row week-content">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-2 form-group">
                                        <label class="font-weight-bold">@lang('Chọn tuần')</label>
                                        @php
                                            $date = \Carbon\Carbon::now();
                                            $weeksInYear = $date->weeksInYear;
                                        @endphp
                                    </div>
                                    <div class="col-lg-5 form-group">
                                        <select class="form-control" name="week[select_from]" id="selectWeekFrom" style="width: 100%" disabled>
                                            @for($i = 1; $i <= $weeksInYear; $i++)
                                                @php
                                                    $week = Modules\Estimate\Libs\help\Help::getStartEndDateOfWeek($i);
                                                @endphp
                                                @if($i == $content)
                                                    <option value="{{ $i }}" selected>@lang('Tuần') {{ $i }} ({{ $week[0] .' - '. $week[1] }})</option>
                                                @else
                                                    <option value="{{ $i }}">@lang('Tuần') {{ $i }} ({{ $week[0] .' - '. $week[1] }})</option>
                                                @endif
                                            @endfor
                                            </select>
                                    </div>
                                    <div class="col-lg-5 form-group">
                                        <select class="form-control" name="week[select_to]" id="selectWeekTo" style="width: 100%" disabled>
                                            @for($i = 1; $i <= $weeksInYear; $i++)
                                                @php
                                                    $week = Modules\Estimate\Libs\help\Help::getStartEndDateOfWeek($i);
                                                @endphp
                                                @if($i == $content)
                                                    <option value="{{ $i }}" selected>@lang('Tuần') {{ $i }} ({{ $week[0] .' - '. $week[1] }})</option>
                                                @else
                                                    <option value="{{ $i }}">@lang('Tuần') {{ $i }} ({{ $week[0] .' - '. $week[1] }})</option>
                                                @endif
                                                
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 d-flex align-items-center">
                                        <label class="font-weight-bold">@lang('Tổng số giờ làm việc dự kiến')</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="week-estimate-time" name="week[estimate_time]"
                                            autocomplete="off" aria-describedby="basic-addon2" value="{{ number_format($time) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">@lang('Giờ') </span>
                                            </div>

                                        </div>
                                        <span class="error-week-estimate-time" style="color:red;"></span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6 d-flex align-items-center">
                                        <label class="font-weight-bold">@lang('Tổng ngân sách lương dự kiến')</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="week-estimate-money" name="week[estimate_money]"
                                            autocomplete="off" aria-describedby="basic-addon2" value="{{ number_format($money) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">VNĐ</span>
                                            </div>
                                        </div>
                                        <span class="error-week-estimate-money" style="color:red;"></span>
                                    </div>
                                </div>
                    
                        
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <label class="form-check-label m-checkbox m-checkbox--air">
                                <input type="checkbox" name="is_approve_month" value="1" id="is_approve_month" checked disabled>
                                <span></span>
                                <div class="pt-1"><b>{{ __('Theo tháng') }}</b></div>
                            </label>
                        </div>
                        <div class="row month-content">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-2 form-group">
                                        <label class="font-weight-bold">{{ __('Chọn tháng') }}</label>
                                    </div>
                                    <div class="col-lg-5 form-group">
                                        <select class="form-control" name="month[select_from]" id="selectMonthFrom" style="width:100%" disabled>
                                            @for($i = 1; $i <= 12; $i++)
                                                @if($i == $content)
                                                    <option value="{{ $i }}" selected>{{ __('Tháng') }} {{ $i .'/'. \Carbon\carbon::now()->year}}</option>
                                                @else
                                                    <option value="{{ $i }}">{{ __('Tháng') }} {{ $i .'/'. \Carbon\carbon::now()->year}}</option>
                                                @endif
                                                
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-lg-5 form-group">
                                        <select class="form-control" name="month[select_to]" id="selectMonthTo" style="width:100%" disabled>
                                            @for($i = 1; $i <= 12; $i++)
                                                @if($i == $content)
                                                    <option value="{{ $i }}" selected>{{ __('Tháng') }} {{ $i .'/'. \Carbon\carbon::now()->year}}</option>
                                                @else
                                                    <option value="{{ $i }}">{{ __('Tháng') }} {{ $i .'/'. \Carbon\carbon::now()->year}}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                    
                                </div>  

                                <div class="row mb-3">
                                    <div class="col-6 d-flex align-items-center">
                                        <label class="font-weight-bold">{{ __('Tổng số giờ làm việc dự kiến') }}</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="month-estimate-time" name="month[estimate_time]"
                                            autocomplete="off" aria-describedby="basic-addon2" value="{{ number_format($time) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">{{ __('Giờ') }} </span>
                                            </div>
                                        </div>
                                        <span class="error-month-estimate-time" style="color:red;"></span>

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6 d-flex align-items-center">
                                        <label class="font-weight-bold">{{ __('Tổng ngân sách lương dự kiến') }}</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="month-estimate-money" name="month[estimate_money]"
                                            autocomplete="off" aria-describedby="basic-addon2" value="{{ number_format($money) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">{{ __('VNĐ') }}</span>
                                            </div>
                                        </div>
                                        <span class="error-month-estimate-money" style="color:red;"></span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    @endif
                    
                    
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
                            <a class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md color_button" onclick="estimate.edit();" style="color: #fff;" href="javascript:void(0)">
                                <span>
                                    <i class="la la-check"></i>
                                    <span>{{ __('LƯU THÔNG TIN') }}</span>
                                </span>
                            </a>
                           
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
