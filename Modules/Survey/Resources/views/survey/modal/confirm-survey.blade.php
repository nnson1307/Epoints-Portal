<div id="modal-survey">
    <div class="modal fade" id="confirm_survey" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <h6 class="title">@lang('Xác nhận duyệt chương trình')</h6>
                        <p class="description">
                            {{__("Khi xác nhận duyệt chương trình, bạn sẽ không thể chỉnh sửa một số thông tin của chương trình bạn đang duyệt. Bạn có chắc chắn duyệt không?")}}
                            </p>
                    </div>
                    <div class="kt-section__content" id="popup-list-group"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-search" style="color:black; border:1px solid"
                        data-dismiss="modal">
                        @lang('Không')
                    </button>
                    <button type="button" onclick="survey.changeStatus('{{ $detail['survey_id'] }}', 'R')"
                        id="btn-add-group-child-to-list" class="btn btn-primary btn-search color_button">
                        @lang('Đồng ý')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
