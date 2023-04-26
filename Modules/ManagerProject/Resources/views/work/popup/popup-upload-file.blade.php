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
                @if($detailFile != null && isset($detailFile['manage_project_document_id']))
                    <input type="hidden" class="manage_project_document_id" name="manage_project_document_id" value="{{$detailFile['manage_project_document_id']}}">
                @endif
                @if($detailFile != null && isset($detailFile['manage_document_file_id']))
                    <input type="hidden" class="manage_document_file_id" name="manage_document_file_id" value="{{$detailFile['manage_document_file_id']}}">
                @endif
                <div class="form-group m-form__group">
                    <div class="form-group">
                        <label class="black_title">
                            @lang('Loại upload'): <b class="text-danger">*</b>
                        </label>
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customRadio2" name="type_upload" value="file" {{$detailFile == null || ($detailFile != null && ($detailFile['type_upload'] == 'file' || !isset($detailFile['type_upload']))) ? 'checked' : ''}}>
                                <label class="custom-control-label" for="customRadio2">Upload file</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customRadio1" name="type_upload" value="link" {{$detailFile != null && $detailFile['type_upload'] == 'link' ? 'checked' : ''}}>
                                <label class="custom-control-label" for="customRadio1">Upload link</label>
                            </div>
                        </div>
                    </div>
                    <div class="upload-group upload-file"  style="{{$detailFile == null || ($detailFile != null && ($detailFile['type_upload'] == 'file' || !isset($detailFile['type_upload']))) ? '' : 'display:none'}}">
                        <label class="black_title">
                            @lang('Hồ sơ đính kèm'): <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 ss--padding-left-0">
                                    <div class="m-dropzone dropzone m-dropzone--primary dz-clickable"
                                         action="{{route('manager-project.work.detail.upload-file')}}" id="dropzoneImage">
                                        <div class="m-dropzone__msg dz-message needsclick">
                                            <h3 class="m-dropzone__msg-title"><i class="fas fa-plus"></i></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row mt-3 upload-image-document" id="upload-image">
                            @if($detailFile != null && $detailFile['type_upload'] != 'link')
                                <div class="image-show col-3">
                                    <img class="img-fluid" src="{{$detailFile['type'] == 'file' ? asset('static/backend/images/document.png') : $detailFile['path']}}">
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
                    <div class="upload-group upload-link" style="{{$detailFile == null || ($detailFile != null && ($detailFile['type_upload'] == 'file' || !isset($detailFile['type_upload']))) ? 'display:none' : ''}}">
                        <label class="black_title">
                            @lang('Tên link'): <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 ss--padding-left-0">
                                    <input type="text" class="form-control" name="name_upload" value="{{$detailFile != null && $detailFile['type_upload'] == 'link' ? $detailFile['file_name'] : ''}}" placeholder="{{__('Tên link')}}">
                                </div>
                            </div>
                        </div>

                        <label class="black_title">
                            @lang('Link'): <b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <div class="input-group">
                                <div class="col-lg-12 col-md-12 col-sm-12 ss--padding-left-0">
                                    <input type="text" class="form-control" name="link_upload" value="{{$detailFile != null && $detailFile['type_upload'] == 'link' ? $detailFile['path'] : ''}}" placeholder="{{__('Link')}}">
                                </div>
                            </div>
                        </div>
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

<script>
    $('input[name="type_upload"]').change(function (){
        $('.upload-group').hide();
        $('.upload-'+$(this).val()).show();
        $('#upload-image').empty();
    });
</script>