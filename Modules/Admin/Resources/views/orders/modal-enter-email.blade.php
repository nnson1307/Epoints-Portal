<div class="modal fade" id="modal-enter-email" role="dialog" style="z-index: 1100">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang("EMAIL KHÁCH HÀNG")
                </h4>
            </div>
            <form id="submit_email">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="form-control-label">
                            {{__('Email')}}:
                        </label>
                        <input type="text" class="form-control" id="enter_email" name="enter_email"
                               placeholder="{{__('Hãy nhập email')}}...">
                        <span class="error_email" style="color: red;"></span>
                    </div>
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
                        <button type="submit" onclick="order.submit_send_email()"
                                class="btn btn-primary color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>@lang("GỬI")</span>
							</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
