<div id="modal-survey">
    <div class="modal fade" id="loyalty__modal--destroy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding:10px">
                    <h5 class="modal-title m-portlet__head-text tab" id="exampleModalLabel">
                        <i class="flaticon-list-1 pr-3"></i>
                        @lang('Thông báo')
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group kt-margin-b-10">
                        <h6 class="title">{{ __('Xác nhận xoá chương trình ') }}</h6>
                        <p class="description">
                            {{ __('Bạn có chắc chắn xoá chương trình này không? Sau khi xoá, chương trình sẽ không thể phục hồi lại được') }}
                        </p>
                    </div>
                    <div class="kt-section__content" id="popup-list-group"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-search" style="color:black; border:1px solid"
                        data-dismiss="modal">
                        {{ __('Huỷ') }}
                    </button>
                    <button type="button" id="btn-add-group-child-to-list"
                        onclick="loyalty.destroy('{{ $itemLoyalty['accumulation_program_id'] }}')"
                        class="btn btn-primary color_button_destroy color_button">
                        {{ __('Xác nhận') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
