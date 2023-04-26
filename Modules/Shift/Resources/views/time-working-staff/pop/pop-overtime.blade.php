<div class="modal fade" id="modal-overtime" role="dialog" style="z-index: 100;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('LÀM THÊM GIỜ')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-overtime">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Tên nhân viên'):</label>
                            <input class="form-control" type="text" value="{{$item['full_name']}}" disabled>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>@lang('Tên ca làm'):</label>
                            <input class="form-control" type="text" value="{{$item['shift_name']}}" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Vị trí làm'):</label> <b class="text-danger">*</b>

                            <div class="input-group">
                                <select class="form-control" id="branch_id" name="branch_id" style="width:100%;">
                                    @foreach($branchShift as $v)
                                        <option value="{{$v['branch_id']}}" {{$v['branch_id'] == $item['branch_id'] ? 'selected': ''}}>{{$v['branch_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>@lang('Hệ số công'):</label> <b class="text-danger">*</b>
                            <input class="form-control" type="text" id="timekeeping_coefficient"
                                   name="timekeeping_coefficient" value="{{$item['timekeeping_coefficient']}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Thời gian làm việc bắt đầu'):</label> <b class="text-danger">*</b>
                            <input class="form-control" type="text" id="time_start" name="time_start"
                                   value="{{\Carbon\Carbon::parse($item['working_end_day'].' '. $item['working_end_time'])->addMinutes(1)->format('d/m/Y H:i')}}">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>@lang('Thời gian làm việc kết thúc'):</label> <b class="text-danger">*</b>
                            <input class="form-control" type="text" id="time_end" name="time_end">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                            <input type="checkbox" id="is_not_check_in" name="is_not_check_in">@lang('Không cần chấm công')
                            <span></span>
                        </label>
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
                    <button type="button"
                            onclick="index.submitTimeKeepingOvertime('{{$item['time_working_staff_id']}}', '{{$view}}')"
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


