<div class="modal fade" id="popup_upload_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    @if($detailFile != null)
                        <i class="far fa-edit ss--icon-title m--margin-right-5"></i>
                        {{ __('Chỉnh sửa hồ sơ') }}
                    @else
                        <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                        {{ __('Thêm hồ sơ') }}
                    @endif

                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                @if($detailFile != null)
                    <input type="hidden" class="manage_document_file_id" name="manage_document_file_id" value="{{$detailFile != null ? $detailFile['manage_document_file_id'] : ''}}">
                @endif
{{--                <div class="form-group m-form__group">--}}
{{--                    <label class="black_title">--}}
{{--                        @lang('Tên hồ sơ'): <b class="text-danger">*</b>--}}
{{--                    </label>--}}
{{--                    <div class="input-group">--}}
{{--                        <input type="text" name="file_name" id="file_name" class="form-control" value="{{$detailFile != null ? $detailFile['file_name'] : ''}}">--}}
{{--                        <input type="hidden" name="path" id="path" value="{{$detailFile != null ? $detailFile['path'] : ''}}">--}}
{{--                        <input type="hidden" name="file_type" id="file_type" value="{{$detailFile != null ? $detailFile['file_type'] : ''}}">--}}
{{--                        @if($detailFile != null)--}}
{{--                            <input type="hidden" name="manage_document_file_id" value="{{$detailFile != null ? $detailFile['manage_document_file_id'] : ''}}">--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="form-group m-form__group">--}}
{{--                    <label class="black_title">--}}
{{--                        @lang('Ghi chú'):--}}
{{--                    </label>--}}
{{--                    <div class="input-group">--}}
{{--                        <textarea type="text" name="note" class="form-control note" rows="5">{!! $detailFile != null ? $detailFile['note'] : '' !!}</textarea>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Hồ sơ đính kèm'): <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        <div class="input-group">
                            <div class="col-lg-12 col-md-12 col-sm-12 ss--padding-left-0">
                                <div class="m-dropzone dropzone m-dropzone--primary dz-clickable"
                                     action="{{route('manager-work.detail.upload-file')}}" id="dropzoneImage">
                                    <div class="m-dropzone__msg dz-message needsclick">
                                        <h3 class="m-dropzone__msg-title"><i class="fas fa-plus"></i></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row mt-3 upload-image-document" id="upload-image">
                        @if($detailFile != null)
                            <div class="image-show col-3">
                                <img class="img-fluid" src="{{$detailFile['file_type'] == 'file' ? asset('static/backend/images/document.png') : $detailFile['path']}}">
                                <input type="hidden" class="path" value="{{$detailFile['path']}}">
                                <input type="hidden" class="file_name" value="{{$detailFile['file_name']}}">
                                <input type="hidden" class="file_type" value="{{$detailFile['file_type']}}">

                                <span class="delete-img-document" style="display: block;">
                                    <a href="javascript:void(0)" onclick="Document.removeImage(this)">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        @if($detailFile != null)
                            <button data-dismiss="modal"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                            </button>
                            <button type="button" onclick="Document.addDocument(0)"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                            </button>
                        @else
                            <button data-dismiss="modal"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                            </button>
                            <button type="button" onclick="Document.addDocument(0)"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                            </button>
                            <button type="button" onclick="Document.addDocument(1)"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="fa fa-plus-circle m--margin-right-10"></i>
                                <span>{{ __('LƯU & TẠO MỚI') }}</span>
                            </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>