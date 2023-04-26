<div class="modal fade show" id="modal-edit" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-edit"></i> @lang('CHỈNH SỬA CA LÀM VIỆC')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Tên ca làm'):<b class="text-danger">*</b>
                                </label>
                                <input type="text" class="form-control m-input" id="shift_name" name="shift_name"
                                       placeholder="@lang('Tên ca làm')" value="{{$item['shift_name']}}">
                            </div>

                            <div class="row">
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black-title">{{__('Thời gian làm việc bắt đầu')}}:<b
                                                class="text-danger">*</b></label>
                                    <div class="input-group m-input-group time_app">
                                        <input id="start_work_time" name="start_work_time"
                                               class="form-control timepicker"
                                               placeholder="{{__('Thời gian làm việc bắt đầu')}}"
                                               value="{{$item['start_work_time']??'08:00'}}" >
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black-title">{{__('Thời gian làm việc kết thúc')}}:<b
                                                class="text-danger">*</b></label>
                                    <div class="input-group m-input-group time_app">
                                        <input id="end_work_time" name="end_work_time" class="form-control timepicker"
                                               placeholder="{{__('Thời gian làm việc kết thúc')}}"
                                               value="{{$item['end_work_time']??'17:00'}}" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black-title">{{__('Thời gian nghỉ trưa bắt đầu')}}:<b
                                                class="text-danger"></b></label>
                                    <div class="input-group m-input-group time_app">
                                        <input id="start_lunch_break" name="start_lunch_break"
                                               class="form-control timepicker"
                                               placeholder="{{__('Thời gian nghỉ trưa bắt đầu')}}"
                                               value="{{$item['start_lunch_break']}}">
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black-title">{{__('Thời gian nghỉ trưa kết thúc')}}:<b
                                                class="text-danger"></b></label>
                                    <div class="input-group m-input-group time_app">
                                        <input id="end_lunch_break" name="end_lunch_break"
                                               class="form-control timepicker"
                                               placeholder="{{__('Thời gian nghỉ trưa kết thúc')}}"
                                               value="{{$item['end_lunch_break']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black_title">
                                        @lang('Số giờ làm tối thiểu tính đủ công'):<b class="text-danger">*</b>
                                    </label>
                                    <input type="text" class="form-control m-input phone" id="min_time_work"
                                           name="min_time_work"
                                           placeholder="@lang('Số giờ')" value="{{$item['min_time_work']??''}}">
                                </div>
                                <div class="form-group m-form__group col-lg-6" style="display: none;">
                                    <label class="black_title">
                                        @lang('Hệ số công'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input_timekeeping_coefficient">
                                        <input type="text" class="form-control m-input phone" id="timekeeping_coefficient"
                                               name="timekeeping_coefficient" value="{{$item['timekeeping_coefficient']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Chi nhánh làm việc'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <select class="form-control" id="branch_id" name="branch_id" multiple>
                                        <option></option>
                                        @foreach($optionBranch as $v)
                                            <option value="{{$v['branch_id']}}"
                                                    {{in_array($v['branch_id'], $branchMap) ? 'selected': ''}}>{{$v['branch_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Thời gian hoạt động của ca'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group m-input-group">
                                    <div class="m-checkbox-inline">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                            <input type="checkbox" class="is_monday"
                                                    {{$item['is_monday'] == 1 ? 'checked': ''}}> @lang('Thứ 2')
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                            <input type="checkbox" class="is_tuesday"
                                                    {{$item['is_tuesday'] == 1 ? 'checked': ''}}> @lang('Thứ 3')
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                            <input type="checkbox" class="is_wednesday"
                                                    {{$item['is_wednesday'] == 1 ? 'checked': ''}}> @lang('Thứ 4')
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                            <input type="checkbox" class="is_thursday"
                                                    {{$item['is_thursday'] == 1 ? 'checked': ''}}> @lang('Thứ 5')
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                            <input type="checkbox" class="is_friday"
                                                    {{$item['is_friday'] == 1 ? 'checked': ''}}> @lang('Thứ 6')
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                            <input type="checkbox" class="is_saturday"
                                                    {{$item['is_saturday'] == 1 ? 'checked': ''}} > @lang('Thứ 7')
                                            <span></span>
                                        </label>
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                            <input type="checkbox" class="is_sunday"
                                                    {{$item['is_sunday'] == 1 ? 'checked': ''}} > @lang('Chủ nhật')
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Ghi chú'):
                                </label>
                                <textarea rows="5" class="form-control m-input" id="note" name="note"
                                       placeholder="@lang('Ghi chú')">{{$item['note']??''}}
                                </textarea>
                            </div>

                            <input type="hidden" name="shift_id" value="{{$item['shift_id']??''}}">

                        </div>

                    </div>


                </form>
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
                    <button type="button" onclick="edit.save({{$item['shift_id']}})"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>