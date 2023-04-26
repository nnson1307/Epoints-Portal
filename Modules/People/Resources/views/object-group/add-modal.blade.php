<div class="modal fade people-object-add-modal ajax-people-object-group-add-form hu-first-uppercase" method="POST" action="{{route('people.object-group.ajax-add')}}" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    <span class="text-uppercase">{{__('Thêm nhóm đối tượng')}}</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tên nhóm đối tượng'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" class="form-control m-input" name="name"
                           placeholder="@lang('Nhập tên nhóm đối tượng')">
                </div>

                <div class="form-group m-form__group">
                    <div class="input-group">
                        <label class="m-checkbox m-checkbox--air">
                            <input name="is_skip" type="checkbox">{{__('Lần sau không cần phúc tra')}}
                            <span></span>
                        </label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                        </button>
                        <button type="button"
                                class="ajax-people-object-group-add-submit ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('LƯU THÔNG TIN')}}</span>
                                    </span>
                        </button>
                        <button type="button" data-action2="save-and-create-new"
                                class="ajax-people-object-group-add-submit ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="fa fa-plus-circle m--margin-right-10"></i>
                                    <span>{{__('LƯU & TẠO MỚI')}}</span>
                                    </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
