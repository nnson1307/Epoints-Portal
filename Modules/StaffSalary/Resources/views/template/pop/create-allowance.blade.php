<div class="modal fade" id="modal-create-allowance" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>
                    @lang('Thêm điều kiện tính phụ cấp')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create-allowance">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại phụ cấp'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group m-input-group">
                            <select class="form-control" id="salary_allowance_id" name="salary_allowance_id">
                                <option></option>
                                @foreach($optionAllowance as $v)
                                    <option value="{{$v['salary_allowance_id']}}">{{$v['salary_allowance_name']}}</option>
                                @endforeach
                            </select>

                            <span class="error-salaryAllowance" style="color: rgb(255, 0, 0);"></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Mức áp dụng'):<b class="text-danger">*</b>
                        </label>
                        <div class="input_min_time_work">
                            <input type="text" class="form-control m-input" id="staff_salary_allowance_num" name="staff_salary_allowance_num">
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
                            onclick="view.submitCreateAllowance()"
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


