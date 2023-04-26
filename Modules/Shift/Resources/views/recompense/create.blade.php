<div class="modal fade" id="modal-create" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i>
                    @lang('THÊM THƯỞNG PHẠT')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-create">
                    <div class="form-group m-form__group">
                        <div class="m-radio-inline">
                            <label class="m-radio">
                                <input type="radio" name="type" value="R" checked> @lang('Thưởng')
                                <span></span>
                            </label>
                            <label class="m-radio">
                                <input type="radio" name="type" value="P"> @lang('Phạt')
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên nội dung'):<b class="text-danger">*</b>
                        </label>
                        <div class="input_min_time_work">
                            <input type="text" class="form-control m-input" id="recompense_name" name="recompense_name">
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
                            onclick="listRecompense.submitCreate()"
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


