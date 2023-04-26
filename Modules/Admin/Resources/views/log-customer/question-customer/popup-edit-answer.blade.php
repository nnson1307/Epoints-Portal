<div class="modal fade show" id="modal-answer" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    {{__('CHỈNH SỬA CÂU TRẢ LỜI')}}
                </h5>
            </div>
            <div class="modal-body">
                <form id="form-answer">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" id="content" name="content" rows="5">{{$feedback_answer_value}}</textarea>
                        </div>
                    </div>
                    <input type="hidden" id="feedback_answer_id" value="{{$feedback_answer_id}}">
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="submit" onclick="log.updateAnswer()"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>