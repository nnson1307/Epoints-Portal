<div class="modal fade" role="dialog" id="modal-print">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    </i> {{__('DANH SÁCH THẺ IN')}}
                </h4>

            </div>
            <form id="form-print-card">
                <div class="m-scrollable m-scroller ps ps--active-y"
                     data-scrollable="true"
                     data-height="380" data-mobile-height="300"
                     style="height: 500px; overflow: hidden;">
                    <div class="row m--padding-40 m--margin-bottom-10 sv-card-print">

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.order')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('THOÁT')}}</span>
						</span>
                        </a>

                        <button type="button"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-print"></i>
							<span>{{__('IN TẤT CẢ')}}</span>
							</span>
                        </button>
                        <button type="button" onclick="ORDERGENERAL.sendAllCodeCard()"
                                class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn-sms m--margin-left-10 btn-send-sms">
							<span>
							<i class="la la-mobile-phone"></i>
							<span>{{__('SMS TẤT CẢ')}}</span>
							</span>
                        </button>
                        <button type="button" onclick="ORDERGENERAL.sendSmsAndPrint()"
                                class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn_all m--margin-left-10 btn-send-sms">
							<span>
							{{--<i class="la la-print"></i>--}}
                                <span>{{__('CẢ HAI')}}</span>
							</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
