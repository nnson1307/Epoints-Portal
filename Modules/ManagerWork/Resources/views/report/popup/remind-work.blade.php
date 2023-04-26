<div class="modal fade" id="popup-remind-work" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <form id="form-remind-staff-work">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('Nhắc ai') }}:<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select multiple name="staff[]" disabled class="form-control select2 selectForm">
                                    <option selected>{{ \Illuminate\Support\Facades\Auth::user()->full_name  }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    {{ __('Thời gian nhắc') }}:<b class="text-danger">*</b>
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
{{--                                        <input type="text" class="form-control input-mask" id="time_remind" name="time_remind" value=""--}}
{{--                                               placeholder="@lang('Thời gian trước nhắc nhở')">--}}
{{--                                        <div class="input-group-append">--}}
{{--                                            <select class="input-group-text" name="time_type_remind">--}}
{{--                                                <option value="m" selected>{{ __('Phút') }}</option>--}}
{{--                                                <option value="h" >{{ __('Giờ') }}</option>--}}
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
                                <input  type="text" name="title" class="form-control m-input" value="{{__('Nhắc nhở của tôi')}}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label> {{ __('Nội dung') }}</label>:<b class="text-danger">*</b>
                                <textarea name="description_remind" class="form-control m-input" rows="3">{{isset($data['manage_work_id']) ? __('Bạn đã tạo nhắc nhở cho công việc :work_name .Hãy cập nhật tiến độ và trạng thái công việc này nhé.',['work_name' => $workDetail['manage_work_title']]) : __('Bạn đã tạo nhắc nhở cho bạn')}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label class="black_title">
                                {{ __('Công việc') }}
                            </label>
                            <div class="form-group m-form__group">
                                <select class="input-group-text form-control selectForm select2-hidden-accessible" onchange="changeTask()" name="manage_work_id">
                                    <option value="" >{{ __('Chọn công việc') }}</option>
                                    @foreach($listWork as $item)
                                        <option value="{{$item['manage_work_id']}}" data-name="{{ $item['manage_work_title'] }}">{{ $item['manage_work_title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                        <input type="hidden" name="staff[]" value="{{\Illuminate\Support\Facades\Auth::id()}}">
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
                        <button type="button" onclick="MyWork.addCloseRemind(0)"
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

<script>
    var title_not_work = "{{__('Bạn đã tạo nhắc nhở cho bạn')}}";
    var title_work = "{{__('Bạn đã tạo nhắc nhở cho công việc :work_name. Hãy cập nhật tiến độ và trạng thái công việc này nhé.')}}";
    function changeTask(){
        if ($('#form-remind-staff-work select[name="manage_work_id"]').val() == ''){
            $('#form-remind-staff-work textarea[name="description_remind"]').val(title_not_work);
        } else {
            title_work_tmp = title_work.replace(':work_name',$('#form-remind-staff-work select[name="manage_work_id"] option:selected').data("name"))
            $('#form-remind-staff-work textarea[name="description_remind"]').val(title_work_tmp);
        }
    }
</script>