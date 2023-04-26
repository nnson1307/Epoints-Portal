<div id="modal-image" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang('XEM HÌNH ẢNH')
                </h4>
            </div>
            <div class="modal-body">
                <img src="{{$link}}" alt="Hình ảnh" style="width: 100%; max-width: 500px; max-height: 550px;">
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang('HỦY')</span>
						</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
