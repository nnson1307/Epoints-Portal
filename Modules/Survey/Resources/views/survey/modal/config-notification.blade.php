<form action="" id="form-submit-template">
    <div class="modal fade" id="modal_notification" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title primary-color" id="exampleModalLabel">
                        @lang('CÀI ĐẶT TRANG HIỆN THỊ SAU KHI HOÀN THÀNH KHẢO SÁT')
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3" style="color: black; font-weight:400">
                                {{ __('Title') }}
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" value="{{ __($data['title']) }}"
                                    name="title">
                            </div>
                        </div>
                    </div>
                    <div class="form-group kt-margin-t-15">
                        <div class="row">
                            <div class="col-lg-3" style="color: black; font-weight:400">
                                @lang('Hình ảnh')
                            </div>
                            <div class="col-lg-9">
                                <div class="kt-avatar kt-avatar--outline" id="kt_user_add_avatar">
                                    <div id="logo-image">
                                        <div class="kt-avatar__holder"
                                            style="background-image: url({{ $data['detail_background'] }});
                                                     background-position: center;
                                                     background-size: 100% 100%;">
                                        </div>
                                    </div>
                                    <input type="hidden" id="detail_background" name="detail_background"
                                        value="{{ $data['detail_background'] }}">
                                    <label class="kt-avatar__upload" data-toggle="kt-tooltip" title=""
                                        data-original-title="">
                                        <i class="fa fa-pen"></i>
                                        <input type="file" id="getFileLogo" name="getFileLogo"
                                            accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                            onchange="question.uploadBackground(this);">
                                    </label>
                                    <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title=""
                                        data-original-title="">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3" style="color: black; font-weight:400">
                                @lang('Nội dung hiển thị')
                            </div>
                            <div class="col-lg-9">
                                <textarea class="form-control" rows="3" name="message" id="message">{{ __($data['message']) }}</textarea>
                            </div>
                        </div>
                    </div>
                    @if (isset($isCountPoint) && $isCountPoint == 1)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6" style="color: black; font-weight:400">
                                    {{ __('Hiển thị điểm số ngay khi hoàn thành khảo sát') }}
                                </div>
                                <div class="col-lg-6 d-flex" style="justify-content: end;">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm d-flex">
                                        <label style="margin: 0 0 0 10px;">
                                            <input type="checkbox" {{ $data['show_point'] ? 'checked' : '' }}
                                                id="show_point" class="manager-btn receipt_info_check">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary color_button btn-search"
                        onclick="question.updateTemplate()">@lang('Lưu')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Hủy')</button>
                </div>
            </div>
        </div>
    </div>
</form>
