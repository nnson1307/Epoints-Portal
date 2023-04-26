<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-comment ss--icon-title m--margin-right-5"></i>
            {{ __('managerwork::managerwork.process') }}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body" id="form-repeat-modal">
        <form class="container" id="update_process">
            <div class="row justify-content-center">
                <div class="col-12 mb-1">
                    <input type="hidden" name="manage_work_id" id="manage_work_id_comment" value="{{$manage_work_id}}">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            {{ __('managerwork::managerwork.process') }}
                        </label>
                        <div class="input-group">
                            <input type="text" class="progress_input form-control" name="progress" value="{{$progress}}">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-5 text-right">
                    <button type="submit"
                            class=" mt-3 ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">{{ __('managerwork::managerwork.update') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-arrow-left"></i>
                        <span>{{ __('HỦY') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>