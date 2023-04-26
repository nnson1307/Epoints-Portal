<div class="modal fade" id="modal-note" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang("THÊM GHI CHÚ")
                </h4>
            </div>
            <div class="modal-body">
                <form id="form-note">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <textarea id="note_detail" name="note_detail" class="form-control m-input class" cols="5" rows="5"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                    </button>
                    <button type="button" onclick="detail.storeNote('{{$customer_id}}')"
                            class="btn btn-primary  color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>@lang("THÊM")</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>