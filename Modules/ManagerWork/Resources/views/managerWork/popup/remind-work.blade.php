<div class="modal fade" id="popup-remind-work" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    @if($detail != null)
                        <i class="far fa-edit ss--icon-title m--margin-right-5"></i>
                        {{ __('CHỈNH SỬA NHẮC NHỞ') }}
                    @else
                        <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                        {{ __('TẠO NHẮC NHỞ') }}
                    @endif

                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-remind-staff-work">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nhắc ai'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select multiple name="staff[]" {{$detail != null ? 'disabled' : ''}} class="form-control select2 selectForm">
                                @foreach ($data['listStaff'] as $value)
                                    <option value="{{ $value['staff_id'] }}" {{$detail != null && $detail['staff_id'] == $value['staff_id'] ? 'selected' : ''}}>{{ $value['staff_name'] }}</option>
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
                                           placeholder="@lang('Thời gian nhắc')" name="date_remind" value="{{$detail!= null ? \Carbon\Carbon::parse($detail['date_remind'])->format('d/m/Y H:i') : \Carbon\Carbon::now()->addMinutes(5)->format('d/m/Y H:i')}}">
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
                                            <option value="{{$detail == null ? 'selected' : ''}}">{{__('Chọn thời gian trước nhắc nhở')}}</option>
                                            <option value="10" {{$detail != null && $detail['time'] == '10' ? 'selected' : ''}}>{{__('Trước 10 phút')}}</option>
                                            <option value="15" {{$detail != null && $detail['time'] == '15' ? 'selected' : ''}}>{{__('Trước 15 phút')}}</option>
                                            <option value="30" {{$detail != null && $detail['time'] == '30' ? 'selected' : ''}}>{{__('Trước 30 phút')}}</option>
                                            <option value="60" {{$detail != null && $detail['time'] == '60' ? 'selected' : ''}}>{{__('Trước 60 phút')}}</option>
                                        </select>
                                        <input type="hidden" name="time_type_remind" value="m">
{{--                                        <input type="text" class="form-control input-mask" id="time_remind" name="time_remind" value="{{$detail != null && $detail['time'] != '' ? $detail['time'] : ''}}"--}}
{{--                                               placeholder="{{__('Nhập thời gian trước nhắc nhở')}}">--}}
{{--                                        <div class="input-group-append input-group-append-select">--}}
{{--                                            <select class="input-group-text" name="time_type_remind">--}}
{{--                                                <option value="m" {{$detail != null && $detail['time_type'] == 'm' ? 'selected' : '' }}>{{ __('Phút') }}</option>--}}
{{--                                                <option value="h" {{$detail != null && $detail['time_type'] == 'h' ? 'selected' : '' }}>{{ __('Giờ') }}</option>--}}
{{--                                                <option value="d" {{$detail != null && $detail['time_type'] == 'd' ? 'selected' : '' }}>{{ __('Ngày') }}</option>--}}
{{--                                                <option value="w" {{$detail != null && $detail['time_type'] == 'w' ? 'selected' : '' }}>{{ __('Tuần') }}</option>--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label> {{ __('Tiêu đề') }}</label>
                                <input  type="text" name="title" class="form-control m-input" value="@if(isset($data['manage_work_id'])){{__('Nhắc nhở về công việc')}}@endif">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label> {{ __('Nội dung') }}</label>:<b class="text-danger">*</b>
                                <textarea name="description_remind" class="form-control m-input" rows="3">{!! $detail != null ? $detail['description'] : (isset($data['manage_work_id']) ? __(':staff_name nhắc bạn về công việc :work_name .Hãy cập nhật tiến độ và trạng thái công việc này nhé.',['staff_name'=> \Illuminate\Support\Facades\Auth::user()->full_name ,'work_name' => isset($workDetail['manage_work_title']) ? $workDetail['manage_work_title'] : (isset($detail['manage_work_title']) ? $detail['manage_work_title'] : '')]) : __('Bạn đã tạo nhắc nhở cho bạn')) !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="popup_manage_work_id" name="popup_manage_work_id" value="{{$data['manage_work_id']}}">
                    @if($detail != null)
                        <input type="hidden" id="manage_remind_id" name="manage_remind_id" value="{{$detail['manage_remind_id']}}">
                        <input type="hidden" name="staff[]" value="{{$detail['staff_id']}}">
                    @endif
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
                        <button type="button" onclick="detailCommon.addCloseRemind(0)"
                            class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                        </button>
                        @if($detail == null)
                            <button type="button" onclick="detailCommon.addCloseRemind(1)"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                    <i class="la la-plus"></i>
                                    <span>{{ __('LƯU & TẠO MỚI') }}</span>
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group-append-select{
        width: 100px;
    }
</style>