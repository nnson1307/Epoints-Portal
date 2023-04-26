<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title ss--title m--font-bold">
                <i class="la la-edit ss--icon-title m--margin-right-5"></i>
                {{ __('CHI TIẾT ZNS BỊ ĐỘNG') }}
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group m-input">
                <label class="black_title">
                    @lang('Tên tham số'):<b class="text-danger">*</b>
                </label>
                <input type="hidden" name="params_id" value="{{$item->params_id}}">
                <input class="form-control" name="name" autocomplete="off" placeholder="{{ __('Tên tham số') }}"
                    value="{{ $item->name }}" disabled>
            </div>
            <div class="form-group m-input">
                <label class="black_title">
                    @lang('Nội dung tham số'):<b class="text-danger">*</b>
                </label>
                <input class="form-control" name="value" autocomplete="off" placeholder="{{ __('Nội dung tham số') }}"
                    value="{{ $item->value }}" disabled>
            </div>
            <div class="form-group m-input">
                <label class="black_title">
                    @lang('Mô tả'):
                </label>
                <input class="form-control" name="description" autocomplete="off" placeholder="{{ __('Mô tả') }}"
                    value="{{ $item->description }}">
            </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </button>
                    <button type="submit"
                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-check"></i>
                            <span>{{ __('CẬP NHẬT THÔNG TIN') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
