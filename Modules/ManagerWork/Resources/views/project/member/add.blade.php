<form action="" id="form-submit-template">
    <div class="modal fade" id="project-member__add" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title primary-color" id="exampleModalLabel">
                        <i class="fa fa-plus-circle"></i>
                        {{ __('THÊM THÀNH VIÊN') }}
                    </h5>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                        <div class="m-form__actions m--align-right">
                            <button type="button" onclick="member.save('{{ $project->manage_project_id }}')"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{ __('LƯU') }}</span>
                                </span>
                            </button>
                            <button data-dismiss="modal"
                                style="background-color: #c4c5d6;
                            border-color: #c4c5d6;"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                    <i class="la la-arrow-left"></i>
                                    <span>{{ __('HUỶ') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
