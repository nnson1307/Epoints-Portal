<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{ __('THÊM LOẠI CÔNG VIỆC') }}
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group m-form__group">
            <label class="black_title">
                {{ __('Tên loại công việc') }}:<b class="text-danger">*</b>
            </label>
            <input type="text" name="manage_type_work_name" class="form-control m-input"
                placeholder="{{ __('Nhập tên loại công việc') }}...">
            <span class="err error_manage_type_work_name"></span>
        </div>
        <div class="form-group m-form__group div_avatar">
            <input type="hidden" class="d-none" name="manage_type_work_icon">
            <div class="d-flex">
                <label class="wrap-img avatar float-left">
                    <img class="m--bg-metal m-image img-sd blah"
                        src="{{ asset('static/backend/images/service-card/default/hinhanh-default3.png') }}"
                        alt="{{ __('Hình ảnh') }}" width="100px" height="100px">
                    {{-- <span class="delete-img">
                        <a href="javascript:void(0)" onclick="TypeWork.remove_avatar()">
                            <i class="la la-close"></i>
                        </a>
                    </span> --}}
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
            <div class="error_img mb-3" style="color:red;">{{ __('(*) Kích thước phù hợp là 1:1') }}</div>
            <label for="getFile" class="btn btn-sm m-btn--icon color">
                <span>
                    <i class="la la-plus"></i>
                    <span>
                        {{ __('Thêm icon') }}
                    </span>
                </span>
            </label>
            <input accept="image/svg+xml,image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                data-msg-accept="{{ __('Hình ảnh không đúng định dạng') }}" id="getFile" type="file"
                onchange="uploadImage(this);" class="form-control" style="display:none">
                <span class="err error_manage_type_work_icon"></span>
        </div>
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

                <button type="button" onclick="TypeWork.addClose()"
                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                        <i class="la la-check"></i>
                        <span>{{ __('LƯU THÔNG TIN') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
