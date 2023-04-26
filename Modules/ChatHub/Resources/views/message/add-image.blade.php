<div class="modal" id="editImage" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title  ss--title m--font-bold">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5 text-primary"></i>
                    @lang('chathub::message.index.IMAGE_UPLOAD')
                </h4>
                <button onclick="cancelAddImage()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    <div id="hiddenn"></div>
                        <div class="form-group m-form__group ">
                            <div class="m-dropzone dropzone dz-clickable"
                                 action="{{route('admin.upload-image')}}"
                                 id="dropzoneone">
                                <div class="m-dropzone__msg dz-message needsclick">
                                    <h3 href="javascript:void(0);" class="m-dropzone__msg-title text-tile">
                                        @lang('chathub::message.index.IMAGE_SELECT')
                                    </h3>
                                </div>
                                <input type="hidden" id="file_image" name="service_image" value="">
                                <div id="up-ima">
                                </div>
                            </div>
                        </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <button onclick="cancelAddImage()" data-dismiss="modal"
                                class="btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 btn-success">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang('chathub::message.index.CANCEL')</span>
						</span>
                        </button>

                        <div class="btn-group">
                            <button type="button"
                                    class="btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md btn-save-image m--margin-left-10 m--margin-bottom-5 btn-primary">
							<span>
							<i class="la la-check"></i>
							<span>@lang('chathub::message.index.SAVE')</span>
							</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
