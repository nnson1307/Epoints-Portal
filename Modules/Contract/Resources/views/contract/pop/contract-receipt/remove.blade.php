<div class="modal fade show" id="modal-remove-receipt" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('XOÁ ĐỢT THU')
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-remove">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Lý do xoá'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="reason" name="reason">
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
                    <button type="button" onclick="contractReceipt.remove('{{$contract_receipt_id}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('ĐỒNG Ý')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
