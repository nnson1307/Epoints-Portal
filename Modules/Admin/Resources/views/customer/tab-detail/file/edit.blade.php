<div class="modal fade" id="modal-file" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    @lang("CHỈNH SỬA TẬP TIN")
                </h4>
            </div>
            <div class="modal-body">
                <form id="form-file">
                    <div class="form-group m-form__group">
                        <div class="m-widget19__action">
                            <button type="button" onclick="document.getElementById('upload_tab_file').click()"
                                    class="btn btn-primary btn-sm color_button m-btn text-center">
                                {{ __('Tải hồ sơ') }}
                            </button>
                        </div>

                        <input accept=".pdf,.doc,.docx,.pdf,.csv,.xls,.xlsx,.docx, .jpeg,.jpg,.png"
                               id="upload_tab_file" type="file"
                               class="btn btn-primary btn-sm color_button m-btn text-center"
                               style="display: none" oninvalid="setCustomValidity('Please, blah, blah, blah ')"
                               onchange="detail.uploadFile(this)">
                    </div>
                    <div class="form-group m-form__group row" id="customer_tab_file">
                        <div class="col-lg-12">
                            <a target="_blank" href="{{$info['link']}}" value="{{$info['link']}}" type="{{$info['file_type']}}" name="customer_files[]" class="ss--text-black"
                               download="{{$info['file_name']}}">{{$info['file_name']}}</a>
                            <a href="javascript:void(0)" onclick="detail.removeFile(this)"><i class="la la-trash"></i></a>
                            <br>
                        </div>
                    </div>

                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nội dung'):
                        </label>
                        <div class="input-group">
                            <textarea id="note_file" name="note_file" class="form-control m-input class" cols="5" rows="5">{{$info['note']}}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                    </button>
                    <button type="button" onclick="detail.updateFile('{{$info['customer_file_id']}}')"
                            class="btn btn-primary  color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<span>@lang("CHỈNH SỬA")</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
