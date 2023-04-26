<div class="modal fade" id="popup-remind-staff-not-start" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{ __('TẠO NHẮC NHỞ') }}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-staff-not-start-work">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nhắc ai'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select multiple disabled class="form-control select2 select2-active">
                                @foreach ($staffList as $value)
                                    <option value="{{ $value['staff_id'] }}" selected>{{ $value['staff_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Thời gian nhắc'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group date">
                                    <input type="text" class="form-control m-input date-timepicker" readonly
                                           placeholder="@lang('Thời gian nhắc')" name="date_remind" value="{{\Carbon\Carbon::now()->addMinutes(5)->format('d/m/Y H:i')}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Thời gian trước nhắc nhở')
                                </label>
                                <div>
                                    <div class="input-group mb-3">
                                        <select class="form-control" id="time_remind" name="time_remind">
                                            <option value="">{{__('Chọn thời gian trước nhắc nhở')}}</option>
                                            <option value="10">{{__('Trước 10 phút')}}</option>
                                            <option value="15">{{__('Trước 15 phút')}}</option>
                                            <option value="30">{{__('Trước 30 phút')}}</option>
                                            <option value="60">{{__('Trước 60 phút')}}</option>
                                        </select>

                                        <input type="hidden" name="time_type_remind" value="m">
{{--                                        <input type="text" class="form-control input-mask" id="time_remind" name="time_remind"--}}
{{--                                               placeholder="Nhập thời gian trước nhắc nhở">--}}
{{--                                        <div class="input-group-append">--}}
{{--                                            <select class="input-group-text" name="time_type_remind">--}}
{{--                                                <option value="m" selected>{{ __('Phút') }}</option>--}}
{{--                                                <option value="h">{{ __('Giờ') }}</option>--}}
{{--                                                <option value="d">{{ __('Ngày') }}</option>--}}
{{--                                                <option value="w">{{ __('Tuần') }}</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label> {{ __('Tiêu đề') }}</label>:<b class="text-danger">*</b>
                                <input  type="text" name="title" class="form-control m-input" value="{{__('Nhắc nhở làm việc')}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label> {{ __('Nội dung') }}</label>:<b class="text-danger">*</b>
                                <textarea name="description_remind" class="form-control m-input" rows="3">{{ __(':staff_name nhắc bạn cập nhật tiến độ và trạng thái công việc của hôm nay nhé.',['staff_name' => \Illuminate\Support\Facades\Auth::user()->full_name]) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="list_staff_not_start_work" value="{{$staffSelect}}">
                </form>
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
                        <button type="button" onclick="StaffOverview.addCloseRemind()"
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
</div>
