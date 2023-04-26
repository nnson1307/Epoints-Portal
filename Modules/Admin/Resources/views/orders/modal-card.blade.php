<div class="modal fade" role="dialog" id="modal-card">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="la la-credit-card"></i>@lang("Danh sách thẻ dịch vụ đã mua") </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-receipt">
                <div class="modal-body">
                    <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true"
                         style="height: 300px; overflow: hidden;">
                        <div class="m-widget4 m-section__content append_card">


                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal" class="btn btn-danger m-btn m-btn--icon m-btn--wide m-btn--md m--margin-right-10">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang("Thoát")</span>
						</span>
                            </button>

                            {{--<div class="btn-group">--}}
                                {{--<button class="btn btn-success  m-btn m-btn--icon m-btn--wide m-btn--md btn-active"--}}
                                        {{--onclick="customer.click_active()">--}}
							{{--<span>--}}
							{{--<i class="la la-check"></i>--}}
							{{--<span>Kích hoạt</span>--}}
							{{--</span>--}}
                                {{--</button>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
