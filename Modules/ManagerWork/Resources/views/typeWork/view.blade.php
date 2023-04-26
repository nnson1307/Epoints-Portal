<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-eye ss--icon-title m--margin-right-5"></i>
            {{__('XEM CHI TIẾT LOẠI CÔNG VIỆC')}}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group m-form__group">
            <label class="black_title">
                {{__('Tên loại công việc')}}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="manage_type_work_name" class="form-control m-input" placeholder="{{__('Nhập tên laoij công việc')}}..." disabled>
        </div>
        <div class="form-group m-form__group div_avatar">
            <div class="d-flex">
                <label class="wrap-img avatar float-left">
                    <img class="m--bg-metal m-image img-sd blah" src="{{ asset('static/backend/images/service-card/default/hinhanh-default3.png') }}"
                        alt="{{ __('Hình ảnh') }}" width="100px" height="100px">
                </label>
                <div class="form-group m-form__group float-left m--margin-left-20 warning_img">
                    <label for="">{{ __('Định dạng') }}: <b class="image-info image-format"></b> </label>
                    <br>
                    <label for="">{{ __('Kích thước') }}: <b class="image-info image-size"></b>
                    </label>
                    <br>
                    <label for="">{{ __('Dung lượng') }}: <b class="image-info image-capacity"></b>
                    </label><br>
                    <label for="">{{ __('Cảnh báo') }}: <b class="image-info">{{ __('Tối đa 10MB (10240KB)') }}</b>
                    </label><br>
                    <span class="error_img" style="color:red;"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                </button>
            </div>
        </div>
    </div>
</div>