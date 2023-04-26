<div id="modal-video" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang('XEM VIDEO')
                </h4>
            </div>
            <div class="modal-body">
                <div class="player">
                    <video controls width="100%" height="300">
                        <source src="{{$link}}" type="video/mp4">
                        <source src="{{$link}}" type="video/webm">
                        <!-- fallback content here -->
                    </video>
                </div>
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
