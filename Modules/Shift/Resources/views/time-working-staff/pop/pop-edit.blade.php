<div class="modal fade" id="modal-edit" role="dialog" style="z-index: 100;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA NGÀY LÀM VIỆC')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-edit">
                    <div class="form-group">
                        <label>@lang('Tên nhân viên'):</label>
                        <input class="form-control" type="text" value="{{$item['full_name']}}" disabled>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Tên ca làm'):</label>
                            <input class="form-control" type="text" value="{{$item['shift_name']}}" disabled>
                        </div>
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
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Hệ số công'):</label> <b class="text-danger">*</b>
                            <input class="form-control" type="text" id="timekeeping_coefficient" {{$item['is_ot'] == 0 ? 'disabled': ''}}
                                   name="timekeeping_coefficient" value="{{$item['timekeeping_coefficient']}}">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>@lang('Số giờ làm tối thiểu tính đủ công'):</label>
                            <input class="form-control" type="text" id="min_time_work" name="min_time_work"
                                   value="{{$item['min_time_work']}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Thời gian làm việc bắt đầu'):</label> <b class="text-danger">*</b>
                            <input class="form-control" type="text" id="time_start" name="time_start"
                                   value="{{\Carbon\Carbon::parse($item['working_day'].' '. $item['working_time'])->format('d/m/Y H:i')}}">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>@lang('Thời gian làm việc kết thúc'):</label> <b class="text-danger">*</b>
                            <input class="form-control" type="text" id="time_end" name="time_end"
                                   value="{{\Carbon\Carbon::parse($item['working_end_day'].' '. $item['working_end_time'])->format('d/m/Y H:i')}}">
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
                    <button type="button"
                            onclick="index.submitUpdateTimeWorking('{{$item['time_working_staff_id']}}', '{{$view}}')"
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


