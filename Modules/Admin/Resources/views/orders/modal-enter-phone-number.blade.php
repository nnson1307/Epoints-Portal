<div class="modal fade" id="modal-enter-phone-number" role="dialog" style="z-index: 1100">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang("SĐT KHÁCH HÀNG")
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												×
											</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">
                            @lang("SĐT:")
                        </label>
                        <input type="text" class="form-control" id="enter-phone-number" onkeyup="ORDERGENERAL.enterPhoneNumber(this)">
                        <span class="text-danger error-enter-phone-number"></span>
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
                    <button type="button" onclick="ORDERGENERAL.sendSms()"
                            class="btn btn-primary  color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>@lang("GỬI")</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="hidden-code-sercard">
<input type="hidden" class="hidden-type-sms" value="one">