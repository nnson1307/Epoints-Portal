<div id="modal-survey">
    <div class="modal fade" id="modal-template__question" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <h6 class="title">
                            {{ __('Bạn có muốn thêm mẫu câu hỏi không?') }}
                        </h6>
                        <p class="description">
                            {{ __('Số câu hỏi trong block đã vượt quá số câu hỏi quy định. Bạn có muốn tiếp tục sử dụng template mẫu') }}
                        </p>
                    </div>
                    <div class="kt-section__content" id="popup-list-group"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-search" style="color:black; border:1px solid"
                        data-dismiss="modal">
                        {{ __('Không') }}
                    </button>
                    <button type="button" id="btn-add-group-child-to-list"
                        onclick="question.loadTeample('{{ $template }}', '{{ $key }}', 'add')"
                        class="btn btn-primary color_button_destroy color_button">
                        {{ __('Đồng ý') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
