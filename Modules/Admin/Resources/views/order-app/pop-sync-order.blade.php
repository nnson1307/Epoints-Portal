<div class="modal fade show" id="modal-sync" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-gear"></i> {{__('ĐỒNG BỘ ĐƠN HÀNG')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-sync">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Số giờ đồng bộ'):<b class="text-danger">*</b>
                        </label>
                        <input type="number" class="form-control" id="number_time" name="number_time" value="1">
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
                    <button type="submit" onclick="list.syncOrder()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('ĐỒNG BỘ')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>